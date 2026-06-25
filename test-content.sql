-- Test Content für Homepage
INSERT INTO tt_content (pid, CType, colPos, header, bodytext, hidden, deleted, tstamp, crdate) 
VALUES 
(2, 'text', 0, 'Welcome to TYPO3 Headless', '<p>This is a test content element with GSAP animation support.</p>', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
(2, 'text', 0, 'Second Element', '<p>Another test element to verify the headless integration.</p>', 0, 0, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- Add GSAP animation to first element
UPDATE tt_content 
SET 
  tx_content_gsap_animation_animation = 'fade-up',
  tx_content_gsap_animation_duration = 1000,
  tx_content_gsap_animation_delay = 0,
  tx_content_gsap_animation_easing = 'power2.out',
  tx_content_gsap_animation_once = 1
WHERE pid = 2 AND header = 'Welcome to TYPO3 Headless';
