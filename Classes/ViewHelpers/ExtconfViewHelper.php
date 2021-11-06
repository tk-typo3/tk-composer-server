<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * @package TimonKreis\TkComposer
 */
class ExtconfViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Initialize the arguments
     */
    public function initializeArguments() : void
    {
        $this->registerArgument('key', 'string', 'Extension configuration key', true);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     * @throws \Exception
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) : string {
        static $values = [];

        if (!array_key_exists($arguments['key'], $values)) {
            $parts = explode('/', $arguments['key']);
            $value = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tk_composer_server']['frontend'];

            foreach ($parts as $part) {
                if (is_array($value) && isset($value[$part])) {
                    $value = $value[$part];
                } else {
                    $value = '';

                    break;
                }
            }

            $values[$arguments['key']] = $value;
        }

        return $values[$arguments['key']];
    }
}
