<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @copyright by Timon Kreis - All rights reserved
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

use TimonKreis\TkComposerServer\Domain\Model\Repository;
use TimonKreis\TkComposerServer\Tools\TCA\FieldsGroup;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3_MODE') || die();

$tca = &$GLOBALS['TCA']['tx_tkcomposerserver_domain_model_account'];

// https://fonts.google.com/icons?selected=Material+Icons:account_box
$tca['ctrl']['iconfile'] = 'EXT:tk_composer_server/Resources/Public/Icons/tx_tkcomposerserver_domain_model_account.svg';

// Sort entries
$tca['ctrl']['default_sortby'] = 'username ASC';

// username
$tca['columns']['username']['config']['size'] = 50;

// hidden
unset($tca['columns']['hidden']['config']['renderType']);

// password
$tca['columns']['password']['config']['renderType'] = 'passwordWizard';

// all_repositories
$tca['columns']['all_repositories']['onChange'] = 'reload';
unset($tca['columns']['all_repositories']['config']['renderType']);

// repository_groups
$tca['columns']['repository_groups']['displayCond'] = 'FIELD:all_repositories:=:0';
$tca['columns']['repository_groups']['config']['fieldControl']['addRecord']['disabled'] = true;
$tca['columns']['repository_groups']['config']['fieldControl']['editPopup']['disabled'] = true;
unset($tca['columns']['repository_groups']['config']['size']);

// repositories
$tca['columns']['repositories']['displayCond'] = 'FIELD:all_repositories:=:0';
$tca['columns']['repositories']['config']['fieldControl']['addRecord']['disabled'] = true;
$tca['columns']['repositories']['config']['fieldControl']['editPopup']['disabled'] = true;
$tca['columns']['repositories']['config']['foreign_table_where'] = 'access = ' . Repository::ACCESS_PRIVATE;
unset($tca['columns']['repositories']['config']['size']);

FieldsGroup::group($tca, ['username', 'hidden']);
FieldsGroup::group($tca, ['password', 'all_repositories']);
FieldsGroup::group($tca, ['starttime', 'endtime']);
