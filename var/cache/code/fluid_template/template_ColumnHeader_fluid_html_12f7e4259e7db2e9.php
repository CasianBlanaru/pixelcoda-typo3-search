<?php
class template_ColumnHeader_fluid_html_12f7e4259e7db2e9 extends \TYPO3Fluid\Fluid\Core\Compiler\AbstractCompiledTemplate {
    public function getOriginalTemplatePath(): ?string {
        return '/var/www/html/vendor/typo3/cms-backend/Resources/Private/Partials/PageLayout/Grid/ColumnHeader.fluid.html';
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
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper
$renderChildrenClosure2 = function() use ($renderingContext) {
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\OrViewHelper
$renderChildrenClosure4 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('columnHeaderLevel');
};

$arguments3 = [
'content' => NULL,
'arguments' => NULL,
'alternative' => 2,
];
$renderChildrenClosure4 = ($arguments3['content'] !== null) ? function() use ($arguments3) { return $arguments3['content']; } : $renderChildrenClosure4;return $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\OrViewHelper::class, $arguments3, $renderingContext, $renderChildrenClosure4);
};

$arguments1 = [
'value' => NULL,
'name' => 'columnHeaderLevel',
];
$renderChildrenClosure2 = ($arguments1['value'] !== null) ? function() use ($arguments1) { return $arguments1['value']; } : $renderChildrenClosure2;
$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\VariableViewHelper::class, $arguments1, $renderingContext, $renderChildrenClosure2)]);

$output0 .= '

<div class="t3-page-column-header">
    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array75 = [
'0' => $renderingContext->getVariableProvider()->getByPath('column.active'),
];

$expression76 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments77 = [
'__then' => function() use ($renderingContext) {
$output5 = '';

$output5 .= '
            <div class="t3-page-column-header-icons">
                ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array6 = [
'0' => $renderingContext->getVariableProvider()->getByPath('allowEditContent'),
'1' => ' && ',
'2' => $renderingContext->getVariableProvider()->getByPath('column.editUrl'),
];

$expression7 = function($context) {return (TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]) && TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node2"]));};

$arguments15 = [
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression7(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array6)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output8 = '';

$output8 .= '
                    <a
                        class="btn btn-link"
                        href="';

$output8 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('column.editUrl')]);

$output8 .= '"
                        title="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure10 = function() use ($renderingContext) {
return NULL;
};

$arguments9 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'key' => 'LLL:EXT:backend/Resources/Private/Language/locallang_layout.xlf:editColumn',
];

$output8 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments9, $renderingContext, $renderChildrenClosure10)]);

$output8 .= '"
                    >
                        ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure12 = function() use ($renderingContext) {
return NULL;
};

$arguments11 = [
'size' => 'small',
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-document-open',
];

$output8 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments11, $renderingContext, $renderChildrenClosure12);

$output8 .= '
                        <span class="visually-hidden">';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure14 = function() use ($renderingContext) {
return NULL;
};

$arguments13 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'key' => 'LLL:EXT:backend/Resources/Private/Language/locallang_layout.xlf:editColumn',
];

$output8 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments13, $renderingContext, $renderChildrenClosure14)]);

$output8 .= '</span>
                    </a>
                ';
return $output8;
},
];

$output5 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments15, $renderingContext)
;

$output5 .= '
            </div>
            <h';

$output5 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('columnHeaderLevel')]);

$output5 .= ' id="';

$output5 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('columnIdentifier')]);

$output5 .= '" class="t3-page-column-title">
                ';

$output5 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('column.title')]);

$output5 .= '
                ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array16 = [
'0' => $renderingContext->getVariableProvider()->getByPath('context.pageContext.pageRecord.pid'),
'1' => ' !== 0 && ',
'2' => $renderingContext->getVariableProvider()->getByPath('column.slideMode.value'),
'3' => ' !== \'none\'',
];

$expression17 = function($context) {return (($context["node0"] !== 0) && ($context["node2"] !== 'none'));};

