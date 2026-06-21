<?php
/**
 * Compiled ext_localconf.php cache file
 */
/**
 * Extension: core
 * File: /var/www/html/vendor/typo3/cms-core/ext_localconf.php
 */
namespace {




use TYPO3\CMS\Core\Authentication\AuthenticationService;
use TYPO3\CMS\Core\Controller\FileDumpController;
use TYPO3\CMS\Core\Hooks\BackendUserPasswordCheck;
use TYPO3\CMS\Core\Hooks\CreateSiteConfiguration;
use TYPO3\CMS\Core\Hooks\DestroySessionHook;
use TYPO3\CMS\Core\Hooks\PagesTsConfigGuard;
use TYPO3\CMS\Core\Hooks\SystemMaintainerAllowanceCheck;
use TYPO3\CMS\Core\Hooks\UpdateFileIndexEntry;
use TYPO3\CMS\Core\MetaTag\EdgeMetaTagManager;
use TYPO3\CMS\Core\MetaTag\Html5MetaTagManager;
use TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry;
use TYPO3\CMS\Core\Resource\MimeTypeCompatibilityTypeGuesser;
use TYPO3\CMS\Core\Resource\Rendering\AudioTagRenderer;
use TYPO3\CMS\Core\Resource\Rendering\RendererRegistry;
use TYPO3\CMS\Core\Resource\Rendering\VideoTagRenderer;
use TYPO3\CMS\Core\Resource\Rendering\VimeoRenderer;
use TYPO3\CMS\Core\Resource\Rendering\YouTubeRenderer;
use TYPO3\CMS\Core\Resource\Security\FileMetadataPermissionsAspect;
use TYPO3\CMS\Core\Resource\Security\FilePermissionAspect;
use TYPO3\CMS\Core\Resource\Security\SvgHookHandler;
use TYPO3\CMS\Core\Resource\TextExtraction\PlainTextExtractor;
use TYPO3\CMS\Core\Resource\TextExtraction\TextExtractorRegistry;
use TYPO3\CMS\Core\Type\File\FileInfo;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') or die();

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][GeneralUtility::class]['moveUploadedFile'][] = SvgHookHandler::class . '->processMoveUploadedFile';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = FileMetadataPermissionsAspect::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = BackendUserPasswordCheck::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = SystemMaintainerAllowanceCheck::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['checkModifyAccessList'][] = FileMetadataPermissionsAspect::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['checkModifyAccessList'][] = FilePermissionAspect::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = FilePermissionAspect::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = DestroySessionHook::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = PagesTsConfigGuard::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][CreateSiteConfiguration::class] = CreateSiteConfiguration::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][UpdateFileIndexEntry::class] = UpdateFileIndexEntry::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][FileInfo::class]['mimeTypeGuessers'][MimeTypeCompatibilityTypeGuesser::class] = MimeTypeCompatibilityTypeGuesser::class . '->guessMimeType';
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['dumpFile'] = FileDumpController::class . '::dumpAction';

$rendererRegistry = GeneralUtility::makeInstance(RendererRegistry::class);
$rendererRegistry->registerRendererClass(AudioTagRenderer::class);
$rendererRegistry->registerRendererClass(VideoTagRenderer::class);
$rendererRegistry->registerRendererClass(YouTubeRenderer::class);
$rendererRegistry->registerRendererClass(VimeoRenderer::class);
unset($rendererRegistry);

$textExtractorRegistry = GeneralUtility::makeInstance(TextExtractorRegistry::class);
$textExtractorRegistry->registerTextExtractor(PlainTextExtractor::class);
unset($textExtractorRegistry);

// Register base authentication service
ExtensionManagementUtility::addService(
    'core',
    'auth',
    AuthenticationService::class,
    [
        'title' => 'User authentication',
        'description' => 'Authentication with username/password.',
        'subtype' => 'getUserBE,getUserFE,authUserBE,authUserFE,processLoginDataBE,processLoginDataFE',
        'available' => true,
        'priority' => 50,
        'quality' => 50,
        'os' => '',
        'exec' => '',
        'className' => TYPO3\CMS\Core\Authentication\AuthenticationService::class,
    ]
);

