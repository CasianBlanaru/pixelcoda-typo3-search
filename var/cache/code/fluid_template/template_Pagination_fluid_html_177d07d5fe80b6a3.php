<?php
class template_Pagination_fluid_html_177d07d5fe80b6a3 extends \TYPO3Fluid\Fluid\Core\Compiler\AbstractCompiledTemplate {
    public function getOriginalTemplatePath(): ?string {
        return '/var/www/html/vendor/typo3/cms-filelist/Resources/Private/Partials/Pagination.fluid.html';
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
  'core' => 
  array (
    0 => 'TYPO3\\CMS\\Core\\ViewHelpers',
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
'0' => $renderingContext->getVariableProvider()->getByPath('paginator.numberOfPages'),
'1' => ' > 1',
];

$expression2 = function($context) {return (TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]) > 1);};

$arguments70 = [
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression2(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array1)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output3 = '';

$output3 .= '
    <nav aria-labelledby="filelist-pagination">
        <ul class="pagination mb-0">
            <li class="page-item">
                <span id="filelist-pagination" class="page-link">
                    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure5 = function() use ($renderingContext) {
return NULL;
};

$array6 = [
'0' => $renderingContext->getVariableProvider()->getByPath('firstElement'),
'1' => $renderingContext->getVariableProvider()->getByPath('lastElement'),
];

$arguments4 = [
'id' => NULL,
'default' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'key' => 'rangeIndicator',
'domain' => 'filelist.mod_file_list',
'arguments' => $array6,
];

$output3 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments4, $renderingContext, $renderChildrenClosure5)]);

$output3 .= '
                    <span class="visually-hidden">, ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure8 = function() use ($renderingContext) {
return NULL;
};

$array9 = [
'0' => $renderingContext->getVariableProvider()->getByPath('currentPage'),
'1' => $renderingContext->getVariableProvider()->getByPath('totalPages'),
];

$arguments7 = [
'id' => NULL,
'default' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'key' => 'pageIndicator',
'domain' => 'core.mod_web_list',
'arguments' => $array9,
];

$output3 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments7, $renderingContext, $renderChildrenClosure8)]);

$output3 .= '</span>
                </span>
            </li>
            ';

$output3 .= '';

$output3 .= '';

$output3 .= '';

$output3 .= '
            ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array30 = [
'0' => $renderingContext->getVariableProvider()->getByPath('currentPage'),
'1' => ' > 1',
];

$expression31 = function($context) {return (TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]) > 1);};

$arguments32 = [
'__then' => function() use ($renderingContext) {
$output10 = '';

$output10 .= '
                    <li class="page-item"><a class="page-link" href="';

$output10 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('currentUrl')]);

$output10 .= '&currentPage=1" aria-label="';
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
'key' => 'first',
'domain' => 'core.common',
];

$output10 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments11, $renderingContext, $renderChildrenClosure12)]);

$output10 .= '" title="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure14 = function() use ($renderingContext) {
return NULL;
};

$arguments13 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'key' => 'first',
'domain' => 'core.common',
];

$output10 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments13, $renderingContext, $renderChildrenClosure14)]);

$output10 .= '">';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure16 = function() use ($renderingContext) {
return NULL;
};

$arguments15 = [
'size' => 'small',
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-view-paging-first',
];

$output10 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments15, $renderingContext, $renderChildrenClosure16);

$output10 .= '</a></li>
                    <li class="page-item"><a class="page-link" href="';

$output10 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('currentUrl')]);

$output10 .= '&currentPage=';
// Rendering TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\MathExpressionNode node
$string17 = '{currentPage -1}';
$array18 = array (
  0 => '{currentPage -1}',
  1 => '{currentPage -1}',
);

$output10 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [\TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\MathExpressionNode::evaluateExpression($renderingContext, $string17, $array18)]);

$output10 .= '" aria-label="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure20 = function() use ($renderingContext) {
return NULL;
};

$arguments19 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'key' => 'previous',
'domain' => 'core.common',
];

