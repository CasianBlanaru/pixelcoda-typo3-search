<?php
declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

putenv('TYPO3_PATH_ROOT=' . __DIR__ . '/../');
putenv('TYPO3_PATH_APP=' . dirname(__DIR__, 2));

Bootstrap::init(
    new \TYPO3\CMS\Core\Core\ApplicationContext('Production')
)->get(\TYPO3\CMS\Core\Database\ConnectionPool::class);

$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
    ->getQueryBuilderForTable('be_users');

$users = $queryBuilder
    ->select('uid', 'username', 'password', 'admin', 'disable', 'deleted')
    ->from('be_users')
    ->where(
        $queryBuilder->expr()->in('username', 
            $queryBuilder->createNamedParameter(['pixelcoda', 'admin', 'pixelcoda-admin'], \TYPO3\CMS\Core\Database\Connection::PARAM_STR_ARRAY)
        )
    )
    ->executeQuery()
    ->fetchAllAssociative();

header('Content-Type: text/plain');
echo "Backend Users Check\n";
echo "===================\n\n";

foreach ($users as $user) {
    echo "User: {$user['username']}\n";
    echo "  UID: {$user['uid']}\n";
    echo "  Admin: {$user['admin']}\n";
    echo "  Disabled: {$user['disable']}\n";
    echo "  Deleted: {$user['deleted']}\n";
    echo "  Password hash: " . substr($user['password'], 0, 60) . "...\n\n";
}

if (empty($users)) {
    echo "No admin users found!\n";
}
