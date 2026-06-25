-- Setup TypoScript Template for TYPO3 Headless
-- This needs to be run once on Railway production database

-- Delete existing template for page 2 if exists
DELETE FROM sys_template WHERE pid = 2;

-- Insert root TypoScript template
INSERT INTO sys_template (
    pid,
    title,
    root,
    clear,
    include_static_file
) VALUES (
    2,
    'Main Template',
    1,
    3,
    'EXT:headless/Configuration/TypoScript/,EXT:pixelcoda_sitepackage/Configuration/TypoScript/'
);
