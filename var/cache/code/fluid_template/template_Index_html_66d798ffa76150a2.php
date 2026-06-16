<?php
class template_Index_html_66d798ffa76150a2 extends \TYPO3Fluid\Fluid\Core\Compiler\AbstractCompiledTemplate {
    public function getOriginalTemplatePath(): ?string {
        return '/var/www/html/vendor/pixelcoda/typo3-search/Resources/Private/Templates/Backend/Index.html';
    }
    public function getLayoutName(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): ?string {
        
return 'Module';
    }
    public function hasLayout(): bool {
        return true;
    }
    public function addCompiledNamespaces(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): void {
        $renderingContext->getViewHelperResolver()->setLocalNamespaces(array (
  'f' => 
  array (
    0 => 'TYPO3\\CMS\\Fluid\\ViewHelpers',
  ),
  'core' => 
  array (
    0 => 'TYPO3\\CMS\\Core\\ViewHelpers',
  ),
));
    }
    
    
    /**
 * section Content
 */
public function section_26298499e77d870c(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): mixed {
    $output0 = '';

$output0 .= '
    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Asset\CssViewHelper
$renderChildrenClosure2 = function() use ($renderingContext) {
return NULL;
};

$arguments1 = [
'additionalAttributes' => NULL,
'data' => NULL,
'aria' => NULL,
'disabled' => NULL,
'csp' => NULL,
'useNonce' => NULL,
'priority' => false,
'inline' => false,
'identifier' => 'pixelcoda-search-backend',
'href' => 'EXT:pixelcoda_search/Resources/Public/Css/backend.css',
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Asset\CssViewHelper::class, $arguments1, $renderingContext, $renderChildrenClosure2);

$output0 .= '

    <div class="pc-search-admin">
        <header class="pc-search-hero">
            <div class="pc-search-hero__mark" aria-hidden="true">
                ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure4 = function() use ($renderingContext) {
return NULL;
};

$arguments3 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'pixelcoda-search',
'size' => 'large',
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments3, $renderingContext, $renderChildrenClosure4);

$output0 .= '
            </div>
            <div class="pc-search-hero__copy">
                <span class="pc-search-eyebrow">Pixelcoda TYPO3 Search</span>
                <h1>Search Administration</h1>
                <p>Rendering, API-Verbindung und Index-Konfiguration zentral verwalten.</p>
            </div>
            <div class="pc-search-hero__state">
                <span class="pc-search-state-dot"></span>
                TYPO3 12–14 kompatibel
            </div>
        </header>

        <div class="pc-search-layout">
            <main class="pc-search-main">
                <section class="pc-search-panel" aria-labelledby="rendering-title">
                    <div class="pc-search-panel__header">
                        <div>
                            <span class="pc-search-section-icon" aria-hidden="true">
                                ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure6 = function() use ($renderingContext) {
return NULL;
};

$arguments5 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-refresh',
'size' => 'small',
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments5, $renderingContext, $renderChildrenClosure6);

$output0 .= '
                            </span>
                            <div>
                                <h2 id="rendering-title">Rendering-Modus</h2>
                                <p>Ausgabeformat für klassische und Headless-Projekte festlegen.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pc-search-mode-summary">
                        <div class="pc-search-mode-status">
                            <span class="pc-search-label">TYPO3-Modus</span>
                            ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array7 = [
'0' => $renderingContext->getVariableProvider()->getByPath('currentMode'),
'1' => ' == \'headless\'',
];

$expression8 = function($context) {return (TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]) == 'headless');};

$arguments9 = [
'__then' => function() use ($renderingContext) {
return '<strong>Headless <small>JSON API</small></strong>';
},
'__else' => function() use ($renderingContext) {
return '<strong>Standard <small>HTML Templates</small></strong>';
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression8(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array7)),
    $renderingContext
),
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments9, $renderingContext)
;

$output0 .= '
                        </div>
                        <div class="pc-search-mode-status">
                            <span class="pc-search-label">Plugin-Modus</span>
                            ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array10 = [
'0' => $renderingContext->getVariableProvider()->getByPath('pluginMode'),
'1' => ' == \'headless\'',
];

$expression11 = function($context) {return (TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]) == 'headless');};

