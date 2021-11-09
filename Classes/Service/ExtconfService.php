<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\Service;

/**
 * @package TimonKreis\TkComposerServer\Service
 */
class ExtconfService
{
    /**
     * @param string $key
     * @return mixed|string
     */
    public static function get(string $key)
    {
        static $values = [];

        if (!array_key_exists($key, $values)) {
            $parts = explode('/', $key);
            $value = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tk_composer_server'];

            foreach ($parts as $part) {
                if (is_array($value) && isset($value[$part])) {
                    $value = $value[$part];
                } else {
                    $value = '';

                    break;
                }
            }

            $values[$key] = $value;
        }

        return $values[$key];
    }
}
