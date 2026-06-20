<?php
class template_ImageManipulationElement_fluid_html_18836912175cb94c extends \TYPO3Fluid\Fluid\Core\Compiler\AbstractCompiledTemplate {
    public function getOriginalTemplatePath(): ?string {
        return '/var/www/html/vendor/typo3/cms-backend/Resources/Private/Templates/Form/ImageManipulationElement.fluid.html';
    }
    public function getLayoutName(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): ?string {
        return (string)'';
    }
    public function hasLayout(): bool {
        return false;
    }
    public function addCompiledNamespaces(\TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext): void {
        $renderingContext->getViewHelperResolver()->setLocalNamespaces(array (
  'core' => 
  array (
    0 => 'TYPO3\\CMS\\Core\\ViewHelpers',
  ),
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





<div class="formengine-field-item t3js-formengine-field-item">

    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\RawViewHelper
$renderChildrenClosure2 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('fieldInformation');
};

$arguments1 = [
'value' => NULL,
];

$output0 .= isset($arguments1['value']) ? $arguments1['value'] : $renderChildrenClosure2();

$output0 .= '

    <div class="form-wizards-wrap">
        <div class="form-wizards-item-element">
            ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array57 = [
'0' => $renderingContext->getVariableProvider()->getByPath('isAllowedFileExtension'),
'1' => ' && ',
'2' => $renderingContext->getVariableProvider()->getByPath('image.properties.width'),
];

$expression58 = function($context) {return (TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]) && TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node2"]));};

$arguments59 = [
'__then' => function() use ($renderingContext) {
$output3 = '';

$output3 .= '
                    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array22 = [
'0' => $renderingContext->getVariableProvider()->getByPath('config.readOnly'),
];

$expression23 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments24 = [
'__else' => function() use ($renderingContext) {
$output4 = '';

$output4 .= '
                            <div class="form-control-wrap">
                                <input
                                    type="hidden"
                                    id="';

$output4 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('formEngine.field.id')]);

$output4 .= '"
                                    name="';

$output4 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('formEngine.field.name')]);

$output4 .= '"
                                    value="';

$output4 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('formEngine.field.value')]);

$output4 .= '"
                                    data-formengine-validation-rules="';

$output4 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('formEngine.validation')]);

$output4 .= '"
                                />
                                <button
                                    class="btn btn-default t3js-image-manipulation-trigger"
                                    data-url="';

$output4 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('wizardUri')]);

$output4 .= '"
                                    data-payload="';

$output4 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('wizardPayload')]);

$output4 .= '"
                                    data-preview-url="';

$output4 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('previewUrl')]);

$output4 .= '"
                                    data-severity="notice"
                                    data-modal-title="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper
$renderChildrenClosure6 = function() use ($renderingContext) {
return NULL;
};

$arguments5 = [
'section' => NULL,
'delegate' => NULL,
'optional' => false,
'default' => NULL,
'contentAs' => NULL,
'debug' => true,
'partial' => 'Form/ImageManipulationModalTitle',
'arguments' => $renderingContext->getVariableProvider()->getAll(),
];

$output4 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\RenderViewHelper::class, $arguments5, $renderingContext, $renderChildrenClosure6);

$output4 .= '"
                                    data-image-uid="';

$output4 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('image.uid')]);

$output4 .= '"
                                    data-crop-variants="';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\JsonViewHelper
$renderChildrenClosure8 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('config.cropVariants');
};

$arguments7 = [
'value' => NULL,
'forceObject' => false,
];
$renderChildrenClosure8 = ($arguments7['value'] !== null) ? function() use ($arguments7) { return $arguments7['value']; } : $renderChildrenClosure8;
$output4 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\Format\JsonViewHelper::class, $arguments7, $renderingContext, $renderChildrenClosure8)]);

$output4 .= '"
                                    data-button-preview-text="';
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
'key' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.preview',
];

$output4 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments9, $renderingContext, $renderChildrenClosure10)]);

$output4 .= '"
                                    data-button-dismiss-text="';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure12 = function() use ($renderingContext) {
return NULL;
};

$arguments11 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'key' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.cancel',
];

$output4 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments11, $renderingContext, $renderChildrenClosure12)]);

$output4 .= '"
                                    data-button-save-text="';
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
'key' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.accept',
];

$output4 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments13, $renderingContext, $renderChildrenClosure14)]);

$output4 .= '"
                                    data-file-field="';

$output4 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('config.file_field')]);

$output4 .= '"
                                    data-field="';

$output4 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('formEngine.field.id')]);

$output4 .= '"
                                    data-sync-available="';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array15 = [
'0' => $renderingContext->getVariableProvider()->getByPath('config.syncAvailable'),
];

