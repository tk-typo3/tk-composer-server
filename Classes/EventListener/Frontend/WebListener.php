<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\EventListener\Frontend;

use TimonKreis\TkComposerServer\Domain\Model\Repository;
use TimonKreis\TkComposerServer\Domain\Repository\AccountRepository;
use TimonKreis\TkComposerServer\Domain\Repository\RepositoryRepository;
use TimonKreis\TkComposerServer\Service\AccountService;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @package TimonKreis\TkComposerServer\EventListener\Frontend
 */
class WebListener extends AbstractFrontendListener
{
    /**
     * @var AccountService
     */
    protected $accountService;

    /**
     * @var AccountRepository
     */
    protected $accountRepository;

    /**
     * @var RepositoryRepository
     */
    protected $repositoryRepository;

    /**
     * @param AccountService $accountService
     * @param AccountRepository $accountRepository
     * @param RepositoryRepository $repositoryRepository
     */
    public function __construct(
        AccountService $accountService,
        AccountRepository $accountRepository,
        RepositoryRepository $repositoryRepository
    ) {
        $this->accountService = $accountService;
        $this->accountRepository = $accountRepository;
        $this->repositoryRepository = $repositoryRepository;
    }

    /**
     *
     */
    protected function execute() : void
    {
        // Check if web frontend is enabled
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tk_composer_server']['frontend']['disable'])
            && $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tk_composer_server']['frontend']['disable']
        ) {
            return;
        }

        if ($this->frontendRequestEvent->getUri() == 'login' || $this->frontendRequestEvent->getUri() == 'logout') {
            $suffix = '';

            if ($this->frontendRequestEvent->getUri() == 'login') {
                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';

                if ($this->accountRepository->findByUsernameAndPassword($username, $password)) {
                    setcookie(
                        'auth',
                        $username . ':' . $this->accountService->getPasswordHashByPassword($password),
                        time() + 3600
                    );
                } else {
                    // Prevent brute-forcing
                    sleep(2);

                    $suffix = 'login-error';
                }
            } else {
                // Invalidate cookie
                setcookie('auth', '', time() - 86400);
            }

            header('Location: /' . ($suffix ? '?' . $suffix : ''), true, 302);

            exit;
        } elseif ($this->frontendRequestEvent->getUri() == '') {
            /** @var Site $site */
            $site = $this->frontendRequestEvent->getRequest()->getAttribute('site');

            // Initialize TypoScriptFrontendController
            $GLOBALS['TSFE'] = GeneralUtility::makeInstance(
                TypoScriptFrontendController::class,
                $GLOBALS['TYPO3_CONF_VARS'],
                $site,
                $site->getDefaultLanguage()
            );

            $account = $this->accountService->getAuthorizedAccount();
            $repositories = $this->repositoryRepository->findByAccount($account);
            $packages = [];

            /** @var Repository $repository */
            foreach ($repositories as $repository) {
                if (!$repository->getData()) {
                    continue;
                }

                foreach ($repository->getData()['packages'] as $packageVersions) {
                    foreach (array_reverse($packageVersions) as $version => $package) {
                        if (!isset($packages[$package['name']])) {
                            $packages[$package['name']] = [
                                'name' => $package['name'],
                                'url' => $repository->getUrl(),
                                'description' => $package['description'] ?? '',
                                'tag' => $version,
                            ];
                        }
                    }
                }
            }

            ksort($packages, SORT_STRING);

            /** @var StandaloneView $standaloneView */
            $standaloneView = GeneralUtility::makeInstance(StandaloneView::class);
            $standaloneView->setLayoutRootPaths(['EXT:tk_composer_server/Resources/Private/Layouts']);
            $standaloneView->setPartialRootPaths(['EXT:tk_composer_server/Resources/Private/Partials']);
            $standaloneView->setTemplatePathAndFilename('EXT:tk_composer_server/Resources/Private/Templates/Main.html');
            $standaloneView->assignMultiple([
                'packages' => array_values($packages),
                'isLoggedIn' => is_object($account),
                'loginError' => isset($_GET['login-error']),
            ]);

            $this->setHtmlResponse($standaloneView);
        }
    }
}
