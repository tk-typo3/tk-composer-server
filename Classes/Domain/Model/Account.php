<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * @package TimonKreis\TkComposerServer\Domain\Model
 */
class Account extends AbstractEntity
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     * @var string
     */
    protected $username = '';

    /**
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     * @var string
     */
    protected $password = '';

    /**
     * @var bool
     */
    protected $allRepositories = false;

    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var ObjectStorage<RepositoryGroup>
     */
    protected $repositoryGroups;

    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var ObjectStorage<Repository>
     */
    protected $repositories;

    /**
     *
     */
    public function __construct()
    {
        // Do not remove the next line: It would break the functionality
        $this->initializeObject();
    }

    /**
     * Initializes all ObjectStorage properties when model is reconstructed from DB (where __construct is not called)
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     */
    public function initializeObject() : void
    {
        $this->repositoryGroups = $this->repositoryGroups ?: new ObjectStorage();
        $this->repositories = $this->repositories ?: new ObjectStorage();
    }

    /**
     * Returns the username
     *
     * @noinspection PhpUnused
     * @return string
     */
    public function getUsername() : string
    {
        return $this->username;
    }

    /**
     * Sets the username
     *
     * @noinspection PhpUnused
     * @param string $username
     */
    public function setUsername(string $username) : void
    {
        $this->username = $username;
    }

    /**
     * Returns the password
     *
     * @noinspection PhpUnused
     * @return string
     */
    public function getPassword() : string
    {
        return $this->password;
    }

    /**
     * Sets the password
     *
     * @noinspection PhpUnused
     * @param string $password
     */
    public function setPassword(string $password) : void
    {
        $this->password = $password;
    }

    /**
     * Returns the allRepositories flag
     *
     * @noinspection PhpUnused
     * @return bool
     */
    public function getAllRepositories() : bool
    {
        return $this->allRepositories;
    }

    /**
     * Sets the allRepositories flag
     *
     * @noinspection PhpUnused
     * @param bool $allRepositories
     */
    public function setAllRepositories(bool $allRepositories) : void
    {
        $this->allRepositories = $allRepositories;
    }

    /**
     * Returns the boolean state of allRepositories
     *
     * @noinspection PhpUnused
     * @return bool
     */
    public function isAllRepositories() : bool
    {
        return $this->allRepositories;
    }

    /**
     * Adds a repository group
     *
     * @noinspection PhpUnused
     * @param RepositoryGroup $repositoryGroup
     */
    public function addRepositoryGroup(RepositoryGroup $repositoryGroup) : void
    {
        $this->repositoryGroups->attach($repositoryGroup);
    }

    /**
     * Removes a repository group
     *
     * @noinspection PhpUnused
     * @param RepositoryGroup $repository
     */
    public function removeRepositoryGroup(RepositoryGroup $repository) : void
    {
        $this->repositoryGroups->detach($repository);
    }

    /**
     * Returns the repository groups
     *
     * @noinspection PhpUnused
     * @return ObjectStorage<RepositoryGroup>
     */
    public function getRepositoryGroups() : ObjectStorage
    {
        return $this->repositoryGroups;
    }

    /**
     * Sets the repository groups
     *
     * @noinspection PhpUnused
     * @param ObjectStorage<RepositoryGroup> $repositoryGroups
     */
    public function setRepositoryGroups(ObjectStorage $repositoryGroups) : void
    {
        $this->repositoryGroups = $repositoryGroups;
    }

    /**
     * Adds a repository
     *
     * @noinspection PhpUnused
     * @param Repository $repository
     */
    public function addRepository(Repository $repository) : void
    {
        $this->repositories->attach($repository);
    }

    /**
     * Removes a repository
     *
     * @noinspection PhpUnused
     * @param Repository $repository
     */
    public function removeRepository(Repository $repository) : void
    {
        $this->repositories->detach($repository);
    }

    /**
     * Returns the repositories
     *
     * @noinspection PhpUnused
     * @return ObjectStorage<Repository>
     */
    public function getRepositories() : ObjectStorage
    {
        return $this->repositories;
    }

    /**
     * Sets the repositories
     *
     * @noinspection PhpUnused
     * @param ObjectStorage<Repository> $repositories
     */
    public function setRepositories(ObjectStorage $repositories) : void
    {
        $this->repositories = $repositories;
    }
}