$expression16 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments17 = [
'__then' => function() use ($renderingContext) {

return 'true';
},
'__else' => function() use ($renderingContext) {

return 'false';
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression16(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array15)),
    $renderingContext
),
];

$output4 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments17, $renderingContext)
;

$output4 .= '"
                                >
                                    ';
// Rendering ViewHelper TYPO3\CMS\Core\ViewHelpers\IconViewHelper
$renderChildrenClosure19 = function() use ($renderingContext) {
return NULL;
};

$arguments18 = [
'overlay' => NULL,
'state' => 'default',
'alternativeMarkupIdentifier' => NULL,
'title' => NULL,
'identifier' => 'actions-crop',
'size' => 'small',
];

$output4 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Core\ViewHelpers\IconViewHelper::class, $arguments18, $renderingContext, $renderChildrenClosure19);

$output4 .= '
                                    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure21 = function() use ($renderingContext) {
return NULL;
};

$arguments20 = [
'key' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'id' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.open-editor',
];

$output4 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments20, $renderingContext, $renderChildrenClosure21)]);

$output4 .= '
                                </button>
                            </div>
                        ';
return $output4;
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression23(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array22)),
    $renderingContext
),
];

$output3 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments24, $renderingContext)
;

$output3 .= '
                    <div class="row media-gallery__list mt-2">
                        ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper
$renderChildrenClosure26 = function() use ($renderingContext) {
$output27 = '';

$output27 .= '
                            <div class="col-6 col-sm-4 col-md-3 media-gallery__item">
                                <p>
                                    <b>';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure29 = function() use ($renderingContext) {
return NULL;
};

$arguments28 = [
'key' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'id' => $renderingContext->getVariableProvider()->getByPath('cropVariant.title'),
'default' => $renderingContext->getVariableProvider()->getByPath('cropVariant.title'),
];

$output27 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments28, $renderingContext, $renderChildrenClosure29)]);

$output27 .= '</b><br/>
                                    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array30 = [
'0' => $renderingContext->getVariableProvider()->getByPath('cropVariant.allowedAspectRatios.{cropVariant.selectedRatio}.title'),
];

$expression31 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments37 = [
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression31(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array30)),
    $renderingContext
),
'__then' => function() use ($renderingContext) {
$output32 = '';

$output32 .= '
                                        ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure34 = function() use ($renderingContext) {
return NULL;
};

$arguments33 = [
'key' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'id' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.aspect-ratio',
];

$output32 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments33, $renderingContext, $renderChildrenClosure34)]);

$output32 .= ': <span class="t3js-image-manipulation-selected-ratio" data-crop-variant-id="';

$output32 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('cropVariant.id')]);

$output32 .= '">';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure36 = function() use ($renderingContext) {
return NULL;
};

$arguments35 = [
'key' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'id' => $renderingContext->getVariableProvider()->getByPath('cropVariant.allowedAspectRatios.{cropVariant.selectedRatio}.title'),
'default' => $renderingContext->getVariableProvider()->getByPath('cropVariant.allowedAspectRatios.{cropVariant.selectedRatio}.title'),
];

$output32 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments35, $renderingContext, $renderChildrenClosure36)]);

$output32 .= '</span>
                                    ';
return $output32;
},
];

$output27 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments37, $renderingContext)
;

$output27 .= '
                                </p>
                                <div
                                    class="t3js-image-manipulation-preview media-object"
                                    data-preview-height="150"
                                    data-crop-variant-id="';

$output27 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getVariableProvider()->getByPath('cropVariant.id')]);

$output27 .= '"
                                >
                                    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\ImageViewHelper
$renderChildrenClosure39 = function() use ($renderingContext) {
return NULL;
};
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\JsonViewHelper
$renderChildrenClosure42 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('cropVariant');
};

$arguments41 = [
'value' => NULL,
'forceObject' => false,
];
$renderChildrenClosure42 = ($arguments41['value'] !== null) ? function() use ($arguments41) { return $arguments41['value']; } : $renderChildrenClosure42;
$array40 = [
'data-crop-variant' => $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\Format\JsonViewHelper::class, $arguments41, $renderingContext, $renderChildrenClosure42),
'data-crop-variant-id' => $renderingContext->getVariableProvider()->getByPath('cropVariant.id'),
];

$arguments38 = [
'data' => NULL,
'aria' => NULL,
'src' => '',
'treatIdAsReference' => false,
'fileExtension' => NULL,
'width' => NULL,
'height' => NULL,
'minWidth' => NULL,
'minHeight' => NULL,
'maxWidth' => NULL,
'absolute' => false,
'base64' => false,
'image' => $renderingContext->getVariableProvider()->getByPath('image'),
'crop' => $renderingContext->getVariableProvider()->getByPath('formEngine.field.value'),
'cropVariant' => $renderingContext->getVariableProvider()->getByPath('cropVariant.id'),
'maxHeight' => 150,
'additionalAttributes' => $array40,
'class' => 'thumbnail thumbnail-status',
'loading' => 'lazy',
];

