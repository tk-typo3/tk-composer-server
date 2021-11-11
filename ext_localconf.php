<?php
## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0 GNU Public License
 */
defined('TYPO3_MODE') || die();

(static function(string $_EXTKEY) : void {
    // Register password wizard
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1602960741] = [
        'nodeName' => 'passwordWizard',
        'priority' => 50,
        'class' => TimonKreis\TkComposerServer\FormEngine\FieldControl\PasswordElement::class,
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$_EXTKEY]
        = TimonKreis\TkComposerServer\Hooks\UpdateHook::class;

    $extconf = &$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tk_composer_server'];

    if (!isset($extconf['frontend']['disable'])) {
        $extconf['frontend']['disable'] = false;
    }

    if (!isset($extconf['frontend']['title'])) {
        $extconf['frontend']['title'] = 'Composer Server';
    }

    if (!isset($extconf['frontend']['footer']['copyrightNotice'])) {
        $extconf['frontend']['footer']['copyrightNotice'] = 'All rights reserved';
    }

    if (!isset($extconf['updateUri'])) {
        $extconf['updateUri'] = 'update';
    }

    if (!isset($extconf['hashingAlgorithm'])) {
        $extconf['hashingAlgorithm'] = 'sha256';
    }

    $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend']['backendLogo']
        = 'EXT:tk_composer_server/Resources/Public/Icons/logo-white.svg';
})('tk_composer_server');
