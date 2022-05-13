<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\Service;

use Composer\Factory;
use Composer\IO\NullIO;
use Composer\Package\AliasPackage;
use Composer\Package\CompletePackage;
use Composer\Package\Dumper\ArrayDumper;
use Composer\Repository\VcsRepository;
use Composer\Util\HttpDownloader;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TimonKreis\TkComposerServer\Domain\Model\Repository;
use TimonKreis\TkComposerServer\Domain\Repository\RepositoryRepository;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;

class UpdateService implements SingletonInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @var RepositoryRepository
     */
    protected $repositoryRepository;

    /**
     * @param PersistenceManagerInterface $persistenceManager
     * @param RepositoryRepository $repositoryRepository
     */
    public function __construct(
        PersistenceManagerInterface $persistenceManager,
        RepositoryRepository $repositoryRepository
    ) {
        $this->persistenceManager = $persistenceManager;
        $this->repositoryRepository = $repositoryRepository;
    }

    /**
     * Update all repositories
     *
     * @param bool $forceFullReload
     * @return array
     */
    public function updateAllRepositories(bool $forceFullReload = false) : array
    {
        $errors = [];
        $repositories = $this->repositoryRepository->findAll();

        /** @var Repository $repository */
        foreach ($repositories as $repository) {
            try {
                $this->updateRepository($repository, $forceFullReload);
            } catch (\Exception $e) {
                $errors[] = [
                    'repository' => $repository,
                    'exception' => $e,
                ];
            }
        }

        return $errors;
    }

    /**
     * Update repository
     *
     * @param Repository $repository
     * @param bool $forceReload
     * @throws \Exception
     */
    public function updateRepository(Repository $repository, bool $forceReload = false) : void
    {
        $repoConfig = [
            'url' => $repository->getUrl(),
            'type' => Repository::TYPE_MAPPINGS[$repository->getType()],
        ];

        $config = Factory::createConfig();
        $config->merge([
            'config' => [
                'cache-dir' => Environment::getVarPath() . '/composer',
            ],
        ]);

        $nullIo = new NullIO();
        $nullIo->loadConfiguration($config);
        $downloader = new HttpDownloader($nullIo, $config);
        $vcsRepository = new VcsRepository($repoConfig, $nullIo, $config, $downloader);

        try {
            $driver = $vcsRepository->getDriver();
            $packages = $vcsRepository->getPackages();

            if ($driver === null) {
                throw new \RuntimeException('Unable to determine the driver');
            }

            if ($packages === null) {
                throw new \RuntimeException('Unable to determine the packages');
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());

            throw $e;
        }

        $hash = md5(json_encode([$driver->getBranches(), $driver->getTags()]));

        // Check if reload is required
        if (!$forceReload && $repository->getHash() === $hash) {
            return;
        }

        $arrayDumper = new ArrayDumper();
        $collection = [
            'packages' => [],
        ];

        /** @var CompletePackage $package */
        foreach ($packages as $package) {
            // Skip alias packages
            if ($package instanceof AliasPackage) {
                continue;
            }

            if (!isset($collection['packages'][$package->getName()])) {
                $collection['packages'][$package->getName()] = [];

                $repository->setPackageName($package->getName());
            }

            $collection['packages'][$package->getName()][$package->getPrettyVersion()] = $arrayDumper->dump($package);
        }

        $repository->setHash($hash);
        $repository->setData($collection);

        $this->repositoryRepository->update($repository);
        $this->persistenceManager->persistAll();
    }
}
