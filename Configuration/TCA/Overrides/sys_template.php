<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

defined('TYPO3_MODE') || die();

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'tk_composer_server',
    'Configuration/TsConfig/Page/account.typoscript',
    'PageTS to allow only account entries'
);

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'tk_composer_server',
    'Configuration/TsConfig/Page/repositorygroup.typoscript',
    'PageTS to allow only repository group entries'
);

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'tk_composer_server',
    'Configuration/TsConfig/Page/repository.typoscript',
    'PageTS to allow only repository entries'
);