$output27 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\ImageViewHelper::class, $arguments38, $renderingContext, $renderChildrenClosure39);

$output27 .= '
                                </div>
                            </div>
                        ';
return $output27;
};

$arguments25 = [
'key' => NULL,
'reverse' => false,
'iteration' => NULL,
'each' => $renderingContext->getVariableProvider()->getByPath('config.cropVariants'),
'as' => 'cropVariant',
];

$output3 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\ForViewHelper::class, $arguments25, $renderingContext, $renderChildrenClosure26);

$output3 .= '
                    </div>
                ';
return $output3;
},
'__else' => function() use ($renderingContext) {
$output43 = '';

$output43 .= '
                    <div class="media-body">
                    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper

$array54 = [
'0' => $renderingContext->getVariableProvider()->getByPath('image.properties.width'),
];

$expression55 = function($context) {return TYPO3Fluid\Fluid\Core\Parser\BooleanParser::convertNodeToBoolean($context["node0"]);};

$arguments56 = [
'__then' => function() use ($renderingContext) {
$output44 = '';

$output44 .= '
                            <p>
                                <em>
                                    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure46 = function() use ($renderingContext) {
return NULL;
};

$arguments45 = [
'key' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'id' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.supported-types-message',
];

$output44 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments45, $renderingContext, $renderChildrenClosure46)]);

$output44 .= '<br/>
                                    ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\CaseViewHelper
$renderChildrenClosure48 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('config.allowedExtensions');
};

$arguments47 = [
'value' => NULL,
'mode' => 'upper',
];

$output44 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\Format\CaseViewHelper::class, $arguments47, $renderingContext, $renderChildrenClosure48)]);

$output44 .= '
                                </em>
                            </p>
                        ';
return $output44;
},
'__else' => function() use ($renderingContext) {
$output49 = '';

$output49 .= '
                            <div class="alert alert-info">
                                <h4>
                                    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure51 = function() use ($renderingContext) {
return NULL;
};

$arguments50 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'key' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.no-image-dimensions',
];

$output49 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments50, $renderingContext, $renderChildrenClosure51)]);

$output49 .= '
                                </h4>
                                <p>
                                    ';
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper
$renderChildrenClosure53 = function() use ($renderingContext) {
return NULL;
};

$arguments52 = [
'id' => NULL,
'default' => NULL,
'arguments' => NULL,
'extensionName' => NULL,
'domain' => NULL,
'languageKey' => NULL,
'key' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.no-image-dimensions-message',
];

$output49 .= call_user_func_array( function ($var) { if ($var instanceof TYPO3Fluid\Fluid\Core\Parser\UnsafeHTML) { return (string)$var; }return (is_string($var) || (is_object($var) && method_exists($var, '__toString')) ? htmlspecialchars((string) $var, ENT_QUOTES) : $var); }, [$renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper::class, $arguments52, $renderingContext, $renderChildrenClosure53)]);

$output49 .= '
                                </p>
                            </div>
                        ';
return $output49;
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression55(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array54)),
    $renderingContext
),
];

$output43 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments56, $renderingContext)
;

$output43 .= '
                    </div>
                ';
return $output43;
},
'condition' => TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(
    $expression58(TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\BooleanNode::gatherContext($renderingContext, $array57)),
    $renderingContext
),
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper::class, $arguments59, $renderingContext)
;

$output0 .= '
        </div>

        <div class="form-wizards-item-aside form-wizards-item-aside--field-control">
            <div class="btn-group">
                ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\RawViewHelper
$renderChildrenClosure61 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('fieldControl');
};

$arguments60 = [
'value' => NULL,
];

$output0 .= isset($arguments60['value']) ? $arguments60['value'] : $renderChildrenClosure61();

$output0 .= '
            </div>
        </div>

        <div class="form-wizards-item-bottom">
            ';
// Rendering ViewHelper TYPO3Fluid\Fluid\ViewHelpers\Format\RawViewHelper
$renderChildrenClosure63 = function() use ($renderingContext) {
return $renderingContext->getVariableProvider()->getByPath('fieldWizard');
};

$arguments62 = [
'value' => NULL,
];

$output0 .= isset($arguments62['value']) ? $arguments62['value'] : $renderChildrenClosure63();

$output0 .= '
        </div>
    </div>

</div>


';

    return $output0;
}

}

#