$arguments65 = [
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression17(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array16)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output18 = '';

$output18 .= '
                    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array19 = [
'0' => '!(',
'1' => $renderingContext->getVariableProvider()->getByPath('column.slideMode.value'),
'2' => ' === \'slide\' && ',
'3' => $renderingContext->getVariableProvider()->getByPath('column.items'),
'4' => ')',
];

$expression20 = function($context) {return !((($context["node1"] === 'slide') && TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node3"])));};

$arguments64 = [
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression20(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array19)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output21 = '';

$output21 .= '
                        <span class="badge badge-info" title="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure23 = function() use ($renderingContext) {
return NULL;
};
$output24 = '';

$output24 .= 'LLL:EXT:backend/Resources/Private/Language/locallang_layout.xlf:slideModeInfo.';

$output24 .= $renderingContext->getVariableProvider()->getByPath('column.slideMode.value');

$arguments22 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'key' => $output24,
];

$output21 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments22, $renderingContext, $renderChildrenClosure23)]);

$output21 .= '">
                            ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\SwitchViewHelper
$renderChildrenClosure26 = function() use ($renderingContext) {
$output42 = '';

$output42 .= '
                                ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\CaseViewHelper
$renderChildrenClosure44 = function() use ($renderingContext) {
$output45 = '';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure47 = function() use ($renderingContext) {
return NULL;
};

$arguments46 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-search',
'size' => 'small',
];

$output45 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments46, $renderingContext, $renderChildrenClosure47);
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure49 = function() use ($renderingContext) {
return NULL;
};

$arguments48 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-sort-amount-up',
'size' => 'small',
];

$output45 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments48, $renderingContext, $renderChildrenClosure49);
return $output45;
};

$arguments43 = [
'value' => 'slide',
];

$output42 .= '';

$output42 .= '
                                ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\CaseViewHelper
$renderChildrenClosure51 = function() use ($renderingContext) {
$output52 = '';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure54 = function() use ($renderingContext) {
return NULL;
};

$arguments53 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-cart',
'size' => 'small',
];

$output52 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments53, $renderingContext, $renderChildrenClosure54);
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure56 = function() use ($renderingContext) {
return NULL;
};

$arguments55 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-sort-amount-down',
'size' => 'small',
];

$output52 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments55, $renderingContext, $renderChildrenClosure56);
return $output52;
};

$arguments50 = [
'value' => 'collect',
];

$output42 .= '';

$output42 .= '
                                ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\CaseViewHelper
$renderChildrenClosure58 = function() use ($renderingContext) {
$output59 = '';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure61 = function() use ($renderingContext) {
return NULL;
};

$arguments60 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-cart',
'size' => 'small',
];

$output59 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments60, $renderingContext, $renderChildrenClosure61);
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure63 = function() use ($renderingContext) {
return NULL;
};

$arguments62 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-sort-amount-up',
'size' => 'small',
];

$output59 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments62, $renderingContext, $renderChildrenClosure63);
return $output59;
};

$arguments57 = [
'value' => 'collectReverse',
];

$output42 .= '';

$output42 .= '
                            ';
return $output42;
};

$arguments25 = [
'expression' => $renderingContext->getVariableProvider()->getByPath('column.slideMode.value'),
];

$output21 .= call_user_func_array(function($arguments) use ($renderingContext) {
switch ($arguments['expression']) {
case call_user_func(function() use ($renderingContext) {

return 'slide';
}): return call_user_func(function() use ($renderingContext) {
$output27 = '';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure29 = function() use ($renderingContext) {
return NULL;
};

$arguments28 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-search',
'size' => 'small',
];

$output27 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments28, $renderingContext, $renderChildrenClosure29);
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure31 = function() use ($renderingContext) {
return NULL;
};

$arguments30 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-sort-amount-up',
'size' => 'small',
];

$output27 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments30, $renderingContext, $renderChildrenClosure31);
return $output27;
});
case call_user_func(function() use ($renderingContext) {

return 'collect';
}): return call_user_func(function() use ($renderingContext) {
$output32 = '';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure34 = function() use ($renderingContext) {
return NULL;
};

$arguments33 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-cart',
'size' => 'small',
];

$output32 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments33, $renderingContext, $renderChildrenClosure34);
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure36 = function() use ($renderingContext) {
return NULL;
};

$arguments35 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-sort-amount-down',
'size' => 'small',
];

$output32 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments35, $renderingContext, $renderChildrenClosure36);
return $output32;
});
case call_user_func(function() use ($renderingContext) {

return 'collectReverse';
}): return call_user_func(function() use ($renderingContext) {
$output37 = '';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure39 = function() use ($renderingContext) {
return NULL;
};

$arguments38 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-cart',
'size' => 'small',
];

$output37 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments38, $renderingContext, $renderChildrenClosure39);
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure41 = function() use ($renderingContext) {
return NULL;
};

