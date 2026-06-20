<?php
class template_PageModule_fluid_html_5e5204e5f6a5ad3f extends \TYPO3Fluid\Fluid\Core\Compiler\AbstractCompiledTemplate {
    public function getOriginalTemplatePath(): ?string {
        return '/var/www/html/vendor/typo3/cms-backend/Resources/Private/Templates/PageLayout/PageModule.fluid.html';
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
 * section Before
 */
public function section_ed3696630fa71e53(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): mixed {
    $output0 = '';

$output0 .= '
    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper
$renderChildrenClosure2 = function() use ($renderingContext) {
return NULL;
};

$arguments1 = [
'identifier' => '@typo3/backend/clear-cache.js',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper::class, $arguments1, $renderingContext, $renderChildrenClosure2)]);

$output0 .= '
    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper
$renderChildrenClosure4 = function() use ($renderingContext) {
return NULL;
};

$arguments3 = [
'identifier' => '@typo3/backend/new-content-element-wizard-button.js',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper::class, $arguments3, $renderingContext, $renderChildrenClosure4)]);

$output0 .= '
    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper
$renderChildrenClosure6 = function() use ($renderingContext) {
return NULL;
};

$arguments5 = [
'identifier' => '@typo3/backend/context-menu.js',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper::class, $arguments5, $renderingContext, $renderChildrenClosure6)]);

$output0 .= '
    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper
$renderChildrenClosure8 = function() use ($renderingContext) {
return NULL;
};

$arguments7 = [
'identifier' => '@typo3/backend/localization.js',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper::class, $arguments7, $renderingContext, $renderChildrenClosure8)]);

$output0 .= '
    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper
$renderChildrenClosure10 = function() use ($renderingContext) {
return NULL;
};

$arguments9 = [
'identifier' => '@typo3/backend/layout-module/drag-drop.js',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper::class, $arguments9, $renderingContext, $renderChildrenClosure10)]);

$output0 .= '
    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper
$renderChildrenClosure12 = function() use ($renderingContext) {
return NULL;
};

$arguments11 = [
'identifier' => '@typo3/backend/layout-module/velocity-scroll.js',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper::class, $arguments11, $renderingContext, $renderChildrenClosure12)]);

$output0 .= '
    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper
$renderChildrenClosure14 = function() use ($renderingContext) {
return NULL;
};

$arguments13 = [
'identifier' => '@typo3/backend/layout-module/page-layout.js',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper::class, $arguments13, $renderingContext, $renderChildrenClosure14)]);

$output0 .= '
    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper
$renderChildrenClosure16 = function() use ($renderingContext) {
return NULL;
};

$arguments15 = [
'identifier' => '@typo3/backend/modal.js',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper::class, $arguments15, $renderingContext, $renderChildrenClosure16)]);

$output0 .= '
    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper
$renderChildrenClosure18 = function() use ($renderingContext) {
return NULL;
};

$arguments17 = [
'identifier' => '@typo3/backend/element/editable-page-title.js',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper::class, $arguments17, $renderingContext, $renderChildrenClosure18)]);

$output0 .= '
    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper
$renderChildrenClosure20 = function() use ($renderingContext) {
return NULL;
};

$arguments19 = [
'identifier' => '@typo3/backend/element/contextual-record-edit-trigger.js',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper::class, $arguments19, $renderingContext, $renderChildrenClosure20)]);

$output0 .= '
    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper
$renderChildrenClosure22 = function() use ($renderingContext) {
return NULL;
};

$arguments21 = [
'identifier' => '@typo3/backend/element/qrcode-modal-button.js',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper::class, $arguments21, $renderingContext, $renderChildrenClosure22)]);

$output0 .= '
    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper
$renderChildrenClosure24 = function() use ($renderingContext) {
return NULL;
};

$arguments23 = [
'identifier' => '@typo3/backend/element/immediate-action-element.js',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Asset\ModuleViewHelper::class, $arguments23, $renderingContext, $renderChildrenClosure24)]);

$output0 .= '
    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper
$renderChildrenClosure26 = function() use ($renderingContext) {
return NULL;
};

$array27 = [
'0' => 'web',
'1' => $renderingContext->getVariableProvider()->getByPath('pageId'),
];

$arguments25 = [
'name' => 'immediateActionArgs',
'value' => $array27,
];
$renderChildrenClosure26 = ($arguments25['value'] !== null) ? function() use ($arguments25) { return $arguments25['value']; } : $renderChildrenClosure26;
$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper::class, $arguments25, $renderingContext, $renderChildrenClosure26)]);

$output0 .= '
    <typo3-immediate-action
        action="TYPO3.Backend.Storage.ModuleStateStorage.update"
        args="';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper
$renderChildrenClosure29 = function() use ($renderingContext) {
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\JsonViewHelper
$renderChildrenClosure31 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('immediateActionArgs');
};

$arguments30 = [
'value' => NULL,
'forceObject' => false,
];
$renderChildrenClosure31 = ($arguments30['value'] !== null) ? function() use ($arguments30) { return $arguments30['value']; } : $renderChildrenClosure31;return $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\Format\JsonViewHelper::class, $arguments30, $renderingContext, $renderChildrenClosure31);
};