$arguments12 = [
'__then' => function() use ($renderingContext) {
return '<strong>Headless <small>API-first</small></strong>';
},
'__else' => function() use ($renderingContext) {
return '<strong>Classic <small>Fluid Rendering</small></strong>';
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression11(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array10)),
    $renderingContext
),
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments12, $renderingContext)
;

$output0 .= '
                        </div>
                    </div>

                    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array19 = [
'0' => $renderingContext->getVariableProvider()->getByPath('modesSynchronized'),
];

$expression20 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments21 = [
'__then' => function() use ($renderingContext) {
$output13 = '';

$output13 .= '
                            <div class="pc-search-notice pc-search-notice--success">
                                ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure15 = function() use ($renderingContext) {
return NULL;
};

$arguments14 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-check',
'size' => 'small',
];

$output13 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments14, $renderingContext, $renderChildrenClosure15);

$output13 .= '
                                <span><strong>Modi synchronisiert.</strong> TYPO3 und Plugin verwenden dieselbe Ausgabe.</span>
                            </div>
                        ';
return $output13;
},
'__else' => function() use ($renderingContext) {
$output16 = '';

$output16 .= '
                            <div class="pc-search-notice pc-search-notice--warning">
                                ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure18 = function() use ($renderingContext) {
return NULL;
};

$arguments17 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-close',
'size' => 'small',
];

$output16 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments17, $renderingContext, $renderChildrenClosure18);

$output16 .= '
                                <span><strong>Modi nicht synchronisiert.</strong> Bitte eine gemeinsame Ausgabe auswählen.</span>
                            </div>
                        ';
return $output16;
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression20(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array19)),
    $renderingContext
),
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments21, $renderingContext)
;

$output0 .= '

                    <form method="post" action="';

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('moduleUrls.switchMode')]);

$output0 .= '" class="pc-search-mode-form">
                        <div class="form-group">
                            <label class="form-label" for="mode">Neuen Modus auswählen</label>
                            <select name="mode" id="mode" class="form-select">
                                ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper
$renderChildrenClosure23 = function() use ($renderingContext) {
$output24 = '';

$output24 .= '
                                    <option value="';

$output24 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('value')]);

$output24 .= '" ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array25 = [
'0' => $renderingContext->getVariableProvider()->getByPath('currentMode'),
'1' => ' == ',
'2' => $renderingContext->getVariableProvider()->getByPath('value'),
];

$expression26 = function($context) {return (TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]) == TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node2"]));};

$arguments27 = [
'__then' => function() use ($renderingContext) {

return 'selected';
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression26(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array25)),
    $renderingContext
),
];

$output24 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments27, $renderingContext)
;

$output24 .= '>';

$output24 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('label')]);

$output24 .= '</option>
                                ';
return $output24;
};

$arguments22 = [
'reverse' => false,
'iteration' => NULL,
'each' => $renderingContext->getVariableProvider()->getByPath('availableModes'),
'as' => 'label',
'key' => 'value',
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper::class, $arguments22, $renderingContext, $renderChildrenClosure23);

$output0 .= '
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure29 = function() use ($renderingContext) {
return NULL;
};

$arguments28 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-refresh',
'size' => 'small',
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments28, $renderingContext, $renderChildrenClosure29);

$output0 .= '
                            Modus anwenden
                        </button>
                    </form>

                    <div class="pc-search-mode-grid">
                        <article class="pc-search-mode-card pc-search-mode-card--headless">
                            <span class="pc-search-mode-card__icon" aria-hidden="true">
                                ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure31 = function() use ($renderingContext) {
return NULL;
};

$arguments30 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'pixelcoda-search',
'size' => 'large',
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments30, $renderingContext, $renderChildrenClosure31);

$output0 .= '
                            </span>
                            <h3>Headless</h3>
                            <p>Strukturierte Daten für moderne Frontends und Apps.</p>
                            <ul>
                                <li>JSON API Output</li>
                                <li>React, Vue und Next.js</li>
                                <li>Unabhängiges Frontend</li>
                            </ul>
                        </article>
                        <article class="pc-search-mode-card">
                            <span class="pc-search-mode-card__icon" aria-hidden="true">
                                ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure33 = function() use ($renderingContext) {
return NULL;
};

$arguments32 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'content-text',
'size' => 'large',
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments32, $renderingContext, $renderChildrenClosure33);

