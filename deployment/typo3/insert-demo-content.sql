-- Insert demo content directly into tt_content table
-- This runs before TYPO3 starts, so it will work even if PHP scripts fail

SET @homepage_uid = (SELECT uid FROM pages WHERE doktype = 1 AND is_siteroot = 1 AND deleted = 0 ORDER BY uid ASC LIMIT 1);
SET @timestamp = UNIX_TIMESTAMP();

-- Only insert if no content exists
INSERT INTO tt_content (pid, colPos, sorting, CType, header, header_layout, bodytext, tstamp, crdate, sys_language_uid, hidden, deleted)
SELECT 
    @homepage_uid,
    0,
    256,
    'header',
    'Welcome to TYPO3 Headless',
    1,
    '',
    @timestamp,
    @timestamp,
    0,
    0,
    0
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM tt_content WHERE pid = @homepage_uid AND deleted = 0);

INSERT INTO tt_content (pid, colPos, sorting, CType, header, header_layout, bodytext, tstamp, crdate, sys_language_uid, hidden, deleted)
SELECT 
    @homepage_uid,
    0,
    512,
    'text',
    'Modern Content Management',
    2,
    '<p>This is a <strong>TYPO3 Headless</strong> setup with a Next.js frontend. Content is managed in TYPO3 and delivered as JSON via the headless extension.</p><p>You can edit this content in the TYPO3 backend and it will automatically appear here.</p>',
    @timestamp,
    @timestamp,
    0,
    0,
    0
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM tt_content WHERE pid = @homepage_uid AND deleted = 0 LIMIT 1);

INSERT INTO tt_content (pid, colPos, sorting, CType, header, header_layout, bodytext, tstamp, crdate, sys_language_uid, hidden, deleted)
SELECT 
    @homepage_uid,
    0,
    768,
    'text',
    'Key Features',
    2,
    '<ul><li>Decoupled Architecture</li><li>Next.js 16 with React 19</li><li>Server-Side Rendering</li><li>TYPO3 14.3 Backend</li><li>JSON API via Headless Extension</li></ul>',
    @timestamp,
    @timestamp,
    0,
    0,
    0
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM tt_content WHERE pid = @homepage_uid AND deleted = 0 LIMIT 1);
