-- Update TypoScript template to include Headless setup
UPDATE sys_template 
SET 
  config = '
# Basic page configuration
page = PAGE
page {
  typeNum = 0
  10 = FLUIDTEMPLATE
  10 {
    templateRootPaths.10 = EXT:pixelcoda_sitepackage/Resources/Private/Templates/Page/
    layoutRootPaths.10 = EXT:pixelcoda_sitepackage/Resources/Private/Layouts/Page/
    partialRootPaths.10 = EXT:pixelcoda_sitepackage/Resources/Private/Partials/Page/
  }
}

# Include Headless extension setup
@import "EXT:headless/Configuration/TypoScript/setup.typoscript"

# Include GSAP Animation setup
@import "EXT:content_gsap_animation/Configuration/TypoScript/setup.typoscript"

# Include Frontend Editing setup
@import "EXT:pixelcoda_fe_editor/Configuration/TypoScript/setup.typoscript"
',
  constants = '
# Plugin configuration
plugin.tx_content_gsap_animation {
  gsap-easing = power2.out
  gsap-once = 1
  gsap-duration = 1000
}
',
  include_static_file = 'EXT:headless/Configuration/TypoScript/,EXT:content_gsap_animation/Configuration/TypoScript/,EXT:pixelcoda_fe_editor/Configuration/TypoScript/'
WHERE uid = 1;
