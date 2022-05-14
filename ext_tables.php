<?php
defined('TYPO3_MODE') || die();

call_user_func(static function() {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_tkcomposerserver_domain_model_account', 'EXT:tk_composer_server/Resources/Private/Language/locallang_csh_tx_tkcomposerserver_domain_model_account.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_tkcomposerserver_domain_model_account');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_tkcomposerserver_domain_model_repository', 'EXT:tk_composer_server/Resources/Private/Language/locallang_csh_tx_tkcomposerserver_domain_model_repository.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_tkcomposerserver_domain_model_repository');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_tkcomposerserver_domain_model_repositorygroup', 'EXT:tk_composer_server/Resources/Private/Language/locallang_csh_tx_tkcomposerserver_domain_model_repositorygroup.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_tkcomposerserver_domain_model_repositorygroup');
});
## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder
(static function(string $_EXTKEY) : void {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.db_list_extra.inc']['actions'][]
        = TimonKreis\TkComposerServer\Hooks\CustomControls::class;
})('tk_composer_server');