$metaTagManagerRegistry = GeneralUtility::makeInstance(MetaTagManagerRegistry::class);
$metaTagManagerRegistry->registerManager(
    'html5',
    Html5MetaTagManager::class
);
$metaTagManagerRegistry->registerManager(
    'edge',
    EdgeMetaTagManager::class
);
unset($metaTagManagerRegistry);

// Add module configuration
ExtensionManagementUtility::addTypoScriptSetup('
    config.pageTitleProviders {
        record.provider = TYPO3\CMS\Core\PageTitle\RecordPageTitleProvider
        recordTitle {
            provider = TYPO3\CMS\Core\PageTitle\RecordTitleProvider
            before = record
        }
    }
');

// Register preset for sys_news
if (empty($GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['sys_news'])) {
    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['sys_news'] = 'EXT:core/Configuration/RTE/SysNews.yaml';
}
}


/**
 * Extension: extbase
 * File: /var/www/html/vendor/typo3/cms-extbase/ext_localconf.php
 */
namespace {




use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

ExtensionManagementUtility::addTypoScriptSetup('
config.tx_extbase {
    mvc {
        throwPageNotFoundExceptionIfActionCantBeResolved = 0
    }
    persistence {
        enableAutomaticCacheClearing = 1
    }
}
');
}


/**
 * Extension: backend
 * File: /var/www/html/vendor/typo3/cms-backend/ext_localconf.php
 */
namespace {




use TYPO3\CMS\Backend\Hooks\DataHandlerAuthenticationContext;
use TYPO3\CMS\Backend\Hooks\DataHandlerContentElementRestrictionHook;
use TYPO3\CMS\Backend\LoginProvider\UsernamePasswordLoginProvider;

defined('TYPO3') or die();

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['backend']['loginProviders'][1433416747] = [
    'provider' => UsernamePasswordLoginProvider::class,
    'sorting' => 50,
    'iconIdentifier' => 'actions-key',
    'label' => 'LLL:EXT:backend/Resources/Private/Language/locallang.xlf:login.link',
];

// Register search key shortcuts
$GLOBALS['TYPO3_CONF_VARS']['SYS']['livesearch']['page'] = 'pages';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = DataHandlerAuthenticationContext::class;

// Giving the two hook registrations a unique name to allow unsetting this hook if it is not wanted.
// ext:container does this to apply own logic.
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['contentElementRestriction'] = DataHandlerContentElementRestrictionHook::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['contentElementRestriction'] = DataHandlerContentElementRestrictionHook::class;

// Initialize empty structure for backward compatibility with extensions
// that add fields via $GLOBALS['TYPO3_USER_SETTINGS']['columns'].
// Core settings are now defined in Configuration/TCA/Overrides/be_users.php.
// Access to settings should go through UserSettingsSchema which merges both sources.
// @deprecated since TYPO3 v14, remove in TYPO3 v15
$GLOBALS['TYPO3_USER_SETTINGS'] = [
    'columns' => [],
    'showitem' => '',
];
}


/**
 * Extension: frontend
 * File: /var/www/html/vendor/typo3/cms-frontend/ext_localconf.php
 */
namespace {




use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Frontend\Controller\ShowImageController;

defined('TYPO3') or die();

// Register eID provider for showpic
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['tx_cms_showpic'] = ShowImageController::class . '::processRequest';

ExtensionManagementUtility::addTypoScriptSetup(
    '
# Creates persistent ParseFunc setup for non-HTML content.
lib.parseFunc {
    makelinks = 1
    makelinks {
        http {
            keep = path
            extTarget = _blank
        }
        mailto {
            keep = path
        }
    }
    tags {
        a = TEXT
        a {
            current = 1
            typolink {
                parameter.data = parameters:href
                title.data = parameters:title
                target.data = parameters:target
                ATagParams.data = parameters:allParams
            }
        }
    }
    nonTypoTagStdWrap {
        HTMLparser = 1
        HTMLparser {
            keepNonMatchedTags = 1
            htmlSpecialChars = 2
        }
    }
}

# Creates persistent ParseFunc setup for RTE content (which is mainly HTML) based on the "default" transformation.
lib.parseFunc_RTE < lib.parseFunc
lib.parseFunc_RTE {
    # Processing <ol>, <ul> and <table> blocks separately
    externalBlocks = article, aside, blockquote, div, dd, dl, footer, header, nav, ol, section, table, ul, pre, figure, figcaption
    externalBlocks {
        ol {
            stripNL = 1
            stdWrap.parseFunc = < lib.parseFunc
        }
        ul {
            stripNL = 1
            stdWrap.parseFunc = < lib.parseFunc
        }
        pre {
            stdWrap.parseFunc < lib.parseFunc
        }
        table {
            stripNL = 1
            stdWrap {
                HTMLparser = 1
                HTMLparser {
                    keepNonMatchedTags = 1
                }
            }
            HTMLtableCells = 1
            HTMLtableCells {
                # Recursive call to self but without wrapping non-wrapped cell content
                default.stdWrap {
                    parseFunc = < lib.parseFunc_RTE
                    parseFunc.nonTypoTagStdWrap.encapsLines {
                        nonWrappedTag =
                        innerStdWrap_all.ifBlank =
                    }
                }
            }
        }
        # ideally, "div" is not calling itself recursive, but instead uses a similar approach as ol/ul/pre
        # so it is a container with content, which does not need to be wrapping <p> tags in it.
        div {
            stripNL = 1
            callRecursive = 1
        }
        article < .div
        aside < .div
        figure < .div
        figcaption {
            stripNL = 1
        }
        blockquote < .div
        footer < .div
        header < .div
        nav < .div
        section < .div
        dl < .div
        dd < .div
    }
    nonTypoTagStdWrap {
        encapsLines {
            encapsTagList = p,pre,h1,h2,h3,h4,h5,h6,hr,dt
            nonWrappedTag = P
            innerStdWrap_all.ifBlank = &nbsp;
        }
    }
}

# Content selection
styles.content.get = CONTENT
styles.content.get {
    table = tt_content
    select {
        orderBy = sorting
        where = {#colPos}=0
    }
}


# Content element rendering
tt_content = CASE
tt_content {
    key {
        field = CType
    }
    default = TEXT
    default {
        field = CType
        htmlSpecialChars = 1
        wrap = <p style="background-color: yellow; padding: 0.5em 1em;"><strong>ERROR:</strong> Content Element with uid "{field:uid}" and type "|" has no rendering definition!</p>
        wrap.insertData = 1
    }
}
    '
);

// Register search key shortcuts
$GLOBALS['TYPO3_CONF_VARS']['SYS']['livesearch']['content'] = 'tt_content';
}


/**
 * Extension: dashboard
 * File: /var/www/html/vendor/typo3/cms-dashboard/ext_localconf.php
 */
namespace {




use TYPO3\CMS\Core\Cache\Backend\FileBackend;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Dashboard\Persistence\DashboardCreationEnricher;

defined('TYPO3') or die();

if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['dashboard_rss'] ?? null)) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['dashboard_rss'] = [
        'frontend' => VariableFrontend::class,
        'backend' => FileBackend::class,
        'options' => [
            'defaultLifetime' => 900,
        ],
    ];
}

