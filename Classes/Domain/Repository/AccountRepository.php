<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\Domain\Repository;

use Doctrine\DBAL\Driver\Exception;
use TimonKreis\TkComposerServer\Domain\Model\Account;
use TimonKreis\TkComposerServer\Service\AccountService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @noinspection PhpUnused
 * @package TimonKreis\TkComposerServer\Domain\Repository
 */
class AccountRepository extends AbstractRepository
{
    public const TABLE = 'tx_tkcomposerserver_domain_model_account';

    /**
     * Find by username and password
     *
     * @param string $username
     * @param string $password
     * @return Account|null
     * @throws Exception
     */
    public function findByUsernameAndPassword(string $username, string $password) : ?Account
    {
        $queryBuilder = $this->getQueryBuilder();

        $query = $queryBuilder
            ->select('*')
            ->where($queryBuilder->expr()->eq('username', $queryBuilder->createNamedParameter($username)))
            ->andWhere($queryBuilder->expr()->eq('password', $queryBuilder->createNamedParameter($password)))
            ->setMaxResults(1);

        $account = $this->getMappedData(Account::class, $query->execute()->fetchAllAssociative());

        return $account[0] ?? null;
    }

    /**
     * Find by username and password hash
     *
     * @param string $username
     * @param string $passwordHash
     * @return Account|null
     * @throws Exception
     */
    public function findByUsernameAndPasswordHash(string $username, string $passwordHash) : ?Account
    {
        $queryBuilder = $this->getQueryBuilder();

        $query = $queryBuilder
            ->select('*')
            ->where($queryBuilder->expr()->eq('username', $queryBuilder->createNamedParameter($username)))
            ->setMaxResults(1);

        $account = $this->getMappedData(Account::class, $query->execute()->fetchAllAssociative());

        if (!$account) {
            return null;
        }

        /** @var AccountService $accountService */
        $accountService = GeneralUtility::makeInstance(AccountService::class);

        /** @var Account $account */
        $account = $account[0];

        return $accountService->getPasswordHashByPassword($account->getPassword()) === $passwordHash ? $account : null;
    }
}
