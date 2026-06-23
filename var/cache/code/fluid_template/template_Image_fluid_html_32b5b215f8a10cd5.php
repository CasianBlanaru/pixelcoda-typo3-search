<?php
class template_Image_fluid_html_32b5b215f8a10cd5 extends \TYPO3Fluid\Fluid\Core\Compiler\AbstractCompiledTemplate {
    public function getOriginalTemplatePath(): ?string {
        return '/var/www/html/vendor/typo3/cms-fluid-styled-content/Resources/Private/Partials/Media/Rendering/Image.fluid.html';
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
// Rendering ViewHelper TYPO3\CMS\Fluid\ViewHelpers\MediaViewHelper
$renderChildrenClosure2 = function() use ($renderingContext) {
return NULL;
};

$arguments1 = [
'additionalAttributes' => NULL,
'data' => NULL,
'aria' => NULL,
'additionalConfig' => [],
'cropVariant' => 'default',
'fileExtension' => NULL,
'file' => $renderingContext->getVariableProvider()->getByPath('file'),
'width' => $renderingContext->getVariableProvider()->getByPath('dimensions.width'),
'height' => $renderingContext->getVariableProvider()->getByPath('dimensions.height'),
'loading' => $renderingContext->getVariableProvider()->getByPath('settings.media.lazyLoading'),
'decoding' => $renderingContext->getVariableProvider()->getByPath('settings.media.imageDecoding'),
'class' => 'image-embed-item',
'alt' => $renderingContext->getVariableProvider()->getByPath('file.alternative'),
'title' => $renderingContext->getVariableProvider()->getByPath('file.title'),
];

$output0 .= $renderingContext->getViewHelperInvoker()->invoke(TYPO3\CMS\Fluid\ViewHelpers\MediaViewHelper::class, $arguments1, $renderingContext, $renderChildrenClosure2);

$output0 .= '

';

    return $output0;
}

}

#