// Fill the "owner" field of a dashboard with the user who created it
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = DashboardCreationEnricher::class;
}


/**
 * Extension: fluid_styled_content
 * File: /var/www/html/vendor/typo3/cms-fluid-styled-content/ext_localconf.php
 */
namespace {




defined('TYPO3') or die();

// Define TypoScript as content rendering template
$GLOBALS['TYPO3_CONF_VARS']['FE']['contentRenderingTemplates'][] = 'fluidstyledcontent/Configuration/TypoScript/';
}


/**
 * Extension: form
 * File: /var/www/html/vendor/typo3/cms-form/ext_localconf.php
 */
namespace {




use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Form\Controller\FormFrontendController;
use TYPO3\CMS\Form\Evaluation\EmailOrFormElementIdentifier;
use TYPO3\CMS\Form\Hooks\ImportExportHook;
use TYPO3\CMS\Form\Mvc\Property\PropertyMappingConfiguration;

defined('TYPO3') or die();

if (ExtensionManagementUtility::isLoaded('impexp')) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/impexp/class.tx_impexp.php']['before_addSysFileRecord'][1530637161]
        = ImportExportHook::class . '->beforeAddSysFileRecordOnImport';
}

// Register RTE presets for form extension
// form-label: Simple formatting for labels (bold, italic, link)
if (empty($GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['form-label'])) {
    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['form-label'] = 'EXT:form/Configuration/RTE/FormLabel.yaml';
}
// form-content: Extended formatting for content fields (includes lists)
if (empty($GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['form-content'])) {
    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['form-content'] = 'EXT:form/Configuration/RTE/FormContent.yaml';
}