$output10 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments19, $renderingContext, $renderChildrenClosure20)]);

$output10 .= '" title="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure22 = function() use ($renderingContext) {
return NULL;
};

$arguments21 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'key' => 'previous',
'domain' => 'core.common',
];

$output10 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments21, $renderingContext, $renderChildrenClosure22)]);

$output10 .= '">';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure24 = function() use ($renderingContext) {
return NULL;
};

$arguments23 = [
'size' => 'small',
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-view-paging-previous',
];

$output10 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments23, $renderingContext, $renderChildrenClosure24);

$output10 .= '</a></li>
                ';
return $output10;
},
'__else' => function() use ($renderingContext) {
$output25 = '';

$output25 .= '
                    <li class="page-item disabled" aria-hidden="true"><span class="page-link">';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure27 = function() use ($renderingContext) {
return NULL;
};

$arguments26 = [
'size' => 'small',
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-view-paging-first',
];

$output25 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments26, $renderingContext, $renderChildrenClosure27);

$output25 .= '</span></li>
                    <li class="page-item disabled" aria-hidden="true"><span class="page-link">';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure29 = function() use ($renderingContext) {
return NULL;
};

$arguments28 = [
'size' => 'small',
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-view-paging-previous',
];

$output25 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments28, $renderingContext, $renderChildrenClosure29);

$output25 .= '</span></li>
                ';
return $output25;
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression31(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array30)),
    $renderingContext
),
];

$output3 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments32, $renderingContext)
;

$output3 .= '
            ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper
$renderChildrenClosure34 = function() use ($renderingContext) {
$output35 = '';

$output35 .= '
                <input type="number" autocomplete="off" name="currentPage" min="1" max="';

$output35 .= $renderingContext->getVariableProvider()->getByPath('totalPages');

$output35 .= '" value="';

$output35 .= $renderingContext->getVariableProvider()->getByPath('currentPage');

$output35 .= '" size="3" class="t3js-filelist-paging form-control form-control-sm paginator-input" />
                <span aria-hidden="true">
            ';
return $output35;
};

$arguments33 = [
'value' => NULL,
'name' => 'pageInput',
];
$renderChildrenClosure34 = ($arguments33['value'] !== null) ? function() use ($arguments33) { return $arguments33['value']; } : $renderChildrenClosure34;
$output3 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper::class, $arguments33, $renderingContext, $renderChildrenClosure34)]);

$output3 .= '
            <li class="page-item">
                <span class="page-link">
                    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\RawViewHelper
$renderChildrenClosure37 = function() use ($renderingContext) {
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure39 = function() use ($renderingContext) {
return NULL;
};

$array40 = [
'0' => $renderingContext->getVariableProvider()->getByPath('pageInput'),
'1' => $renderingContext->getVariableProvider()->getByPath('totalPages'),
];

$arguments38 = [
'id' => NULL,
'default' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'key' => 'pageIndicator',
'domain' => 'core.mod_web_list',
'arguments' => $array40,
];
return $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments38, $renderingContext, $renderChildrenClosure39);
};

$arguments36 = [
'value' => NULL,
];

$output3 .= isset($arguments36['value']) ? $arguments36['value'] : $renderChildrenClosure37();

$output3 .= '</span>
                </span>
            </li>

            ';

$output3 .= '';

$output3 .= '';

$output3 .= '';

$output3 .= '
            ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array61 = [
'0' => $renderingContext->getVariableProvider()->getByPath('currentPage'),
'1' => ' < ',
'2' => $renderingContext->getVariableProvider()->getByPath('totalPages'),
];

$expression62 = function($context) {return (TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]) < TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node2"]));};

$arguments63 = [
'__then' => function() use ($renderingContext) {
$output41 = '';

$output41 .= '
                    <li class="page-item"><a class="page-link" href="';

$output41 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('currentUrl')]);

