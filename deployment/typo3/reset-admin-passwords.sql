-- Reset admin passwords to known values
-- Password: Pixelcoda123! (for all admin users)

SET @admin_password_hash = '$argon2id$v=19$m=65536,t=4,p=1$TUlzOU1pMXI1YUdNdkN2MQ$bbfkrYUnNNfnow3YuDzpxCB3CdRj0MgaKfPNABXs/OA';

-- Update pixelcoda admin
UPDATE be_users 
SET 
    password = @admin_password_hash,
    admin = 1,
    disable = 0,
    deleted = 0
WHERE username = 'pixelcoda';

-- Insert pixelcoda if not exists
INSERT INTO be_users (username, password, admin, disable, deleted, tstamp, crdate)
SELECT 'pixelcoda', @admin_password_hash, 1, 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()
WHERE NOT EXISTS (SELECT 1 FROM be_users WHERE username = 'pixelcoda');

-- Update admin user
UPDATE be_users 
SET 
    password = @admin_password_hash,
    admin = 1,
    disable = 0,
    deleted = 0
WHERE username = 'admin';

-- Update pixelcoda-admin user
UPDATE be_users 
SET 
    password = @admin_password_hash,
    admin = 1,
    disable = 0,
    deleted = 0
WHERE username = 'pixelcoda-admin';

SELECT CONCAT('Updated user: ', username) as message 
FROM be_users 
WHERE username IN ('pixelcoda', 'admin', 'pixelcoda-admin') 
AND deleted = 0;
