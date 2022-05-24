<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\EventListener\Frontend\Platforms;

/**
 * @noinspection PhpUnused
 * @package TimonKreis\TkComposerServer\EventListener\Frontend\Platforms
 */
class GitLabListener extends AbstractPlatformListener
{
    /**
     *
     */
    protected function execute() : void
    {
        if (isset($this->getBody()['repository']['git_ssh_url'])) {
            $this->urls[] = $this->getBody()['repository']['git_ssh_url'];
        }

        if (isset($this->getBody()['repository']['git_http_url'])) {
            $this->urls[] = $this->getBody()['repository']['git_http_url'];
        }
    }
}