$output41 .= '&currentPage=';
// Rendering TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\MathExpressionNode node
$string42 = '{currentPage + 1}';
$array43 = array (
  0 => '{currentPage + 1}',
  1 => '{currentPage + 1}',
);

$output41 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [\TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression\MathExpressionNode::evaluateExpression($renderingContext, $string42, $array43)]);

$output41 .= '" aria-label="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure45 = function() use ($renderingContext) {
return NULL;
};

$arguments44 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'key' => 'next',
'domain' => 'core.common',
];

$output41 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments44, $renderingContext, $renderChildrenClosure45)]);

$output41 .= '" title="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure47 = function() use ($renderingContext) {
return NULL;
};

$arguments46 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'key' => 'next',
'domain' => 'core.common',
];

$output41 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments46, $renderingContext, $renderChildrenClosure47)]);

$output41 .= '">';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure49 = function() use ($renderingContext) {
return NULL;
};

$arguments48 = [
'size' => 'small',
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-view-paging-next',
];

$output41 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments48, $renderingContext, $renderChildrenClosure49);

$output41 .= '</a></li>
                    <li class="page-item"><a class="page-link" href="';

$output41 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('currentUrl')]);

$output41 .= '&currentPage=';

$output41 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('totalPages')]);

$output41 .= '" aria-label="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure51 = function() use ($renderingContext) {
return NULL;
};

$arguments50 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'key' => 'last',
'domain' => 'core.common',
];

$output41 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments50, $renderingContext, $renderChildrenClosure51)]);

$output41 .= '" title="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure53 = function() use ($renderingContext) {
return NULL;
};

$arguments52 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'key' => 'last',
'domain' => 'core.common',
];

$output41 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments52, $renderingContext, $renderChildrenClosure53)]);

$output41 .= '">';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure55 = function() use ($renderingContext) {
return NULL;
};

$arguments54 = [
'size' => 'small',
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-view-paging-last',
];

$output41 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments54, $renderingContext, $renderChildrenClosure55);

$output41 .= '</a></li>
                ';
return $output41;
},
'__else' => function() use ($renderingContext) {
$output56 = '';

$output56 .= '
                    <li class="page-item disabled" aria-hidden="true"><span class="page-link">';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure58 = function() use ($renderingContext) {
return NULL;
};

$arguments57 = [
'size' => 'small',
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-view-paging-next',
];

$output56 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments57, $renderingContext, $renderChildrenClosure58);

$output56 .= '</span></li>
                    <li class="page-item disabled" aria-hidden="true"><span class="page-link">';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure60 = function() use ($renderingContext) {
return NULL;
};

$arguments59 = [
'size' => 'small',
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-view-paging-last',
];

$output56 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments59, $renderingContext, $renderChildrenClosure60);

$output56 .= '</span></li>
                ';
return $output56;
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression62(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array61)),
    $renderingContext
),
];

$output3 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments63, $renderingContext)
;

$output3 .= '
            <li class="page-item"><a class="page-link" href="';

$output3 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('currentUrl')]);

$output3 .= '&currentPage=';

$output3 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('currentPage')]);

$output3 .= '" aria-label="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure65 = function() use ($renderingContext) {
return NULL;
};

$arguments64 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'key' => 'reload',
'domain' => 'core.common',
];

$output3 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments64, $renderingContext, $renderChildrenClosure65)]);

$output3 .= '" title="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure67 = function() use ($renderingContext) {
return NULL;
};

$arguments66 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'languageKey' => NULL,
'key' => 'reload',
'domain' => 'core.common',
];

$output3 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments66, $renderingContext, $renderChildrenClosure67)]);

$output3 .= '">';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure69 = function() use ($renderingContext) {
return NULL;
};

$arguments68 = [
'size' => 'small',
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-refresh',
];

$output3 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments68, $renderingContext, $renderChildrenClosure69);

$output3 .= '</a></li>
        </ul>
    </nav>
';
return $output3;
},
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments70, $renderingContext)
;

$output0 .= '

';

    return $output0;
}

}

#