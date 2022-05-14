<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TimonKreis\TkComposerServer\Domain\Model\Account;
use TimonKreis\TkComposerServer\Domain\Repository\AccountRepository;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @package TimonKreis\TkComposerServer\Controller
 */
class BackendController
{
    /**
     * @var AccountRepository
     */
    protected $accountRepository;

    /**
     *
     */
    public function __construct()
    {
        $this->accountRepository = GeneralUtility::makeInstance(AccountRepository::class);
    }

    /**
     * @noinspection PhpUnused
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Exception
     */
    public function downloadAuthJsonAction(ServerRequestInterface $request) : ResponseInterface
    {
        $account = $this->getAccountByRequest($request);

        $json = [
            'http-basic' => [
                $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tk_composer_server']['hostname'] ?? $_SERVER['HTTP_HOST'] => [
                    'username' => $account->getUsername(),
                    'password' => $account->getPassword(),
                ],
            ],
        ];

        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="auth.json"');
        header('Pragma: no-cache');

        return new HtmlResponse(json_encode($json, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    /**
     * @param ServerRequestInterface $request
     * @return Account
     * @throws \Exception
     */
    protected function getAccountByRequest(ServerRequestInterface $request) : Account
    {
        $accountUid = (int)($request->getQueryParams()['account'] ?? 0);

        if (!$accountUid || !preg_match('/^\d+$/', (string)$accountUid)) {
            throw new \InvalidArgumentException('Account UID is invalid');
        }

        /** @var Account $account */
        $account = $this->accountRepository->findByUid($accountUid);

        if (!$account) {
            throw new \RuntimeException(sprintf('No account found for UID "%d"', $accountUid));
        }

        return $account;
    }
}
