<?php
class template_Files_fluid_html_a5b5c50369a7b8a0 extends \TYPO3Fluid\Fluid\Core\Compiler\AbstractCompiledTemplate {
    public function getOriginalTemplatePath(): ?string {
        return '/var/www/html/vendor/typo3/cms-filelist/Resources/Private/Templates/ElementBrowser/Files.fluid.html';
    }
    public function getLayoutName(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): ?string {
        
return 'ElementBrowserWithNavigation';
    }
    public function hasLayout(): bool {
        return true;
    }
    public function addCompiledNamespaces(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): void {
        $renderingContext->getViewHelperResolver()->setLocalNamespaces(array (
  'f' => 
  array (
    0 => 'TYPO3\\CMS\\Fluid\\ViewHelpers',
    1 => 'TYPO3\\CMS\\Fluid\\ViewHelpers',
  ),
  'core' => 
  array (
    0 => 'TYPO3\\CMS\\Core\\ViewHelpers',
  ),
));
    }
    
    
    /**
 * section Navigation
 */
public function section_cec5e0e618483f50(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): mixed {
    $output0 = '';

$output0 .= '
    <typo3-backend-component-filestorage-browser
        active-folder="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Format\HtmlentitiesViewHelper
$renderChildrenClosure2 = function() use ($renderingContext) {
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array3 = [
'0' => $renderingContext->getVariableProvider()->getByPath('selectedFolder'),
];

$expression4 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments5 = [
'__then' => function() use ($renderingContext) {

return $renderingContext->getVariableProvider()->getByPath('selectedFolder.combinedIdentifier');
},
'__else' => function() use ($renderingContext) {

return '';
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression4(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array3)),
    $renderingContext
),
];
return $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments5, $renderingContext)
;
};

$arguments1 = [
'value' => NULL,
'keepQuotes' => false,
'encoding' => NULL,
'doubleEncode' => true,
];
$renderChildrenClosure2 = ($arguments1['value'] !== null) ? function() use ($arguments1) { return $arguments1['value']; } : $renderChildrenClosure2;
$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Format\HtmlentitiesViewHelper::class, $arguments1, $renderingContext, $renderChildrenClosure2);

$output0 .= '"
    >
    </typo3-backend-component-filestorage-browser>
';

    return $output0;
}
/**
 * section Content
 */
public function section_26298499e77d870c(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): mixed {
    $output6 = '';

$output6 .= '
    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array7 = [
'0' => $renderingContext->getVariableProvider()->getByPath('selectedFolder'),
];

$expression8 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments12 = [
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression8(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array7)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output9 = '';

$output9 .= '
        <h4 class="text-truncate mb-4">
            ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconForResourceViewHelper
$renderChildrenClosure11 = function() use ($renderingContext) {
return NULL;
};

$arguments10 = [
'size' => \TYPO3\CMS\Core\Imaging\IconSize::SMALL,
'overlay' => NULL,
'options' => [],
'alternativeMarkupIdentifier' => NULL,
'resource' => $renderingContext->getVariableProvider()->getByPath('selectedFolder'),
];

$output9 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconForResourceViewHelper::class, $arguments10, $renderingContext, $renderChildrenClosure11);

$output9 .= '
            ';

$output9 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('selectedFolder.storage.name')]);

$output9 .= ': ';

$output9 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('selectedFolder.identifier')]);

$output9 .= '
        </h4>
    ';
return $output9;
},
];

$output6 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments12, $renderingContext)
;

$output6 .= '
    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\FlashMessagesViewHelper
$renderChildrenClosure14 = function() use ($renderingContext) {
return NULL;
};

$arguments13 = [
'as' => NULL,
'queueIdentifier' => 'core.template.flashMessages',
];

$output6 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\FlashMessagesViewHelper::class, $arguments13, $renderingContext, $renderChildrenClosure14);

$output6 .= '
    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\RawViewHelper
$renderChildrenClosure16 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('content');
};

$arguments15 = [
'value' => NULL,
];

$output6 .= isset($arguments15['value']) ? $arguments15['value'] : $renderChildrenClosure16();

$output6 .= '
';

    return $output6;
}
/**
 * Main Render function
 */
public function render(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): mixed {
    $output17 = '';

$output17 .= '





';

$output17 .= '';

$output17 .= '

';

$output17 .= '';

$output17 .= '

';

$output17 .= '';

$output17 .= '


';

    return $output17;
}

}

#