<?php
class template_EditDocument_fluid_html_2357e2da76b95a93 extends \TYPO3Fluid\Fluid\Core\Compiler\AbstractCompiledTemplate {
    public function getOriginalTemplatePath(): ?string {
        return '/var/www/html/vendor/typo3/cms-backend/Resources/Private/Templates/Form/EditDocument.fluid.html';
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
));
    }
    
    
    /**
 * section Content
 */
public function section_26298499e77d870c(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): mixed {
    $output0 = '';

$output0 .= '

    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper
$renderChildrenClosure2 = function() use ($renderingContext) {
return NULL;
};

$arguments1 = [
'identifier' => '@typo3/backend/context-menu.js',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper::class, $arguments1, $renderingContext, $renderChildrenClosure2)]);

$output0 .= '
    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper
$renderChildrenClosure4 = function() use ($renderingContext) {
return NULL;
};

$arguments3 = [
'identifier' => '@typo3/backend/localization.js',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper::class, $arguments3, $renderingContext, $renderChildrenClosure4)]);

$output0 .= '

    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array5 = [
'0' => $renderingContext->getVariableProvider()->getByPath('moduleContext'),
'1' => ' != \'\' && ',
'2' => $renderingContext->getVariableProvider()->getByPath('moduleContextId'),
'3' => ' != \'\'',
];

$expression6 = function($context) {return ((TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]) != '') && (TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node2"]) != ''));};

$arguments17 = [
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression6(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array5)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output7 = '';

$output7 .= '
        ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper
$renderChildrenClosure9 = function() use ($renderingContext) {
return NULL;
};

$arguments8 = [
'identifier' => '@typo3/backend/element/immediate-action-element.js',
];

$output7 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper::class, $arguments8, $renderingContext, $renderChildrenClosure9)]);

$output7 .= '

        ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper
$renderChildrenClosure11 = function() use ($renderingContext) {
return NULL;
};

$array12 = [
'0' => $renderingContext->getVariableProvider()->getByPath('moduleContext'),
'1' => $renderingContext->getVariableProvider()->getByPath('moduleContextId'),
];

$arguments10 = [
'name' => 'immediateActionArgs',
'value' => $array12,
];
$renderChildrenClosure11 = ($arguments10['value'] !== null) ? function() use ($arguments10) { return $arguments10['value']; } : $renderChildrenClosure11;
$output7 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper::class, $arguments10, $renderingContext, $renderChildrenClosure11)]);

$output7 .= '
        <typo3-immediate-action
            action="TYPO3.Backend.Storage.ModuleStateStorage.update"
            args="';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$renderChildrenClosure14 = function() use ($renderingContext) {
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\JsonViewHelper
$renderChildrenClosure16 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('immediateActionArgs');
};

$arguments15 = [
'value' => NULL,
'forceObject' => false,
];
$renderChildrenClosure16 = ($arguments15['value'] !== null) ? function() use ($arguments15) { return $arguments15['value']; } : $renderChildrenClosure16;return $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\Format\JsonViewHelper::class, $arguments15, $renderingContext, $renderChildrenClosure16);
};

$arguments13 = [
'value' => NULL,
'keepQuotes' => false,
'encoding' => 'UTF-8',
'doubleEncode' => true,
];

$output7 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper::class, $arguments13, $renderingContext, $renderChildrenClosure14);

$output7 .= '"
        ></typo3-immediate-action>
    ';
return $output7;
},
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments17, $renderingContext)
;

$output0 .= '

    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\RawViewHelper
$renderChildrenClosure19 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('bodyHtml');
};

$arguments18 = [
'value' => NULL,
];

$output0 .= isset($arguments18['value']) ? $arguments18['value'] : $renderChildrenClosure19();

$output0 .= '

';

    return $output0;
}
/**
 * Main Render function
 */
public function render(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): mixed {
    $output20 = '';

$output20 .= '




';

$output20 .= '';

$output20 .= '

';

$output20 .= '';

$output20 .= '


';

    return $output20;
}

}

#