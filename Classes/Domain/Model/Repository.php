<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * @package TimonKreis\TkComposerServer\Domain\Model
 */
class Repository extends AbstractEntity
{
    public const ACCESS_PRIVATE = 1;
    public const ACCESS_PROTECTED = 2;
    public const ACCESS_PUBLIC = 3;

    public const TYPE_GIT = 1;

    public const TYPE_MAPPINGS = [
        self::TYPE_GIT => 'vcs',
    ];

    /**
     * @var string
     */
    protected $packageName = '';

    /**
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     * @var string
     */
    protected $url = '';

    /**
     * @var int
     */
    protected $type = 0;

    /**
     * @var int
     */
    protected $access = 0;

    /**
     * @var string
     */
    protected $hash = '';

    /**
     * @var string
     */
    protected $checksum = '';

    /**
     * @var string
     */
    protected $data = '';

    /**
     * Returns the package name
     *
     * @return string
     */
    public function getPackageName() : string
    {
        return $this->packageName;
    }

    /**
     * Sets the package name
     *
     * @param string $packageName
     */
    public function setPackageName(string $packageName) : void
    {
        $this->packageName = $packageName;
    }

    /**
     * Returns the url
     *
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }

    /**
     * Sets the url
     *
     * @param string $url
     */
    public function setUrl(string $url) : void
    {
        $this->url = $url;
    }

    /**
     * Returns the type
     *
     * @return int
     */
    public function getType() : int
    {
        return $this->type;
    }

    /**
     * Sets the type
     *
     * @param int $type
     */
    public function setType(int $type) : void
    {
        $this->type = $type;
    }

    /**
     * Returns the access
     *
     * @return int
     */
    public function getAccess() : int
    {
        return $this->access;
    }

    /**
     * Sets the access
     *
     * @param int $access
     */
    public function setAccess(int $access) : void
    {
        $this->access = $access;
    }

    /**
     * Returns the hash
     *
     * @return string
     */
    public function getHash() : string
    {
        return $this->hash;
    }

    /**
     * Sets the hash
     *
     * @param string $hash
     */
    public function setHash(string $hash) : void
    {
        $this->hash = $hash;
    }

    /**
     * Returns the checksum
     *
     * @return string
     */
    public function getChecksum() : string
    {
        return $this->checksum;
    }

    /**
     * Sets the checksum
     *
     * @param string $checksum
     */
    public function setChecksum(string $checksum) : void
    {
        $this->checksum = $checksum;
    }

    /**
     * Returns the data
     *
     * @return array $data
     */
    public function getData() : array
    {
        return json_decode($this->data, true);
    }

    /**
     * Sets the data
     *
     * @param string $data
     */
    public function setData(array $data) : void
    {
        $this->data = json_encode($data, JSON_UNESCAPED_SLASHES);
        $this->checksum = hash('sha256', $this->data);
    }
}