// FE file upload processing
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/form']['afterFormStateInitialized'][1613296803] = PropertyMappingConfiguration::class;

// Add validation call for input which contains email or form element identifier
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][EmailOrFormElementIdentifier::class] = '';

// Register FE plugin
ExtensionUtility::configurePlugin('Form', 'Formframework', [FormFrontendController::class => ['render', 'perform']], [FormFrontendController::class => ['perform']]);
}


/**
 * Extension: seo
 * File: /var/www/html/vendor/typo3/cms-seo/ext_localconf.php
 */
namespace {




use TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Seo\Canonical\CanonicalGenerator;
use TYPO3\CMS\Seo\MetaTag\MetaTagGenerator;
use TYPO3\CMS\Seo\MetaTag\OpenGraphMetaTagManager;
use TYPO3\CMS\Seo\MetaTag\TwitterCardMetaTagManager;

defined('TYPO3') or die();

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['TYPO3\CMS\Frontend\Page\PageGenerator']['generateMetaTags']['metatag']
    = MetaTagGenerator::class . '->generate';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['TYPO3\CMS\Frontend\Page\PageGenerator']['generateMetaTags']['canonical']
    = CanonicalGenerator::class . '->generate';

$metaTagManagerRegistry = GeneralUtility::makeInstance(MetaTagManagerRegistry::class);
$metaTagManagerRegistry->registerManager(
    'opengraph',
    OpenGraphMetaTagManager::class
);
$metaTagManagerRegistry->registerManager(
    'twitter',
    TwitterCardMetaTagManager::class
);
unset($metaTagManagerRegistry);

// Add module configuration
ExtensionManagementUtility::addTypoScriptSetup(trim('
    config.pageTitleProviders {
        seo {
            provider = TYPO3\CMS\Seo\PageTitle\SeoTitlePageTitleProvider
            before = record
        }
    }
'));
}


/**
 * Extension: sys_note
 * File: /var/www/html/vendor/typo3/cms-sys-note/ext_localconf.php
 */
namespace {




use TYPO3\CMS\SysNote\Persistence\NoteCreationEnricher;

defined('TYPO3') or die();

// Fill the "owner" field of a sys_note with the user who created it
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = NoteCreationEnricher::class;
}


/**
 * Extension: felogin
 * File: /var/www/html/vendor/typo3/cms-felogin/ext_localconf.php
 */
namespace {




use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\FrontendLogin\Controller\LoginController;
use TYPO3\CMS\FrontendLogin\Controller\PasswordRecoveryController;

defined('TYPO3') or die();

// Add default TypoScript
ExtensionManagementUtility::addTypoScriptConstants(
    "@import 'EXT:felogin/Configuration/TypoScript/constants.typoscript'",
    false
);
ExtensionManagementUtility::addTypoScriptSetup(
    "@import 'EXT:felogin/Configuration/TypoScript/setup.typoscript'",
    false
);

ExtensionUtility::configurePlugin(
    'Felogin',
    'Login',
    [
        LoginController::class => ['login', 'overview'],
        PasswordRecoveryController::class => ['recovery', 'showChangePassword', 'changePassword'],
    ],
    [
        LoginController::class => ['login', 'overview'],
        PasswordRecoveryController::class => ['recovery', 'showChangePassword', 'changePassword'],
    ],
);
}


/**
 * Extension: reactions
 * File: /var/www/html/vendor/typo3/cms-reactions/ext_localconf.php
 */
namespace {




use TYPO3\CMS\Reactions\Form\Element\FieldMapElement;

defined('TYPO3') or die();

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1660911089] = [
    'nodeName' => 'fieldMap',
    'priority' => 40,
    'class' => FieldMapElement::class,
];
}


/**
 * Extension: rte_ckeditor
 * File: /var/www/html/vendor/typo3/cms-rte-ckeditor/ext_localconf.php
 */
namespace {




use TYPO3\CMS\RteCKEditor\Form\Resolver\RichTextNodeResolver;

defined('TYPO3') or die();

// Register FormEngine node type resolver hook to render RTE in FormEngine if enabled
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeResolver'][1480314091] = [
    'nodeName' => 'text',
    'priority' => 50,
    'class' => RichTextNodeResolver::class,
];

// Register the presets
if (empty($GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'])) {
    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] = 'EXT:rte_ckeditor/Configuration/RTE/Default.yaml';
}
if (empty($GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['minimal'])) {
    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['minimal'] = 'EXT:rte_ckeditor/Configuration/RTE/Minimal.yaml';
}
if (empty($GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['full'])) {
    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['full'] = 'EXT:rte_ckeditor/Configuration/RTE/Full.yaml';
}
}


