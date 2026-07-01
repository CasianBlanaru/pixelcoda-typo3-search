-- Force create/update pixelcoda admin user
-- This runs on every container start

-- Delete any existing pixelcoda user first
DELETE FROM be_users WHERE username = 'pixelcoda';

-- Insert fresh pixelcoda user with known password: Pixelcoda123!
-- Hash generated with TYPO3 parameters: memory_cost=65536, time_cost=16, threads=1
INSERT INTO be_users (
    username,
    password,
    admin,
    disable,
    deleted,
    tstamp,
    crdate,
    starttime,
    endtime,
    lang,
    usergroup
) VALUES (
    'pixelcoda',
    '$argon2id$v=19$m=65536,t=16,p=1$ZEhDODZPbFdBeWhhOEg1SQ$K4vduaDRHGLPxjaHFfsou1Tc85UtZ0IyOkP87eidtW0',
    1,
    0,
    0,
    UNIX_TIMESTAMP(),
    UNIX_TIMESTAMP(),
    0,
    0,
    'default',
    ''
);

-- Verify the insert
SELECT 
    uid,
    username,
    admin,
    disable,
    deleted,
    SUBSTRING(password, 1, 50) as password_hash
FROM be_users 
WHERE username = 'pixelcoda';
