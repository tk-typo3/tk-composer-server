<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\FormEngine\FieldControl;

use TYPO3\CMS\Backend\Form\Element\InputTextElement;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * @package TimonKreis\TkComposerServer\FormEngine\FieldControl
 */
class PasswordElement extends InputTextElement
{
    /**
     * @inheritDoc
     */
    public function render() : array
    {
        $parent = parent::render();

        $attributes = [
            'value' => '',
            'id' => StringUtility::getUniqueId('formengine-input-'),
            'class' => 'form-control t3js-clearable hasDefaultValue',
            'data-formengine-input-params' => json_encode([
                'field' => $this->data['parameterArray']['itemFormElName'],
                'evalList' => $this->data['parameterArray']['fieldConf']['config']['eval'],
            ]),
            'data-formengine-input-name' => $this->data['parameterArray']['itemFormElName'],
            'style' => 'font-family:monospace',
        ];

        /** @var StandaloneView $standaloneView */
        $standaloneView = GeneralUtility::makeInstance(StandaloneView::class);
        $standaloneView->setTemplatePathAndFilename(
            'EXT:tk_composer_server/Resources/Private/Templates/FormEngine/FieldControl/PasswordElement.html'
        );
        $standaloneView->assignMultiple([
            'attributes' => GeneralUtility::implodeAttributes($attributes, true),
            'name' => $this->data['parameterArray']['itemFormElName'],
            'value' => htmlspecialchars($this->data['parameterArray']['itemFormElValue']),
            'id' => $attributes['id'],
            'icon' => $this->iconFactory->getIcon('actions-synchronize', Icon::SIZE_SMALL)->render(),
        ]);

        $parent['html'] = $standaloneView->render();

        return $parent;
    }
}
