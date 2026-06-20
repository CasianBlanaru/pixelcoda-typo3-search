<?php
class template_RecordSearchBox_fluid_html_df3a534c1cbd496d extends \TYPO3Fluid\Fluid\Core\Compiler\AbstractCompiledTemplate {
    public function getOriginalTemplatePath(): ?string {
        return '/var/www/html/vendor/typo3/cms-backend/Resources/Private/Templates/RecordSearchBox.fluid.html';
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

$output0 .= '<div class="recordsearchbox-container" data-recordsearchbox-container>
    <form action="';

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('formUrl')]);

$output0 .= '" method="post">
        <div class="input-group">
            <label for="recordsearchbox-searchterm" class="visually-hidden">
                ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure2 = function() use ($renderingContext) {
return NULL;
};

$arguments1 = [
'key' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'id' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.label.searchString',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments1, $renderingContext, $renderChildrenClosure2)]);

$output0 .= '
            </label>
            <input
                data-recordsearchbox-input
                class="form-control"
                type="search"
                autocomplete="off"
                placeholder="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure4 = function() use ($renderingContext) {
return NULL;
};

$arguments3 = [
'key' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'id' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.enterSearchString',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments3, $renderingContext, $renderChildrenClosure4)]);

$output0 .= '"
                title="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure6 = function() use ($renderingContext) {
return NULL;
};

$arguments5 = [
'key' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'id' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.title.searchString',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments5, $renderingContext, $renderChildrenClosure6)]);

$output0 .= '"
                name="searchTerm"
                id="recordsearchbox-searchterm"
                value="';

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('searchString')]);

$output0 .= '"
            />
            ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\CountViewHelper
$renderChildrenClosure9 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('availableSearchLevels');
};

$arguments8 = [
'subject' => NULL,
];
$renderChildrenClosure9 = ($arguments8['subject'] !== null) ? function() use ($arguments8) { return $arguments8['subject']; } : $renderChildrenClosure9;
$array7 = [
'0' => $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\CountViewHelper::class, $arguments8, $renderingContext, $renderChildrenClosure9),
'1' => ' > 0',
];

$expression10 = function($context) {return (TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]) > 0);};

$arguments25 = [
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression10(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array7)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output11 = '';

$output11 .= '
                <label for="search_levels" class="visually-hidden">
                    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure13 = function() use ($renderingContext) {
return NULL;
};

$arguments12 = [
'key' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'id' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.label.search_levels',
];

$output11 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments12, $renderingContext, $renderChildrenClosure13)]);

$output11 .= '
                </label>
                <select
                    data-recordsearchbox-levels
                    class="form-select"
                    name="search_levels"
                    title="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure15 = function() use ($renderingContext) {
return NULL;
};

$arguments14 = [
'key' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'id' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.title.search_levels',
];

$output11 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments14, $renderingContext, $renderChildrenClosure15)]);

$output11 .= '"
                    id="search_levels"
                >
                    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper
$renderChildrenClosure17 = function() use ($renderingContext) {
$output18 = '';

$output18 .= '
                        <option ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array19 = [
'0' => $renderingContext->getVariableProvider()->getByPath('level'),
'1' => ' == ',
'2' => $renderingContext->getVariableProvider()->getByPath('selectedSearchLevel'),
];

$expression20 = function($context) {return (TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]) == TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node2"]));};

$arguments21 = [
'__then' => function() use ($renderingContext) {

return ' selected="selected"';
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression20(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array19)),
    $renderingContext
),
];

$output18 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments21, $renderingContext)
;

$output18 .= ' value="';

$output18 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('level')]);

$output18 .= '">
                        ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure23 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('searchLevel');
};
$output24 = '';

$output24 .= 'LLL:';

$output24 .= $renderingContext->getVariableProvider()->getByPath('searchLevel');

$arguments22 = [
'key' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'id' => $output24,
];

$output18 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments22, $renderingContext, $renderChildrenClosure23)]);

$output18 .= '
                        </option>
                    ';
return $output18;
};

$arguments16 = [
'reverse' => false,
'iteration' => NULL,
'each' => $renderingContext->getVariableProvider()->getByPath('availableSearchLevels'),
'as' => 'searchLevel',
'key' => 'level',
];

$output11 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper::class, $arguments16, $renderingContext, $renderChildrenClosure17);

$output11 .= '
                </select>
            ';
return $output11;
},
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments25, $renderingContext)
;

$output0 .= '
            <button data-recordsearchbox-submit class="btn btn-default" type="submit" name="search">
                ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure27 = function() use ($renderingContext) {
return NULL;
};

$arguments26 = [
'key' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'id' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.title.search',
];

$output0 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments26, $renderingContext, $renderChildrenClosure27)]);

$output0 .= '
            </button>
        </div>
    </form>
</div>
';

    return $output0;
}

}

#