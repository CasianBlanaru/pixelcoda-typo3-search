<?php
// Generate password hash for TYPO3 backend user
// Run: php deployment/typo3/generate-password-hash.php

$password = 'Pixelcoda123!';
$hash = password_hash($password, PASSWORD_ARGON2ID);

echo "Password: {$password}\n";
echo "Hash: {$hash}\n\n";

echo "SQL to insert/update user:\n";
echo "DELETE FROM be_users WHERE username = 'pixelcoda';\n";
echo "INSERT INTO be_users (pid, tstamp, crdate, username, password, admin, disable, deleted, realName, email, lang) VALUES (0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'pixelcoda', '{$hash}', 1, 0, 0, 'PixelCoda Admin', 'admin@pixelcoda.de', 'de');\n";
