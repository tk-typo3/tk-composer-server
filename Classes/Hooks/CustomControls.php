<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\Hooks;

use TimonKreis\TkComposerServer\Domain\Repository\AccountRepository;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Recordlist\RecordList\RecordListHookInterface;

/**
 * @noinspection PhpUnused
 * @package TimonKreis\TkComposresultserServer\Hooks
 */
class CustomControls implements RecordListHookInterface
{
    /**
     * @param string $table
     * @param array $row
     * @param array $cells
     * @param object $parentObject
     * @return array
     */
    public function makeClip($table, $row, $cells, &$parentObject) : array
    {
        return $cells;
    }

    /**
     * @param string $table
     * @param array $row
     * @param array $cells
     * @param object $parentObject
     * @return array
     * @throws \Exception
     */
    public function makeControl($table, $row, $cells, &$parentObject) : array
    {
        if ($table === AccountRepository::TABLE) {
            $additionalCells = [];

            /** @var UriBuilder $uriBuilder */
            $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);

            $uri = $uriBuilder->buildUriFromRoutePath('/account/downloadAuthJson', ['account' => $row['uid']]);
            $iconUrl = PathUtility::getAbsoluteWebPath(ExtensionManagementUtility::extPath('tk_composer_server'))
                       . 'Resources/Public/Icons/DownloadAuthJson.svg';

            $title = LocalizationUtility::translate(
                'LLL:EXT:tk_composer_server/Resources/Private/Language/locallang.xlf:DownloadAuthJson'
            );

            $additionalCells['downloadAuth'] = <<<HTML
<a class="btn btn-default" href="$uri" title="$title">
    <span class="t3js-icon icon icon-size-small icon-state-default">
        <span class="icon-markup">
            <img alt="" src="$iconUrl" width="16" height="16" />
        </span>
    </span>
</a>
HTML;

            $cells['primary'] = array_merge($additionalCells, $cells['primary']);
        }

        return $cells;
    }

    /**
     * @param string $table
     * @param array $currentIdList
     * @param array $headerColumns
     * @param object $parentObject
     * @return array
     */
    public function renderListHeader($table, $currentIdList, $headerColumns, &$parentObject) : array
    {
        return $headerColumns;
    }

    /**
     * @param string $table
     * @param array $currentIdList
     * @param array $cells
     * @param object $parentObject
     * @return array
     */
    public function renderListHeaderActions($table, $currentIdList, $cells, &$parentObject) : array
    {
        return $cells;
    }
}