$arguments40 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-sort-amount-up',
'size' => 'small',
];

$output37 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments40, $renderingContext, $renderChildrenClosure41);
return $output37;
});
default:
return '';
}
}, array($arguments25));

$output21 .= '
                        </span>
                    ';
return $output21;
},
];

$output18 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments64, $renderingContext)
;

$output18 .= '
                ';
return $output18;
},
];

$output5 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments65, $renderingContext)
;

$output5 .= '
            </h';

$output5 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('columnHeaderLevel')]);

$output5 .= '>
        ';
return $output5;
},
'__elseIf' => [
'0' => [
'condition' => function() use ($renderingContext) {

$array66 = [
'0' => $renderingContext->getVariableProvider()->getByPath('column.unused'),
];

$expression67 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

return TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression67(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array66)),
    $renderingContext
);
},
'body' => function() use ($renderingContext) {
$output68 = '';

$output68 .= '
            <span id="';

$output68 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('columnIdentifier')]);

$output68 .= '">';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure70 = function() use ($renderingContext) {
return NULL;
};

$arguments69 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'key' => 'LLL:EXT:backend/Resources/Private/Language/locallang_layout.xlf:unusedColPos',
];

$output68 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments69, $renderingContext, $renderChildrenClosure70)]);

$output68 .= '</span>
        ';
return $output68;
},
],
'1' => [
'condition' => function() use ($renderingContext) {

$array71 = [
'0' => $renderingContext->getVariableProvider()->getByPath('column.unassigned'),
];

$expression72 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

return TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression72(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array71)),
    $renderingContext
);
},
'body' => function() use ($renderingContext) {
$output73 = '';

$output73 .= '
            <span id="';

$output73 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('columnIdentifier')]);

$output73 .= '">';

$output73 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('column.titleUnassigned')]);

$output73 .= '</span>
        ';
return $output73;
},
],
],
'__else' => function() use ($renderingContext) {
$output74 = '';

$output74 .= '
            <span id="';

$output74 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('columnIdentifier')]);

$output74 .= '">';

$output74 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('column.titleInaccessible')]);

$output74 .= '</span>
        ';
return $output74;
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression76(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array75)),
    $renderingContext
),
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments77, $renderingContext)
;

$output0 .= '
</div>
';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\RawViewHelper
$renderChildrenClosure79 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('column.beforeSectionMarkup');
};

$arguments78 = [
'value' => NULL,
];

$output0 .= isset($arguments78['value']) ? $arguments78['value'] : $renderChildrenClosure79();

$output0 .= '
';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array80 = [
'0' => $renderingContext->getVariableProvider()->getByPath('allowEditContent'),
'1' => ' && ',
'2' => $renderingContext->getVariableProvider()->getByPath('column.contentEditable'),
'3' => ' && ',
'4' => $renderingContext->getVariableProvider()->getByPath('column.context.allowNewContent'),
'5' => ' && ',
'6' => $renderingContext->getVariableProvider()->getByPath('column.active'),
];

$expression81 = function($context) {return (((TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]) && TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node2"])) && TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node4"])) && TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node6"]));};

$arguments86 = [
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression81(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array80)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output82 = '';

$output82 .= '
    <div class="t3-page-ce t3js-page-ce" data-page="';

$output82 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('column.context.pageId')]);

$output82 .= '">
        ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper
$renderChildrenClosure84 = function() use ($renderingContext) {
return NULL;
};

$array85 = [
'column' => $renderingContext->getVariableProvider()->getByPath('column'),
'allowEditContent' => $renderingContext->getVariableProvider()->getByPath('allowEditContent'),
'url' => $renderingContext->getVariableProvider()->getByPath('column.newContentUrl'),
];

$arguments83 = [
'section' => NULL,
'delegate' => NULL,
'optional' => false,
'default' => NULL,
'contentAs' => NULL,
'debug' => true,
'partial' => 'PageLayout/ActionControls',
'arguments' => $array85,
];

$output82 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper::class, $arguments83, $renderingContext, $renderChildrenClosure84);

$output82 .= '
        <div class="t3-page-ce-dropzone t3js-page-ce-dropzone-available" hidden></div>
    </div>
';
return $output82;
},
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments86, $renderingContext)
;

$output0 .= '
';

    return $output0;
}

}

#