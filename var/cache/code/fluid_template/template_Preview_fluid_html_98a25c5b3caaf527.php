<?php
class template_Preview_fluid_html_98a25c5b3caaf527 extends \TYPO3Fluid\Fluid\Core\Compiler\AbstractCompiledTemplate {
    public function getOriginalTemplatePath(): ?string {
        return '/var/www/html/vendor/typo3/cms-backend/Resources/Private/Partials/PageLayout/RecordDefault/Preview.fluid.html';
    }
    public function getLayoutName(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): ?string {
        return (string)'';
    }
    public function hasLayout(): bool {
        return false;
    }
    public function addCompiledNamespaces(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): void {
        $renderingContext->getViewHelperResolver()->setLocalNamespaces(array (
));
    }
    
    
    /**
 * Main Render function
 */
public function render(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): mixed {
    $output0 = '';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper
$renderChildrenClosure2 = function() use ($renderingContext) {
$output6 = '';

$output6 .= '
    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\RawViewHelper
$renderChildrenClosure8 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('item.preview');
};

$arguments7 = [
'value' => NULL,
];

$output6 .= isset($arguments7['value']) ? $arguments7['value'] : $renderChildrenClosure8();

$output6 .= '
';
return $output6;
};
$output3 = '';

$output3 .= 'PageLayout/Record/';

$output3 .= $renderingContext->getVariableProvider()->getByPath('item.record.CType');

$output3 .= '/Preview';

$array4 = [
'0' => 1,
];

$expression5 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments1 = [
'section' => NULL,
'delegate' => NULL,
'default' => NULL,
'contentAs' => NULL,
'debug' => true,
'partial' => $output3,
'arguments' => $renderingContext->getVariableProvider()->getAll(),
'optional' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression5(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array4)),
    $renderingContext
),
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper::class, $arguments1, $renderingContext, $renderChildrenClosure2);

$output0 .= '
';

    return $output0;
}

}

#