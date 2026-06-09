<?php
return [
    'BE' => [
        'debug' => false,
        'installToolPassword' => '$argon2i$v=19$m=65536,t=16,p=1$dkVNbFhhV2ZNOWhWemE0Mg$Gc7oL2gJecDVlhqBLeWqnUIT7onyfXQ3RH+EwruzbQU',
        'passwordHashing' => [
            'className' => 'TYPO3\\CMS\\Core\\Crypto\\PasswordHashing\\Argon2iPasswordHash',
            'options' => [],
        ],
    ],
    'DB' => [
        'Connections' => [
            'Default' => [
                'charset' => 'utf8mb4',
                'dbname' => 'db',
                'defaultTableOptions' => [
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                ],
                'driver' => 'mysqli',
                'host' => 'db',
                'password' => 'db',
                'port' => 3306,
                'user' => 'db',
            ],
        ],
    ],
    'EXTENSIONS' => [
        'backend' => [
            'backendFavicon' => '',
            'backendLogo' => '',
            'loginBackgroundImage' => '',
            'loginFootnote' => '',
            'loginHighlightColor' => '',
            'loginLogo' => '',
            'loginLogoAlt' => '',
        ],
        'content_gsap_animation' => [
            'disableAddAnimationsTab' => '0',
            'extendedAnimationSettings' => '1',
            'hideFooterAnimationLabel' => '0',
        ],
        'pixelcoda_fe_editor' => [
            'ai' => [
                'apiKey' => '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',
                'enabled' => '1',
                'model' => '',
                'provider' => 'anthropic',
            ],
        ],
        'pixelcoda_search' => [
            'api_key' => 'pc_write_dev_key',
            'api_url' => 'http://host.docker.internal:8787',
            'batch_size' => '50',
            'cors_origins' => '',
            'debug_mode' => '0',
            'default_mode' => 'headless',
            'enable_auto_index' => '1',
            'enable_metrics' => '0',
            'enable_vector_search' => '1',
            'enabled_tables' => 'pages,tt_content',
            'hmac_secret' => '',
            'project_id' => 'typo3',
            'read_api_key' => 'pc_read_dev_key',
            'timeout' => '30',
        ],
    ],
    'FE' => [
        'cacheHash' => [
            'enforceValidation' => true,
        ],
        'debug' => false,
        'disableNoCacheParameter' => true,
        'passwordHashing' => [
            'className' => 'TYPO3\\CMS\\Core\\Crypto\\PasswordHashing\\Argon2iPasswordHash',
            'options' => [],
        ],
    ],
    'GFX' => [
        'processor' => 'GraphicsMagick',
        'processor_effects' => false,
        'processor_enabled' => true,
        'processor_path' => '/usr/bin/',
    ],
    'LOG' => [
        'TYPO3' => [
            'CMS' => [
                'deprecations' => [
                    'writerConfiguration' => [
                        'notice' => [
                            'TYPO3\CMS\Core\Log\Writer\FileWriter' => [
                                'disabled' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'MAIL' => [
        'transport' => 'sendmail',
        'transport_sendmail_command' => '/usr/local/bin/mailpit sendmail -t --smtp-addr 127.0.0.1:1025',
        'transport_smtp_encrypt' => '',
        'transport_smtp_password' => '',
        'transport_smtp_server' => '',
        'transport_smtp_username' => '',
    ],
    'SYS' => [
        'UTF8filesystem' => true,
        'caching' => [
            'cacheConfigurations' => [
                'hash' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                ],
                'pages' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                    'options' => [
                        'compression' => true,
                    ],
                ],
                'rootline' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                    'options' => [
                        'compression' => true,
                    ],
                ],
            ],
        ],
        'devIPmask' => '',
        'displayErrors' => 0,
        'encryptionKey' => 'b4a5fb8dd80feb35857f95388966a0ee97edcd6c6dcb1e5b6dd6b09548322f5e753efc9857528c6d90d29cf03f452cf8',
        'exceptionalErrors' => 4096,
        'features' => [
            'frontend.cache.autoTagging' => true,
            'security.system.enforceAllowedFileExtensions' => true,
        ],
        'sitename' => 'Pixelcoda TYPO3 Test',
        'systemMaintainers' => [
            2,
            3,
        ],
    ],
];
