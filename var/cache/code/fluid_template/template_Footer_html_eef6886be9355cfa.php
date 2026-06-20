<?php
class template_Footer_html_eef6886be9355cfa extends \TYPO3Fluid\Fluid\Core\Compiler\AbstractCompiledTemplate {
    public function getOriginalTemplatePath(): ?string {
        return '/var/www/html/vendor/pixelcoda/content-gsap-animation/Resources/Private/TemplateOverrides/typo3/cms-backend/Partials/PageLayout/RecordDefault/Footer.html';
    }
    public function getLayoutName(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): ?string {
        return (string)'';
    }
    public function hasLayout(): bool {
        return false;
    }
    public function addCompiledNamespaces(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): void {
        $renderingContext->getViewHelperResolver()->setLocalNamespaces(array (
  'cga' => 
  array (
    0 => 'Pixelcoda\\ContentGsapAnimation\\ViewHelpers',
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
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper
$renderChildrenClosure2 = function() use ($renderingContext) {
return NULL;
};
// Rendering ViewHelper Pixelcoda\ContentGsapAnimation\ViewHelpers\ScalarFieldValueViewHelper
$renderChildrenClosure4 = function() use ($renderingContext) {
return NULL;
};

$arguments3 = [
'field' => $renderingContext->getVariableProvider()->getByPath('item.record.tx_content_gsap_animation_animation'),
];

$arguments1 = [
'name' => 'animationValue',
'value' => $renderingContext->getViewHelperInvoker()->invoke(Pixelcoda\ContentGsapAnimation\ViewHelpers\ScalarFieldValueViewHelper::class, $arguments3, $renderingContext, $renderChildrenClosure4),
];
$renderChildrenClosure2 = ($arguments1['value'] !== null) ? function() use ($arguments1) { return $arguments1['value']; } : $renderChildrenClosure2;
$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper::class, $arguments1, $renderingContext, $renderChildrenClosure2)]);

$output0 .= '
';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper
$renderChildrenClosure6 = function() use ($renderingContext) {
return NULL;
};
// Rendering ViewHelper Pixelcoda\ContentGsapAnimation\ViewHelpers\ScalarFieldValueViewHelper
$renderChildrenClosure8 = function() use ($renderingContext) {
return NULL;
};

$arguments7 = [
'field' => $renderingContext->getVariableProvider()->getByPath('item.footerInfo'),
];

$arguments5 = [
'name' => 'footerInfoValue',
'value' => $renderingContext->getViewHelperInvoker()->invoke(Pixelcoda\ContentGsapAnimation\ViewHelpers\ScalarFieldValueViewHelper::class, $arguments7, $renderingContext, $renderChildrenClosure8),
];
$renderChildrenClosure6 = ($arguments5['value'] !== null) ? function() use ($arguments5) { return $arguments5['value']; } : $renderChildrenClosure6;
$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper::class, $arguments5, $renderingContext, $renderChildrenClosure6)]);

$output0 .= '
';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array9 = [
'0' => $renderingContext->getVariableProvider()->getByPath('footerInfoValue'),
'1' => ' || ',
'2' => $renderingContext->getVariableProvider()->getByPath('animationValue'),
];

$expression10 = function($context) {return (TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]) || TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node2"]));};

$arguments24 = [
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression10(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array9)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output11 = '';

$output11 .= '
    <div class="t3-page-ce-footer">
        <div class="t3-page-ce-info">
            ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array12 = [
'0' => $renderingContext->getVariableProvider()->getByPath('animationValue'),
];

$expression13 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments17 = [
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression13(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array12)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output14 = '';

$output14 .= '
                <strong>';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure16 = function() use ($renderingContext) {
return NULL;
};

$arguments15 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'key' => 'LLL:EXT:content_gsap_animation/Resources/Private/Language/locallang_be.xlf:label.animation',
];

$output14 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments15, $renderingContext, $renderChildrenClosure16)]);

$output14 .= '</strong> ';

$output14 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('animationValue')]);

$output14 .= '<br>
            ';
return $output14;
},
];

$output11 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments17, $renderingContext)
;

$output11 .= '
            ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array18 = [
'0' => $renderingContext->getVariableProvider()->getByPath('footerInfoValue'),
];

$expression19 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments23 = [
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression19(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array18)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output20 = '';

$output20 .= '
                ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\RawViewHelper
$renderChildrenClosure22 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('footerInfoValue');
};

$arguments21 = [
'value' => NULL,
];

$output20 .= isset($arguments21['value']) ? $arguments21['value'] : $renderChildrenClosure22();

$output20 .= '
            ';
return $output20;
},
];

$output11 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments23, $renderingContext)
;

$output11 .= '
        </div>
    </div>
';
return $output11;
},
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments24, $renderingContext)
;

$output0 .= '
';

    return $output0;
}

}

#