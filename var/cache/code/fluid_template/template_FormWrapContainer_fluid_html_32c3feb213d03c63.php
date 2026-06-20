<?php
class template_FormWrapContainer_fluid_html_32c3feb213d03c63 extends \TYPO3Fluid\Fluid\Core\Compiler\AbstractCompiledTemplate {
    public function getOriginalTemplatePath(): ?string {
        return '/var/www/html/vendor/typo3/cms-backend/Resources/Private/Templates/Form/FormWrapContainer.fluid.html';
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
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array1 = [
'0' => $renderingContext->getVariableProvider()->getByPath('recordDescription'),
];

$expression2 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments11 = [
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression2(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array1)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output3 = '';

$output3 .= '
    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Be\InfoboxViewHelper
$renderChildrenClosure5 = function() use ($renderingContext) {
$output8 = '';

$output8 .= '
        ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\Nl2brViewHelper
$renderChildrenClosure10 = function() use ($renderingContext) {
return call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('recordDescription')]);
};

$arguments9 = [
'value' => NULL,
];
$renderChildrenClosure10 = ($arguments9['value'] !== null) ? function() use ($arguments9) { return $arguments9['value']; } : $renderChildrenClosure10;
$output8 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\Format\Nl2brViewHelper::class, $arguments9, $renderingContext, $renderChildrenClosure10);

$output8 .= '
    ';
return $output8;
};
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure7 = function() use ($renderingContext) {
return NULL;
};

$arguments6 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'key' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.recordInformation',
];

$arguments4 = [
'message' => NULL,
'state' => \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::NOTICE,
'iconName' => NULL,
'disableIcon' => false,
'title' => $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments6, $renderingContext, $renderChildrenClosure7),
];
$renderChildrenClosure5 = ($arguments4['message'] !== null) ? function() use ($arguments4) { return $arguments4['message']; } : $renderChildrenClosure5;
$output3 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Be\InfoboxViewHelper::class, $arguments4, $renderingContext, $renderChildrenClosure5);

$output3 .= '
';
return $output3;
},
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments11, $renderingContext)
;

$output0 .= '
';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array12 = [
'0' => $renderingContext->getVariableProvider()->getByPath('recordReadonly'),
];

$expression13 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments22 = [
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression13(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array12)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output14 = '';

$output14 .= '
    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Be\InfoboxViewHelper
$renderChildrenClosure16 = function() use ($renderingContext) {
$output19 = '';

$output19 .= '
        ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure21 = function() use ($renderingContext) {
return NULL;
};

$arguments20 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'key' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.recordReadonly',
];

$output19 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments20, $renderingContext, $renderChildrenClosure21)]);

$output19 .= '
    ';
return $output19;
};
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure18 = function() use ($renderingContext) {
return NULL;
};

$arguments17 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'key' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.recordInformation',
];

$arguments15 = [
'message' => NULL,
'state' => \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::NOTICE,
'iconName' => NULL,
'disableIcon' => false,
'title' => $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments17, $renderingContext, $renderChildrenClosure18),
];
$renderChildrenClosure16 = ($arguments15['message'] !== null) ? function() use ($arguments15) { return $arguments15['message']; } : $renderChildrenClosure16;
$output14 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Be\InfoboxViewHelper::class, $arguments15, $renderingContext, $renderChildrenClosure16);

$output14 .= '
';
return $output14;
},
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments22, $renderingContext)
;

$output0 .= '

<div class="typo3-TCEforms';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array23 = [
'0' => $renderingContext->getVariableProvider()->getByPath('isNewRecord'),
];

$expression24 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments25 = [
'__then' => function() use ($renderingContext) {

return ' is-new';
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression24(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array23)),
    $renderingContext
),
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments25, $renderingContext)
;

$output0 .= '">
    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\RawViewHelper
$renderChildrenClosure27 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('fieldInformationHtml');
};

$arguments26 = [
'value' => NULL,
];

$output0 .= isset($arguments26['value']) ? $arguments26['value'] : $renderChildrenClosure27();

$output0 .= '
    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\RawViewHelper
$renderChildrenClosure29 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('fieldWizardHtml');
};

$arguments28 = [
'value' => NULL,
];

$output0 .= isset($arguments28['value']) ? $arguments28['value'] : $renderChildrenClosure29();

$output0 .= '
    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\RawViewHelper
$renderChildrenClosure31 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('childHtml');
};

$arguments30 = [
'value' => NULL,
];

$output0 .= isset($arguments30['value']) ? $arguments30['value'] : $renderChildrenClosure31();

$output0 .= '
</div>


';

    return $output0;
}

}

#