-- Force reset admin password for Railway deployment
-- Run manually via Railway MySQL console if login fails
-- Password: Pixelcoda123!

SET @password_hash = '$argon2id$v=19$m=65536,t=16,p=1$ZmZ2VnZuY0ZkSzJ6RUJuSQ$CmhNL3pYdE5wVXBHZmlQL1IvV0dXUT09';

-- Delete any existing pixelcoda users first
DELETE FROM be_users WHERE username = 'pixelcoda';

-- Insert fresh pixelcoda admin
INSERT INTO be_users (
    pid, 
    tstamp, 
    crdate, 
    username, 
    password, 
    admin, 
    disable, 
    deleted,
    realName,
    email,
    lang
) VALUES (
    0,
    UNIX_TIMESTAMP(),
    UNIX_TIMESTAMP(),
    'pixelcoda',
    @password_hash,
    1,
    0,
    0,
    'PixelCoda Admin',
    'admin@pixelcoda.de',
    'de'
);

SELECT 'Admin user created/updated successfully' as status;
SELECT uid, username, admin, disable, deleted FROM be_users WHERE username = 'pixelcoda';
