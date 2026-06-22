<?php
class template_T3GeneralInformationWidget_fluid_html_2c5ea8154699ccaa extends \TYPO3Fluid\Fluid\Core\Compiler\AbstractCompiledTemplate {
    public function getOriginalTemplatePath(): ?string {
        return '/var/www/html/vendor/typo3/cms-dashboard/Resources/Private/Templates/Widget/T3GeneralInformationWidget.fluid.html';
    }
    public function getLayoutName(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): ?string {
        
return 'Widget/Widget';
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
));
    }
    
    
    /**
 * section main
 */
public function section_49de89cb7af55ee5(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): mixed {
    $output0 = '';

$output0 .= '

    <div class="row">
        <div class="col-sm-8">
            ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array1 = [
'0' => $renderingContext->getVariableProvider()->getByPath('currentVersion'),
'1' => ' && ',
'2' => $renderingContext->getVariableProvider()->getByPath('copyrightYear'),
];

$expression2 = function($context) {return (TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]) && TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node2"]));};

$arguments10 = [
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression2(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array1)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output3 = '';

$output3 .= '
                <p>
                    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\RawViewHelper
$renderChildrenClosure5 = function() use ($renderingContext) {
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure7 = function() use ($renderingContext) {
return NULL;
};
$output9 = '';

$output9 .= '&copy; ';

$output9 .= $renderingContext->getVariableProvider()->getByPath('copyrightYear');

$array8 = [
'0' => $renderingContext->getVariableProvider()->getByPath('currentVersion'),
'1' => $output9,
'2' => 'Kasper Sk&aring;rh&oslash;j',
];

$arguments6 = [
'id' => NULL,
'default' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'key' => 'widgets.t3information.text',
'extensionName' => 'dashboard',
'arguments' => $array8,
];
return $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments6, $renderingContext, $renderChildrenClosure7);
};

$arguments4 = [
'value' => NULL,
];

$output3 .= isset($arguments4['value']) ? $arguments4['value'] : $renderChildrenClosure5();

$output3 .= '
                </p>
            ';
return $output3;
},
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments10, $renderingContext)
;

$output0 .= '
        </div>
        <div class="col-sm-4">
            <img src="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Uri\ResourceViewHelper
$renderChildrenClosure12 = function() use ($renderingContext) {
return NULL;
};

$arguments11 = [
'resource' => NULL,
'extensionName' => NULL,
'absolute' => false,
'useCacheBusting' => true,
'path' => 'EXT:core/Resources/Public/Images/typo3_light_dark.svg',
];
$renderChildrenClosure12 = ($arguments11['resource'] !== null) ? function() use ($arguments11) { return $arguments11['resource']; } : $renderChildrenClosure12;
$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Uri\ResourceViewHelper::class, $arguments11, $renderingContext, $renderChildrenClosure12)]);

$output0 .= '"
                class="widget-t3information-logo"
                alt="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure14 = function() use ($renderingContext) {
return NULL;
};

$arguments13 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'key' => 'widgets.t3information.logo',
'extensionName' => 'dashboard',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments13, $renderingContext, $renderChildrenClosure14)]);

$output0 .= '"/>
        </div>
    </div>

';

    return $output0;
}
/**
 * Main Render function
 */
public function render(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): mixed {
    $output15 = '';

$output15 .= '
';

$output15 .= '';

$output15 .= '
';

$output15 .= '';

$output15 .= '

';

    return $output15;
}

}

#