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
class RepositoryGroup extends AbstractEntity
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     * @var string
     */
    protected $name = '';

    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TimonKreis\TkComposerServer\Domain\Model\Repository>
     */
    protected $repositories = null;

    /**
     * __construct
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
        $this->repositories = $this->repositories ?: new ObjectStorage();
    }

    /**
     * Returns the name
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     */
    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    /**
     * Adds a repository
     *
     * @param Repository $repository
     */
    public function addRepository(Repository $repository) : void
    {
        $this->repositories->attach($repository);
    }

    /**
     * Removes a repository
     *
     * @param Repository $repository
     */
    public function removeRepository(Repository $repository) : void
    {
        $this->repositories->detach($repository);
    }

    /**
     * Returns the repositories
     *
     * @return ObjectStorage<Repository>
     */
    public function getRepositories() : ObjectStorage
    {
        return $this->repositories;
    }

    /**
     * Sets the repositories
     *
     * @param ObjectStorage<Repository> $repositories
     */
    public function setRepositories(ObjectStorage $repositories) : void
    {
        $this->repositories = $repositories;
    }
}
