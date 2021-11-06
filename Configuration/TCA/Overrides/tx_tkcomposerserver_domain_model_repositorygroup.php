<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @copyright by Timon Kreis - All rights reserved
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

defined('TYPO3_MODE') || die();

$tca = &$GLOBALS['TCA']['tx_tkcomposerserver_domain_model_repositorygroup'];

// https://fonts.google.com/icons?selected=Material+Icons:group_work
$tca['ctrl']['iconfile'] = 'EXT:tk_composer_server/Resources/Public/Icons/tx_tkcomposerserver_domain_model_repositorygroup.svg';

// Sort entries
$tca['ctrl']['default_sortby'] = 'name ASC';

// hidden
unset($tca['columns']['hidden']['config']['renderType']);

// name
$tca['columns']['name']['config']['size'] = 40;

// repositories
$tca['columns']['repositories']['config']['foreign_table_where']
    = 'access = ' . TimonKreis\TkComposerServer\Domain\Model\Repository::ACCESS_PRIVATE;
unset($tca['columns']['repositories']['config']['size']);

// accounts
$tca['columns']['accounts'] = [
    'exclude' => true,
    'label' => 'LLL:EXT:tk_composer_server/Resources/Private/Language/locallang_db.xlf:tx_tkcomposerserver_domain_model_repositorygroup.accounts',
    'config' => [
        'type' => 'select',
        'renderType' => 'selectMultipleSideBySide',
        'foreign_table' => 'tx_tkcomposerserver_domain_model_account',
        'foreign_table_where' => 'all_repositories = 0',
        'MM' => 'tx_tkcomposerserver_account_repositorygroup_mm',
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
$tca['interface']['showRecordFieldList'] .= ', accounts';
$tca['types']['1']['showitem'] = str_replace('hidden, ', 'hidden,  accounts,', $tca['types']['1']['showitem']);

// repositories
$tca['columns']['repositories']['config']['fieldControl']['addRecord']['disabled'] = true;
$tca['columns']['repositories']['config']['fieldControl']['editPopup']['disabled'] = true;

TimonKreis\TkComposerServer\Tools\TCA\FieldsGroup::group($tca, ['name', 'hidden']);
TimonKreis\TkComposerServer\Tools\TCA\FieldsGroup::group($tca, ['starttime', 'endtime']);
