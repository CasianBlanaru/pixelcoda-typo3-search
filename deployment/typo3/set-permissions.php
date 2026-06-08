<?php

declare(strict_types=1);

$databaseHost = (string)getenv('TYPO3_DB_HOST');
$databaseName = (string)getenv('TYPO3_DB_DBNAME');
$databaseUser = (string)getenv('TYPO3_DB_USERNAME');
$databasePassword = (string)getenv('TYPO3_DB_PASSWORD');
$databasePort = (int)(getenv('TYPO3_DB_PORT') ?: 3306);

if ('' === $databaseHost || '' === $databaseName || '' === $databaseUser) {
    echo "Missing database configuration environment variables.\n";
    exit(1);
}

try {
    $pdo = new PDO(
        sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $databaseHost, $databasePort, $databaseName),
        $databaseUser,
        $databasePassword,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// 1. Identify the root page
$rootPageId = (int)$pdo->query(
    'SELECT uid FROM pages WHERE pid = 0 AND is_siteroot = 1 AND deleted = 0 ORDER BY uid ASC LIMIT 1'
)->fetchColumn();

if ($rootPageId <= 0) {
    // Fallback to first page if no siteroot is defined yet
    $rootPageId = (int)$pdo->query(
        'SELECT uid FROM pages WHERE pid = 0 AND deleted = 0 ORDER BY uid ASC LIMIT 1'
    )->fetchColumn();
}

if ($rootPageId <= 0) {
    echo "No root page found.\n";
    exit(0); // Not a fatal error, maybe setup is not yet complete
}

echo "Found root page with UID: $rootPageId\n";

// 2. Identify backend groups
$groups = $pdo->query(
    "SELECT * FROM be_groups WHERE (title LIKE '%Editor%' OR title LIKE '%Redakteur%') AND deleted = 0"
)->fetchAll(PDO::FETCH_ASSOC);

if (empty($groups)) {
    echo "No editor groups found.\n";
    exit(0);
}

$firstGroupUid = (int)$groups[0]['uid'];

foreach ($groups as $group) {
    echo "Updating group: " . $group['title'] . " (UID: " . $group['uid'] . ")\n";

    $uid = (int)$group['uid'];

    // Modules
    $modules = array_filter(explode(',', $group['modules'] ?? ''));
    $requiredModules = ['web_layout', 'web_list', 'file_FilelistList', 'tools_PixelcodaSearchM1', 'help_AboutAbout'];
    $modules = array_unique(array_merge($modules, $requiredModules));

    // Tables
    $tablesSelect = array_filter(explode(',', $group['tables_select'] ?? ''));
    $tablesModify = array_filter(explode(',', $group['tables_modify'] ?? ''));
    $requiredTables = ['pages', 'tt_content', 'sys_file', 'sys_file_reference', 'sys_file_metadata', 'sys_category', 'tx_news_domain_model_news'];
    $tablesSelect = array_unique(array_merge($tablesSelect, $requiredTables));
    $tablesModify = array_unique(array_merge($tablesModify, $requiredTables));

    // Explicit Allow/Deny
    $explicitAllowDeny = array_filter(explode(',', $group['explicit_allowdeny'] ?? ''));
    $requiredAllow = [
        'tt_content:CType:pixelcodasearch_search:ALLOW',
        'tt_content:CType:pc_demo:ALLOW',
        'tt_content:CType:textmedia:ALLOW',
        'tt_content:CType:text:ALLOW',
        'tt_content:CType:image:ALLOW'
    ];
    $explicitAllowDeny = array_unique(array_merge($explicitAllowDeny, $requiredAllow));

    // DB Mounts
    $dbMounts = array_filter(explode(',', $group['db_mountpoints'] ?? ''));
    if (!in_array((string)$rootPageId, $dbMounts)) {
        $dbMounts[] = (string)$rootPageId;
    }

    // File Mounts (ensure access to default storage)
    $fileMounts = array_filter(explode(',', $group['file_mountpoints'] ?? ''));
    $defaultStorageId = (int)$pdo->query('SELECT uid FROM sys_file_storage LIMIT 1')->fetchColumn();
    if ($defaultStorageId > 0 && !in_array((string)$defaultStorageId, $fileMounts)) {
        $fileMounts[] = (string)$defaultStorageId;
    }

    // Non-exclude fields (ensure GSAP fields and other important ones are there)
    $nonExcludeFields = array_filter(explode(',', $group['non_exclude_fields'] ?? ''));
    $requiredFields = [
        'pages:title', 'pages:nav_title', 'pages:slug', 'pages:hidden', 'pages:starttime', 'pages:endtime',
        'tt_content:CType', 'tt_content:header', 'tt_content:bodytext', 'tt_content:image', 'tt_content:pi_flexform',
        'tt_content:tx_content_gsap_animation_animation',
        'tt_content:tx_content_gsap_animation_duration',
        'tt_content:tx_content_gsap_animation_delay',
        'tt_content:tx_content_gsap_animation_offset',
        'tt_content:tx_content_gsap_animation_anchor_placement',
        'tt_content:tx_content_gsap_animation_once',
        'tt_content:tx_content_gsap_animation_mirror',
        'tt_content:tx_content_gsap_animation_easing',
        'sys_file_reference:title', 'sys_file_reference:alternative', 'sys_file_reference:description'
    ];
    $nonExcludeFields = array_unique(array_merge($nonExcludeFields, $requiredFields));

    // File permissions
    $filePermissions = 'readFolder,writeFolder,addFolder,renameFolder,moveFolder,copyFolder,deleteFolder,readFile,writeFile,addFile,renameFile,replaceFile,moveFile,copyFile,deleteFile';

    $update = $pdo->prepare('
        UPDATE be_groups
        SET modules = :modules,
            tables_select = :tables_select,
            tables_modify = :tables_modify,
            explicit_allowdeny = :explicit_allowdeny,
            db_mountpoints = :db_mountpoints,
            file_mountpoints = :file_mountpoints,
            file_permissions = :file_permissions,
            non_exclude_fields = :non_exclude_fields
        WHERE uid = :uid
    ');

    $update->execute([
        'modules' => implode(',', $modules),
        'tables_select' => implode(',', $tablesSelect),
        'tables_modify' => implode(',', $tablesModify),
        'explicit_allowdeny' => implode(',', $explicitAllowDeny),
        'db_mountpoints' => implode(',', $dbMounts),
        'file_mountpoints' => implode(',', $fileMounts),
        'file_permissions' => $filePermissions,
        'non_exclude_fields' => implode(',', $nonExcludeFields),
        'uid' => $uid
    ]);
}

// 3. Update Page Permissions (ACLs)
// Set group to the first editor group and grant all permissions (31)
$pdo->prepare('UPDATE pages SET perms_groupid = :groupid, perms_group = 31 WHERE deleted = 0')
    ->execute(['groupid' => $firstGroupUid]);

echo "Permissions and Page ACLs updated successfully.\n";