/**
 * Extension: tstemplate
 * File: /var/www/html/vendor/typo3/cms-tstemplate/ext_localconf.php
 */
namespace {




use TYPO3\CMS\Tstemplate\Hooks\DataHandlerClearCachePostProcHook;

defined('TYPO3') or die();

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['tstemplate'] = DataHandlerClearCachePostProcHook::class . '->clearPageCacheIfNecessary';
}


/**
 * Extension: webhooks
 * File: /var/www/html/vendor/typo3/cms-webhooks/ext_localconf.php
 */
namespace {




defined('TYPO3') or die();

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = \TYPO3\CMS\Webhooks\Listener\PageModificationListener::class;
}


/**
 * Extension: content_gsap_animation
 * File: /var/www/html/vendor/pixelcoda/content-gsap-animation/ext_localconf.php
 */
namespace {


if (!defined('TYPO3')) {
    die ('Access denied.');
}

// Register custom renderType
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1552428667] = [
    'nodeName' => 'animationPreview',
    'priority' => '40',
    'class' => \Pixelcoda\ContentGsapAnimation\Form\Elements\AnimationPreviewField::class
];

// Register custom typoscript FILECONTENT cObject (can be removed once v11 support is dropped)
if(!isset($GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects']['FILECONTENT'])) {
	$GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects'] = array_merge(
		$GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects'] ?? [],
		['FILECONTENT' => \Pixelcoda\ContentGsapAnimation\ContentObject\FileContentContentObject::class]
	);
}
}


/**
 * Extension: headless
 * File: /var/www/html/vendor/friendsoftypo3/headless/ext_localconf.php
 */