$output0 .= '
                            </span>
                            <h3>Standard</h3>
                            <p>Direktes TYPO3-Rendering mit Fluid Templates.</p>
                            <ul>
                                <li>HTML Template Output</li>
                                <li>SEO-freundliche Ausgabe</li>
                                <li>Einfache Integration</li>
                            </ul>
                        </article>
                    </div>
                </section>

                <section class="pc-search-panel" aria-labelledby="configuration-title">
                    <div class="pc-search-panel__header">
                        <div>
                            <span class="pc-search-section-icon" aria-hidden="true">
                                ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure35 = function() use ($renderingContext) {
return NULL;
};

$arguments34 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-cog',
'size' => 'small',
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments34, $renderingContext, $renderChildrenClosure35);

$output0 .= '
                            </span>
                            <div>
                                <h2 id="configuration-title">Aktuelle Konfiguration</h2>
                                <p>Aktive Verbindung und Indexierungsfunktionen im Überblick.</p>
                            </div>
                        </div>
                    </div>
                    <dl class="pc-search-config-grid">
                        <div><dt>API URL</dt><dd><code>';

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('config.api_url')]);

$output0 .= '</code></dd></div>
                        <div><dt>Project ID</dt><dd><code>';

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('config.project_id')]);

$output0 .= '</code></dd></div>
                        <div><dt>Default Mode</dt><dd><code>';

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('config.default_mode')]);

$output0 .= '</code></dd></div>
                        <div><dt>Batch Size</dt><dd><code>';

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('config.batch_size')]);

$output0 .= '</code></dd></div>
                        <div><dt>Auto Index</dt><dd>';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array36 = [
'0' => $renderingContext->getVariableProvider()->getByPath('config.enable_auto_index'),
];

$expression37 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments38 = [
'__then' => function() use ($renderingContext) {
return 'Aktiv';
},
'__else' => function() use ($renderingContext) {
return 'Inaktiv';
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression37(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array36)),
    $renderingContext
),
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments38, $renderingContext)
;

$output0 .= '</dd></div>
                        <div><dt>Vector Search</dt><dd>';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array39 = [
'0' => $renderingContext->getVariableProvider()->getByPath('config.enable_vector_search'),
];

$expression40 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments41 = [
'__then' => function() use ($renderingContext) {
return 'Aktiv';
},
'__else' => function() use ($renderingContext) {
return 'Inaktiv';
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression40(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array39)),
    $renderingContext
),
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments41, $renderingContext)
;

$output0 .= '</dd></div>
                        <div><dt>Metrics</dt><dd>';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array42 = [
'0' => $renderingContext->getVariableProvider()->getByPath('config.enable_metrics'),
];

$expression43 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments44 = [
'__then' => function() use ($renderingContext) {
return 'Aktiv';
},
'__else' => function() use ($renderingContext) {
return 'Inaktiv';
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression43(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array42)),
    $renderingContext
),
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments44, $renderingContext)
;

$output0 .= '</dd></div>
                        <div><dt>Debug Mode</dt><dd>';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array45 = [
'0' => $renderingContext->getVariableProvider()->getByPath('config.debug_mode'),
];

$expression46 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments47 = [
'__then' => function() use ($renderingContext) {
return 'Aktiv';
},
'__else' => function() use ($renderingContext) {
return 'Inaktiv';
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression46(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array45)),
    $renderingContext
),
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments47, $renderingContext)
;

$output0 .= '</dd></div>
                    </dl>
                </section>

                <section class="pc-search-panel" aria-labelledby="help-title">
                    <div class="pc-search-panel__header">
                        <div>
                            <span class="pc-search-section-icon" aria-hidden="true">
                                ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure49 = function() use ($renderingContext) {
return NULL;
};

$arguments48 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-info',
'size' => 'small',
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments48, $renderingContext, $renderChildrenClosure49);