$arguments28 = [
'value' => NULL,
'keepQuotes' => false,
'encoding' => 'UTF-8',
'doubleEncode' => true,
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\Format\HtmlspecialcharsViewHelper::class, $arguments28, $renderingContext, $renderChildrenClosure29);

$output0 .= '"
    ></typo3-immediate-action>
';

    return $output0;
}
/**
 * section Content
 */
public function section_26298499e77d870c(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): mixed {
    $output32 = '';

$output32 .= '

    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array33 = [
'0' => '!',
'1' => $renderingContext->getVariableProvider()->getByPath('pageLayoutContext.drawingConfiguration.languageComparisonMode'),
];

$expression34 = function($context) {return !(TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node1"]));};

$arguments38 = [
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression34(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array33)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output35 = '';

$output35 .= '
        <typo3-backend-editable-page-title
            pageTitle="';

$output35 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('localizedPageTitle')]);

$output35 .= '"
            pageId="';

$output35 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('pageId')]);

$output35 .= '"
            localizedPageId="';

$output35 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('localizedPageId')]);

$output35 .= '"
            ';
// Rendering TernaryExpression node
$array36 = array (
  0 => '{isPageEditable ? \'editable\' : \'\'}',
  1 => '{isPageEditable ? \'editable\' : \'\'}',
);
$ternaryExpression37 = function($context, $renderingContext) {
    $check = 'isPageEditable';
    $parser = new \TYPO3Fluid\Fluid\Core\Parser\BooleanParser();
    $checkResult = $parser->evaluate($check, $context);
    if ($checkResult) {
        return TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\TernaryExpressionNode::getTemplateVariableOrValueItself('\'editable\'', $renderingContext);
    }
    return TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\TernaryExpressionNode::getTemplateVariableOrValueItself('\'\'', $renderingContext);
};

$output35 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$ternaryExpression37(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\TernaryExpressionNode::gatherContext($renderingContext, $array36[1]), $renderingContext)]);

$output35 .= '
        >
            ';

$output35 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('localizedPageTitle')]);

$output35 .= '
        </typo3-backend-editable-page-title>
    ';
return $output35;
},
];

$output32 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments38, $renderingContext)
;

$output32 .= '

    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper
$renderChildrenClosure40 = function() use ($renderingContext) {
$output41 = '';

$output41 .= '
        ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Be\InfoboxViewHelper
$renderChildrenClosure43 = function() use ($renderingContext) {
$output44 = '';

$output44 .= '
            ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\RawViewHelper
$renderChildrenClosure46 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('infoBox.message');
};

$arguments45 = [
'value' => NULL,
];

$output44 .= isset($arguments45['value']) ? $arguments45['value'] : $renderChildrenClosure46();

$output44 .= '
        ';
return $output44;
};

$arguments42 = [
'message' => NULL,
'title' => NULL,
'iconName' => NULL,
'disableIcon' => false,
'state' => $renderingContext->getVariableProvider()->getByPath('infoBox.state'),
];
$renderChildrenClosure43 = ($arguments42['message'] !== null) ? function() use ($arguments42) { return $arguments42['message']; } : $renderChildrenClosure43;
$output41 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Be\InfoboxViewHelper::class, $arguments42, $renderingContext, $renderChildrenClosure43);

$output41 .= '
    ';
return $output41;
};

$arguments39 = [
'key' => NULL,
'reverse' => false,
'iteration' => NULL,
'each' => $renderingContext->getVariableProvider()->getByPath('infoBoxes'),
'as' => 'infoBox',
];

$output32 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper::class, $arguments39, $renderingContext, $renderChildrenClosure40);

$output32 .= '

    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\RawViewHelper
$renderChildrenClosure48 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('eventContentHtmlTop');
};

$arguments47 = [
'value' => NULL,
];

$output32 .= isset($arguments47['value']) ? $arguments47['value'] : $renderChildrenClosure48();

$output32 .= '

    <form action="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\Be\UriViewHelper
$renderChildrenClosure50 = function() use ($renderingContext) {
return NULL;
};

$array51 = [
'id' => $renderingContext->getVariableProvider()->getByPath('pageId'),
];

$arguments49 = [
'referenceType' => 'absolute',
'route' => 'web_layout',
'parameters' => $array51,
];

$output32 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\Be\UriViewHelper::class, $arguments49, $renderingContext, $renderChildrenClosure50)]);

$output32 .= '" id="PageLayoutController" method="post">
        ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\RawViewHelper
$renderChildrenClosure53 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('mainContentHtml');
};

$arguments52 = [
'value' => NULL,
];

$output32 .= isset($arguments52['value']) ? $arguments52['value'] : $renderChildrenClosure53();

$output32 .= '
    </form>

    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\RawViewHelper
$renderChildrenClosure55 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('eventContentHtmlBottom');
};

$arguments54 = [
'value' => NULL,
];

$output32 .= isset($arguments54['value']) ? $arguments54['value'] : $renderChildrenClosure55();

$output32 .= '

';

    return $output32;
}
/**
 * Main Render function
 */
public function render(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): mixed {
    $output56 = '';

$output56 .= '




';

$output56 .= '';

$output56 .= '

';

$output56 .= '';

$output56 .= '

';

$output56 .= '';

$output56 .= '


';

    return $output56;
}

}

#