namespace {


/*
 * This file is part of the "headless" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use FriendsOfTYPO3\Headless\Hooks\FileOrFolderLinkBuilder;
use FriendsOfTYPO3\Headless\Seo\MetaTag\EdgeMetaTagManager;
use FriendsOfTYPO3\Headless\Seo\MetaTag\Html5MetaTagManager;
use FriendsOfTYPO3\Headless\Seo\MetaTag\OpenGraphMetaTagManager;
use FriendsOfTYPO3\Headless\Seo\MetaTag\TwitterCardMetaTagManager;
use FriendsOfTYPO3\Headless\Resource\Rendering\AudioTagRenderer;
use FriendsOfTYPO3\Headless\Resource\Rendering\VideoTagRenderer;
use FriendsOfTYPO3\Headless\Resource\Rendering\VimeoRenderer;
use FriendsOfTYPO3\Headless\Resource\Rendering\YouTubeRenderer;
use FriendsOfTYPO3\Headless\Seo\CanonicalGenerator;
use FriendsOfTYPO3\Headless\XClass\ResourceLocalDriver;
use TYPO3\CMS\Core\Configuration\Features;
use TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry;
use TYPO3\CMS\Core\Resource\Driver\LocalDriver;
use TYPO3\CMS\Core\Resource\Rendering\RendererRegistry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Form\Controller\FormFrontendController;
use TYPO3\CMS\Form\Domain\Runtime\FormRuntime;
use TYPO3\CMS\FrontendLogin\Controller\LoginController;
use TYPO3\CMS\Workspaces\Controller\PreviewController;
use TYPO3\CMS\Workspaces\Preview\PreviewUriBuilder;

defined('TYPO3') || die();

call_user_func(
    static function () {
        $GLOBALS['TYPO3_CONF_VARS']['FE']['contentRenderingTemplates'][] = 'headless/Configuration/TypoScript/';

        $GLOBALS['TYPO3_CONF_VARS']['FE']['typolinkBuilder']['file'] = FileOrFolderLinkBuilder::class;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['headless'] = [
            'FriendsOfTYPO3\Headless\ViewHelpers'
        ];

        $features = GeneralUtility::makeInstance(Features::class);

        if ($features->isFeatureEnabled('headless.storageProxy')) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][LocalDriver::class] = [
                'className' => ResourceLocalDriver::class
            ];

            $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][ImageService::class] = [
                'className' => FriendsOfTYPO3\Headless\XClass\ImageService::class
            ];
        }

        if (ExtensionManagementUtility::isLoaded('form')) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][FormFrontendController::class] = [
                'className' => FriendsOfTYPO3\Headless\XClass\Controller\FormFrontendController::class
            ];

            $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][FormRuntime::class] = [
                'className' => FriendsOfTYPO3\Headless\XClass\FormRuntime::class
            ];
        }

        if (ExtensionManagementUtility::isLoaded('felogin')) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][LoginController::class] = [
                'className' => FriendsOfTYPO3\Headless\XClass\Controller\LoginController::class
            ];
        }

        if (ExtensionManagementUtility::isLoaded('workspaces')) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][PreviewUriBuilder::class] = [
                'className' => FriendsOfTYPO3\Headless\XClass\Preview\PreviewUriBuilder::class
            ];
        }

        if (ExtensionManagementUtility::isLoaded('seo')) {
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['TYPO3\CMS\Frontend\Page\PageGenerator']['generateMetaTags']['canonical'] =
                CanonicalGenerator::class . '->handle';

            $metaTagManagerRegistry = GeneralUtility::makeInstance(MetaTagManagerRegistry::class);
            $metaTagManagerRegistry->registerManager(
                'html5',
                Html5MetaTagManager::class
            );
            $metaTagManagerRegistry->registerManager(
                'edge',
                EdgeMetaTagManager::class
            );
            $metaTagManagerRegistry->registerManager(
                'opengraph',
                OpenGraphMetaTagManager::class
            );
            $metaTagManagerRegistry->registerManager(
                'twitter',
                TwitterCardMetaTagManager::class
            );
            unset($metaTagManagerRegistry);
        }

        $rendererRegistry = GeneralUtility::makeInstance(RendererRegistry::class);
        $rendererRegistry->registerRendererClass(YouTubeRenderer::class);
        $rendererRegistry->registerRendererClass(VimeoRenderer::class);
        $rendererRegistry->registerRendererClass(AudioTagRenderer::class);
        $rendererRegistry->registerRendererClass(VideoTagRenderer::class);
        unset($rendererRegistry);
    }
);
}


/**
 * Extension: pixelcoda_fe_editor
 * File: /var/www/html/vendor/pixelcoda/fe-editor/ext_localconf.php
 */
namespace {

defined('TYPO3') || die();
}


/**
 * Extension: pixelcoda_search
 * File: /var/www/html/vendor/pixelcoda/typo3-search/ext_localconf.php
 */
namespace {




use PixelCoda\PixelcodaSearch\Controller\Api\PluginConfigController;
use PixelCoda\PixelcodaSearch\Controller\SearchController;
use PixelCoda\PixelcodaSearch\Eid\SuggestEid;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || exit();

// ExtensionUtility removed - not needed anymore

// Register DataHandler hooks for automatic indexing (temporarily disabled due to signature issues)
// $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = DatamapHook::class;
// $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = DatamapHook::class;

// CLI commands are now registered via Configuration/Services.yaml

// Auto-include TypoScript setup
ExtensionManagementUtility::addTypoScriptSetup(
    '@import "EXT:pixelcoda_search/Configuration/TypoScript/setup.typoscript"'
);

// Auto-include TypoScript constants
ExtensionManagementUtility::addTypoScriptConstants(
    '@import "EXT:pixelcoda_search/Configuration/TypoScript/constants.typoscript"'
);

// Plugin registration removed - using Content Element only (TYPO3 Best Practice)
// The search functionality is provided as a Content Element (CType: pixelcodasearch_search)
// This prevents duplicate registration and follows TYPO3 Headless best practices

// Register AJAX endpoints for search suggestions
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['pixelcoda_suggest']
    = SuggestEid::class . '::processRequest';

// Register API routes for plugin configuration
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['pixelcoda_config']
    = PluginConfigController::class . '::getPluginConfig';

// Register page type for JSON API (headless mode)
$GLOBALS['TYPO3_CONF_VARS']['FE']['PageTypesToNoCache'][1699] = true;

// Environment variables provide defaults without overwriting values configured
// through TYPO3's extension configuration.
$extensionConfiguration = &$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['pixelcoda_search'];
$extensionConfiguration['api_url'] = trim((string)($extensionConfiguration['api_url'] ?? '')) !== ''
    ? $extensionConfiguration['api_url']
    : (getenv('PIXELCODA_API_URL') ?: 'http://127.0.0.1:8787');
$extensionConfiguration['api_key'] = trim((string)($extensionConfiguration['api_key'] ?? '')) !== ''
    ? $extensionConfiguration['api_key']
    : (getenv('PIXELCODA_API_KEY') ?: getenv('API_WRITE_KEY') ?: 'pc_write_dev_key');
$extensionConfiguration['read_api_key'] = trim((string)($extensionConfiguration['read_api_key'] ?? '')) !== ''
    ? $extensionConfiguration['read_api_key']
    : (getenv('PIXELCODA_READ_API_KEY') ?: getenv('API_READ_KEY') ?: 'pc_read_dev_key');
$extensionConfiguration['hmac_secret'] = trim((string)($extensionConfiguration['hmac_secret'] ?? '')) !== ''
    ? $extensionConfiguration['hmac_secret']
    : (getenv('PIXELCODA_HMAC_SECRET') ?: '');
$extensionConfiguration['project_id'] = trim((string)($extensionConfiguration['project_id'] ?? '')) !== ''
    ? $extensionConfiguration['project_id']
    : (getenv('PIXELCODA_PROJECT_ID') ?: 'typo3');
$extensionConfiguration['typo3_headless_url'] = trim((string)($extensionConfiguration['typo3_headless_url'] ?? '')) !== ''
    ? $extensionConfiguration['typo3_headless_url']
    : (getenv('TYPO3_HEADLESS_URL') ?: '');
$extensionConfiguration['cors_origins'] = trim((string)($extensionConfiguration['cors_origins'] ?? '')) !== ''
    ? $extensionConfiguration['cors_origins']
    : (getenv('PIXELCODA_CORS_ORIGINS') ?: '');
$extensionConfiguration['enabled_tables'] ??= ['pages', 'tt_content', 'tx_news_domain_model_news'];
$extensionConfiguration['default_mode'] ??= 'classic';
$extensionConfiguration['enable_auto_index'] ??= true;
$extensionConfiguration['enable_vector_search'] ??= true;
$extensionConfiguration['enable_metrics'] ??= false;
$extensionConfiguration['batch_size'] ??= 50;
$extensionConfiguration['timeout'] ??= 30;
$extensionConfiguration['debug_mode'] ??= false;

// Static TypoScript files are now added in ext_tables.php

// Register the search plugin for the search results page
ExtensionUtility::configurePlugin(
    'PixelcodaSearch',
    'SearchResults',
    [
        SearchController::class => 'search,suggest',
    ],
    // non-cacheable actions
    [
        SearchController::class => 'search,suggest',
    ]
);

ExtensionUtility::configurePlugin(
    'PixelcodaSearch',
    'Search',
    [
        SearchController::class => 'index,search,suggest,apiSearch,apiAsk,apiAskStream',
    ],
    [
        SearchController::class => 'index,search,suggest,apiSearch,apiAsk,apiAskStream',
    ]
);

ExtensionUtility::configurePlugin(
    'PixelcodaSearch',
    'SearchApi',
    [
        SearchController::class => 'apiSearch',
    ],
    [
        SearchController::class => 'apiSearch',
    ]
);

ExtensionUtility::configurePlugin(
    'PixelcodaSearch',
    'AskApi',
    [
        SearchController::class => 'apiAsk',
    ],
    [
        SearchController::class => 'apiAsk',
    ]
);

ExtensionUtility::configurePlugin(
    'PixelcodaSearch',
    'AskStreamApi',
    [
        SearchController::class => 'apiAskStream',
    ],
    [
        SearchController::class => 'apiAskStream',
    ]
);

// Register EID handler for AJAX autocomplete
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['search_suggest']
    = SuggestEid::class . '::processRequest';

// Register webhook endpoint
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['pixelcoda_webhook']
    = PixelCoda\PixelcodaSearch\Controller\WebhookController::class . '::indexAction';
}


#