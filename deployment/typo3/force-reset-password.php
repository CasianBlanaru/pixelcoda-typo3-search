<?php
declare(strict_types=1);

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

require __DIR__ . '/../../vendor/autoload.php';

$container = \TYPO3\CMS\Core\Core\Bootstrap::init(
    \TYPO3\CMS\Core\Core\Environment::getContext()
);

$password = 'Pixelcoda123!';
$passwordHash = password_hash($password, PASSWORD_ARGON2ID);

$connection = GeneralUtility::makeInstance(ConnectionPool::class)
    ->getConnectionForTable('be_users');

// Update pixelcoda user
$affected = $connection->update(
    'be_users',
    [
        'password' => $passwordHash,
        'admin' => 1,
        'disable' => 0,
        'deleted' => 0,
    ],
    ['username' => 'pixelcoda']
);

if ($affected > 0) {
    echo "Updated pixelcoda user with new password hash\n";
} else {
    // Insert if not exists
    $connection->insert(
        'be_users',
        [
            'username' => 'pixelcoda',
            'password' => $passwordHash,
            'admin' => 1,
            'disable' => 0,
            'deleted' => 0,
            'tstamp' => time(),
            'crdate' => time(),
        ]
    );
    echo "Created pixelcoda user\n";
}

// Verify
$user = $connection->select(
    ['username', 'admin', 'disable', 'deleted'],
    'be_users',
    ['username' => 'pixelcoda']
)->fetchAssociative();

echo "User verification:\n";
print_r($user);
echo "\nPassword hash (first 60 chars): " . substr($passwordHash, 0, 60) . "\n";
