<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @copyright by Timon Kreis - All rights reserved
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

use TimonKreis\TkComposerServer\Domain\Model\Repository;
use TimonKreis\TkComposerServer\Tools\TCA\FieldsGroup;
use TimonKreis\TkComposerServer\Userfuncs\Tca;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3_MODE') || die();

$tca = &$GLOBALS['TCA']['tx_tkcomposerserver_domain_model_repository'];

// https://fonts.google.com/icons?selected=Material+Icons:archive
$tca['ctrl']['iconfile']
    = 'EXT:tk_composer_server/Resources/Public/Icons/tx_tkcomposerserver_domain_model_repository.svg';

// Sort entries
$tca['ctrl']['default_sortby'] = 'package_name ASC';

// Label
$tca['ctrl']['label_userFunc'] = Tca::class . '->repositoryLabel';

$tca['ctrl']['searchFields'] = GeneralUtility::rmFromList('hash', $tca['ctrl']['searchFields']);
$tca['ctrl']['searchFields'] = GeneralUtility::rmFromList('checksum', $tca['ctrl']['searchFields']);
$tca['ctrl']['searchFields'] = GeneralUtility::rmFromList('data', $tca['ctrl']['searchFields']);

// hidden
unset($tca['columns']['hidden']['config']['renderType']);

// package_name
$tca['columns']['package_name']['config']['type'] = 'passthrough';

// url
$tca['columns']['url']['config']['size'] = 50;
$tca['columns']['url']['config']['eval'] .= ',unique';

// type
$tca['columns']['type']['config']['items'] = [
    [
        'LLL:EXT:tk_composer_server/Resources/Private/Language/locallang.xlf:Type.Git',
        Repository::TYPE_GIT,
    ],
];

// access
$tca['columns']['access']['config']['default'] = Repository::ACCESS_PRIVATE;
$tca['columns']['access']['onChange'] = 'reload';
$tca['columns']['access']['config']['items'] = [
    [
        'LLL:EXT:tk_composer_server/Resources/Private/Language/locallang.xlf:Access.Private',
        Repository::ACCESS_PRIVATE,
    ],
    [
        'LLL:EXT:tk_composer_server/Resources/Private/Language/locallang.xlf:Access.Protected',
        Repository::ACCESS_PROTECTED,
    ],
    [
        'LLL:EXT:tk_composer_server/Resources/Private/Language/locallang.xlf:Access.Public',
        Repository::ACCESS_PUBLIC,
    ],
];

// accounts
$tca['columns']['accounts'] = [
    'exclude' => true,
    'label' => 'LLL:EXT:tk_composer_server/Resources/Private/Language/locallang_db.xlf:tx_tkcomposerserver_domain_model_repository.accounts',
    'displayCond' => 'FIELD:access:=:' . Repository::ACCESS_PRIVATE,
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
$tca['types']['1']['showitem'] = str_replace(' access,', ' access, accounts,', $tca['types']['1']['showitem']);

// repository_groups
$tca['columns']['repository_groups'] = [
    'exclude' => true,
    'label' => 'LLL:EXT:tk_composer_server/Resources/Private/Language/locallang_db.xlf:tx_tkcomposerserver_domain_model_repository.repository_groups',
    'displayCond' => 'FIELD:access:=:' . Repository::ACCESS_PRIVATE,
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
$tca['types']['1']['showitem'] = str_replace(' access,', ' access, repository_groups,', $tca['types']['1']['showitem']);

// hash
$tca['columns']['hash']['config']['type'] = 'passthrough';

// checksum
$tca['columns']['checksum']['config']['type'] = 'passthrough';

// data
$tca['columns']['data']['config']['type'] = 'passthrough';

FieldsGroup::group($tca, ['url', 'hidden']);
FieldsGroup::group($tca, ['type', 'access']);
FieldsGroup::group($tca, ['starttime', 'endtime']);
