<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @copyright by Timon Kreis - All rights reserved
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

defined('TYPO3_MODE') || die();

$tca = &$GLOBALS['TCA']['tx_tkcomposerserver_domain_model_repository'];

// https://fonts.google.com/icons?selected=Material+Icons:archive
$tca['ctrl']['iconfile']
    = 'EXT:tk_composer_server/Resources/Public/Icons/tx_tkcomposerserver_domain_model_repository.svg';

// Sort entries
$tca['ctrl']['default_sortby'] = 'url ASC';

// Label
$tca['ctrl']['label_userFunc'] = TimonKreis\TkComposerServer\Userfuncs\Tca::class . '->repositoryLabel';

TYPO3\CMS\Core\Utility\GeneralUtility::rmFromList('hash', $tca['ctrl']['searchFields']);
TYPO3\CMS\Core\Utility\GeneralUtility::rmFromList('checksum', $tca['ctrl']['searchFields']);
TYPO3\CMS\Core\Utility\GeneralUtility::rmFromList('data', $tca['ctrl']['searchFields']);

// hidden
unset($tca['columns']['hidden']['config']['renderType']);

// package_name
$tca['columns']['package_name']['config']['type'] = 'passthrough';

// url
$tca['columns']['url']['config']['size'] = 50;
$tca['columns']['url']['config']['eval'] = 'unique';

// type
$tca['columns']['type']['config']['items'] = [
    [
        'LLL:EXT:tk_composer_server/Resources/Private/Language/locallang.xlf:Type.Git',
        TimonKreis\TkComposerServer\Domain\Model\Repository::TYPE_GIT,
    ],
];

// access
$tca['columns']['access']['config']['default'] = TimonKreis\TkComposerServer\Domain\Model\Repository::ACCESS_PRIVATE;
$tca['columns']['access']['onChange'] = 'reload';
$tca['columns']['access']['config']['items'] = [
    [
        'LLL:EXT:tk_composer_server/Resources/Private/Language/locallang.xlf:Access.Private',
        TimonKreis\TkComposerServer\Domain\Model\Repository::ACCESS_PRIVATE,
    ],
    [
        'LLL:EXT:tk_composer_server/Resources/Private/Language/locallang.xlf:Access.Protected',
        TimonKreis\TkComposerServer\Domain\Model\Repository::ACCESS_PROTECTED,
    ],
    [
        'LLL:EXT:tk_composer_server/Resources/Private/Language/locallang.xlf:Access.Public',
        TimonKreis\TkComposerServer\Domain\Model\Repository::ACCESS_PUBLIC,
    ],
];

// repository_groups
$tca['columns']['repository_groups'] = [
    'exclude' => true,
    'label' => 'LLL:EXT:tk_composer_server/Resources/Private/Language/locallang_db.xlf:tx_tkcomposerserver_domain_model_repository.repository_groups',
    'displayCond' => 'FIELD:access:=:' . TimonKreis\TkComposerServer\Domain\Model\Repository::ACCESS_PRIVATE,
    'config' => [
        'type' => 'select',
        'renderType' => 'selectMultipleSideBySide',
        'foreign_table' => 'tx_tkcomposerserver_domain_model_repositorygroup',
        'MM' => 'tx_tkcomposerserver_repositorygroup_repository_mm',
        'MM_opposite_field' => 'repositories',
        'autoSizeMax' => 30,
        'maxitems' => 9999,
        'multiple' => 0,
        'fieldControl' => [
            'addRecord' => [
                'disabled' => true,
            ],
            'listModule' => [
                'disabled' => true,
            ],
        ],
    ],
];
$tca['interface']['showRecordFieldList'] .= ', repository_groups';
$tca['types']['1']['showitem'] = str_replace('access,', 'access,repository_groups,', $tca['types']['1']['showitem']);

// accounts
$tca['columns']['accounts'] = [
    'exclude' => true,
    'label' => 'LLL:EXT:tk_composer_server/Resources/Private/Language/locallang_db.xlf:tx_tkcomposerserver_domain_model_repository.accounts',
    'displayCond' => 'FIELD:access:=:' . TimonKreis\TkComposerServer\Domain\Model\Repository::ACCESS_PRIVATE,
    'config' => [
        'type' => 'select',
        'renderType' => 'selectMultipleSideBySide',
        'foreign_table' => 'tx_tkcomposerserver_domain_model_account',
        'foreign_table_where' => 'all_repositories = 0',
        'MM' => 'tx_tkcomposerserver_account_repository_mm',
        'MM_opposite_field' => 'repositories',
        'autoSizeMax' => 30,
        'maxitems' => 9999,
        'multiple' => 0,
        'fieldControl' => [
            'addRecord' => [
                'disabled' => true,
            ],
            'listModule' => [
                'disabled' => true,
            ],
        ],
    ],
];
$tca['interface']['showRecordFieldList'] .= ',accounts';
$tca['types']['1']['showitem'] = str_replace('access,', 'access,accounts,', $tca['types']['1']['showitem']);

// hash
$tca['columns']['hash']['config']['type'] = 'passthrough';

// checksum
$tca['columns']['checksum']['config']['type'] = 'passthrough';

// data
$tca['columns']['data']['config']['type'] = 'passthrough';

TimonKreis\TkComposerServer\Tools\TCA\FieldsGroup::group($tca, ['url', 'hidden']);
TimonKreis\TkComposerServer\Tools\TCA\FieldsGroup::group($tca, ['type', 'access']);
TimonKreis\TkComposerServer\Tools\TCA\FieldsGroup::group($tca, ['starttime', 'endtime']);
