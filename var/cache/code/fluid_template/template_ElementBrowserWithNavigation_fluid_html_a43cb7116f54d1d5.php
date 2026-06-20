<?php
class template_ElementBrowserWithNavigation_fluid_html_a43cb7116f54d1d5 extends \TYPO3Fluid\Fluid\Core\Compiler\AbstractCompiledTemplate {
    public function getOriginalTemplatePath(): ?string {
        return '/var/www/html/vendor/typo3/cms-backend/Resources/Private/Layouts/ElementBrowserWithNavigation.fluid.html';
    }
    public function getLayoutName(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): ?string {
        return (string)'';
    }
    public function hasLayout(): bool {
        return false;
    }
    public function addCompiledNamespaces(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): void {
        $renderingContext->getViewHelperResolver()->setLocalNamespaces(array (
  'f' => 
  array (
    0 => 'TYPO3\\CMS\\Fluid\\ViewHelpers',
  ),
));
    }
    
    
    /**
 * Main Render function
 */
public function render(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): mixed {
    $output0 = '';

$output0 .= '




';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper
$renderChildrenClosure2 = function() use ($renderingContext) {
return NULL;
};

$arguments1 = [
'identifier' => '@typo3/backend/viewport/content-navigation.js',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper::class, $arguments1, $renderingContext, $renderChildrenClosure2)]);

$output0 .= '

';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array17 = [
'0' => $renderingContext->getVariableProvider()->getByPath('contentOnly'),
];

$expression18 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments19 = [
'__then' => function() use ($renderingContext) {
$output3 = '';

$output3 .= '
        ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper
$renderChildrenClosure5 = function() use ($renderingContext) {
return NULL;
};

$arguments4 = [
'partial' => NULL,
'delegate' => NULL,
'arguments' => [],
'optional' => false,
'default' => NULL,
'contentAs' => NULL,
'debug' => true,
'section' => 'Content',
];

$output3 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper::class, $arguments4, $renderingContext, $renderChildrenClosure5);

$output3 .= '
    ';
return $output3;
},
'__else' => function() use ($renderingContext) {
$output6 = '';

$output6 .= '
        <div class="element-browser">
            <typo3-backend-content-navigation
                class="element-browser-main"
                identifier="elementbrowser"
                navigation-title="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure8 = function() use ($renderingContext) {
return NULL;
};

$arguments7 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'key' => 'viewport.navigation',
'domain' => 'backend.messages',
];

$output6 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments7, $renderingContext, $renderChildrenClosure8)]);

$output6 .= '"
                navigation-label-collapse="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure10 = function() use ($renderingContext) {
return NULL;
};

$arguments9 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'key' => 'viewport.navigation.hide',
'domain' => 'backend.messages',
];

$output6 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments9, $renderingContext, $renderChildrenClosure10)]);

$output6 .= '"
                navigation-label-expand="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure12 = function() use ($renderingContext) {
return NULL;
};

$arguments11 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'key' => 'viewport.navigation.show',
'domain' => 'backend.messages',
];

$output6 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments11, $renderingContext, $renderChildrenClosure12)]);

$output6 .= '"
                navigation-initial-width="';

$output6 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('initialNavigationWidth')]);

$output6 .= '"
            >
                <div slot="navigation" class="element-browser-main-sidebar">
                    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper
$renderChildrenClosure14 = function() use ($renderingContext) {
return NULL;
};

$arguments13 = [
'partial' => NULL,
'delegate' => NULL,
'arguments' => [],
'optional' => false,
'default' => NULL,
'contentAs' => NULL,
'debug' => true,
'section' => 'Navigation',
];

$output6 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper::class, $arguments13, $renderingContext, $renderChildrenClosure14);

$output6 .= '
                </div>
                <div slot="content" class="element-browser-main-content">
                    <div class="element-browser-navigation-toggle">
                        <typo3-backend-content-navigation-toggle
                            class="btn btn-sm btn-icon btn-default btn-borderless"
                            action="expand"
                            hidden
                        >
                        </typo3-backend-content-navigation-toggle>
                    </div>
                    <div class="element-browser-body">
                        ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper
$renderChildrenClosure16 = function() use ($renderingContext) {
return NULL;
};

$arguments15 = [
'partial' => NULL,
'delegate' => NULL,
'arguments' => [],
'optional' => false,
'default' => NULL,
'contentAs' => NULL,
'debug' => true,
'section' => 'Content',
];

$output6 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper::class, $arguments15, $renderingContext, $renderChildrenClosure16);

$output6 .= '
                    </div>
                </div>
            </typo3-backend-content-navigation>
        </div>
    ';
return $output6;
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression18(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array17)),
    $renderingContext
),
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments19, $renderingContext)
;

$output0 .= '


';

    return $output0;
}

}

#