$output0 .= '
                            </span>
                            <div>
                                <h2 id="help-title">Schnellstart</h2>
                                <p>Die wichtigsten Schritte für eine produktive Suche.</p>
                            </div>
                        </div>
                    </div>
                    <ol class="pc-search-steps">
                        <li><span>1</span><div><strong>Modus auswählen</strong><p>Classic oder Headless passend zum Projekt aktivieren.</p></div></li>
                        <li><span>2</span><div><strong>API testen</strong><p>Verbindung prüfen und veröffentlichte Inhalte indexieren.</p></div></li>
                        <li><span>3</span><div><strong>Search-Element einfügen</strong><p>Das barrierefreie Inhaltselement auf der gewünschten Seite platzieren.</p></div></li>
                    </ol>
                    <div class="pc-search-code-row">
                        <code>typo3 pixelcoda:search:reindex</code>
                        <code>typo3 pixelcoda:search:index --dry-run</code>
                    </div>
                </section>
            </main>

            <aside class="pc-search-sidebar" aria-label="Systemstatus und Schnellaktionen">
                <section class="pc-search-panel pc-search-panel--sticky">
                    <div class="pc-search-panel__header">
                        <div>
                            <span class="pc-search-section-icon" aria-hidden="true">
                                ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure51 = function() use ($renderingContext) {
return NULL;
};

$arguments50 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-info',
'size' => 'small',
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments50, $renderingContext, $renderChildrenClosure51);

$output0 .= '
                            </span>
                            <div>
                                <h2>Systemstatus</h2>
                                <p>Bereitschaft der Suchdienste.</p>
                            </div>
                        </div>
                    </div>
                    <ul class="pc-search-status-list">
                        <li>
                            <span>API-Konfiguration</span>
                            ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array52 = [
'0' => $renderingContext->getVariableProvider()->getByPath('systemStatus.api_configured'),
];

$expression53 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments54 = [
'__then' => function() use ($renderingContext) {
return '<strong class="pc-search-badge pc-search-badge--success">Bereit</strong>';
},
'__else' => function() use ($renderingContext) {
return '<strong class="pc-search-badge pc-search-badge--danger">Fehlt</strong>';
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression53(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array52)),
    $renderingContext
),
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments54, $renderingContext)
;

$output0 .= '
                        </li>
                        <li>
                            <span>Headless Extension</span>
                            ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array55 = [
'0' => $renderingContext->getVariableProvider()->getByPath('systemStatus.headless_extension'),
];

$expression56 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments57 = [
'__then' => function() use ($renderingContext) {
return '<strong class="pc-search-badge pc-search-badge--success">Aktiv</strong>';
},
'__else' => function() use ($renderingContext) {
return '<strong class="pc-search-badge pc-search-badge--neutral">Optional</strong>';
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression56(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array55)),
    $renderingContext
),
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments57, $renderingContext)
;

$output0 .= '
                        </li>
                        <li>
                            <span>Cache</span>
                            ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array58 = [
'0' => $renderingContext->getVariableProvider()->getByPath('systemStatus.cache_status'),
'1' => ' == \'empty\'',
];

$expression59 = function($context) {return (TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]) == 'empty');};

$arguments60 = [
'__then' => function() use ($renderingContext) {
return '<strong class="pc-search-badge pc-search-badge--success">Leer</strong>';
},
'__else' => function() use ($renderingContext) {
return '<strong class="pc-search-badge pc-search-badge--info">Gefüllt</strong>';
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression59(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array58)),
    $renderingContext
),
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments60, $renderingContext)
;

$output0 .= '
                        </li>
                    </ul>
                    <div class="pc-search-actions">
                        <a href="';

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('moduleUrls.testConnection')]);

$output0 .= '" class="btn btn-primary">
                            ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure62 = function() use ($renderingContext) {
return NULL;
};

$arguments61 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-link',
'size' => 'small',
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments61, $renderingContext, $renderChildrenClosure62);

$output0 .= '
                            API-Verbindung testen
                        </a>
                        <a href="';

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('moduleUrls.clearCache')]);

$output0 .= '" class="btn btn-default">
                            ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure64 = function() use ($renderingContext) {
return NULL;
};

$arguments63 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-system-cache-clear-impact-low',
'size' => 'small',
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments63, $renderingContext, $renderChildrenClosure64);

$output0 .= '
                            Cache leeren
                        </a>
                    </div>
                </section>
            </aside>
        </div>
    </div>
';

    return $output0;
}
/**
 * Main Render function
 */
public function render(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): mixed {
    $output65 = '';

$output65 .= '



';

$output65 .= '';

$output65 .= '

';

$output65 .= '';

$output65 .= '

';

    return $output65;
}

}

#