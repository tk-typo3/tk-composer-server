<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\Domain\Repository;

use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\ParameterType;
use TimonKreis\TkComposerServer\Domain\Model\Account;
use TimonKreis\TkComposerServer\Domain\Model\Repository;
use TimonKreis\TkComposerServer\Domain\Model\RepositoryGroup;

/**
 * @noinspection PhpUnused
 * @package TimonKreis\TkComposerServer\Domain\Repository
 */
class RepositoryRepository extends AbstractRepository
{
    public const TABLE = 'tx_tkcomposerserver_domain_model_repository';

    /**
     * Find packages by account
     *
     * @param Account|null $account
     * @return Repository[]
     * @throws Exception
     */
    public function findByAccount(Account $account = null) : array
    {
        $queryBuilder = $this->getQueryBuilder();
        $query = $queryBuilder->select('*');

        if ($account) {
            if (!$account->getAllRepositories()) {
                $query->where(
                    $queryBuilder->expr()->eq(
                        'access',
                        $queryBuilder->createNamedParameter(Repository::ACCESS_PROTECTED, ParameterType::INTEGER)
                    )
                )->orWhere(
                    $queryBuilder->expr()->eq(
                        'access',
                        $queryBuilder->createNamedParameter(Repository::ACCESS_PUBLIC, ParameterType::INTEGER)
                    )
                );

                $repositoryUids = [];

                /** @var RepositoryGroup $repositoryGroup */
                foreach ($account->getRepositoryGroups() as $repositoryGroup) {
                    /** @var Repository $repository */
                    foreach ($repositoryGroup->getRepositories() as $repository) {
                        $uid = $repository->getUid();
                        $repositoryUids[$uid] = $queryBuilder->createNamedParameter($uid, ParameterType::INTEGER);
                    }
                }

                /** @var Repository $repository */
                foreach ($account->getRepositories() as $repository) {
                    $uid = $repository->getUid();
                    $repositoryUids[$uid] = $queryBuilder->createNamedParameter($uid, ParameterType::INTEGER);
                }

                if ($repositoryUids) {
                    $query->orWhere($queryBuilder->expr()->in('uid', array_values($repositoryUids)));
                }
            }
        } else {
            $query->where(
                $queryBuilder->expr()->eq(
                    'access',
                    $queryBuilder->createNamedParameter(Repository::ACCESS_PUBLIC, ParameterType::INTEGER)
                )
            );
        }

        return $this->getMappedData(Repository::class, $query->execute()->fetchAllAssociative());
    }

    /**
     * Find repository by package name
     *
     * @param string $packageName
     * @return Repository|null
     * @throws Exception
     */
    public function findByPackageName(string $packageName) : ?Repository
    {
        $queryBuilder = $this->getQueryBuilder();

        $query = $queryBuilder
            ->select('*')
            ->where($queryBuilder->expr()->eq('package_name', $queryBuilder->createNamedParameter($packageName)))
            ->setMaxResults(1);

        $repository = $this->getMappedData(Repository::class, $query->execute()->fetchAllAssociative());

        return $repository[0] ?? null;
    }

    /**
     * Find repository by URL
     *
     * @param string $url
     * @return Repository|null
     * @throws Exception
     */
    public function findByUrl(string $url) : ?Repository
    {
        $queryBuilder = $this->getQueryBuilder();

        $query = $queryBuilder
            ->select('*')
            ->where($queryBuilder->expr()->eq('url', $queryBuilder->createNamedParameter($url)));

        $repository = $this->getMappedData(Repository::class, $query->execute()->fetchAllAssociative());

        return $repository[0] ?? null;
    }
}
