<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\EventListener\Frontend\Platforms;

/**
 * @noinspection PhpUnused
 * @package TimonKreis\TkComposerServer\EventListener\Frontend\Update
 */
class GiteaListener extends AbstractPlatformListener
{
    /**
     *
     */
    protected function execute() : void
    {
        // Body
        if (isset($this->getBody()['repository']['ssh_url'])) {
            $this->urls[] = $this->getBody()['repository']['ssh_url'];
        }

        if (isset($this->getBody()['repository']['html_url'])) {
            $this->urls[] = $this->getBody()['repository']['html_url'];
        }

        // GET or POST
        if (isset($this->getGet()['payload']) || isset($this->getPost()['payload'])) {
            $payload = json_decode($this->getGet()['payload'] ?? $this->getPost()['payload'], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return;
            }

            if (isset($payload['repository']['ssh_url'])) {
                $this->urls[] = $payload['repository']['ssh_url'];
            }

            if (isset($payload['repository']['html_url'])) {
                $this->urls[] = $payload['repository']['html_url'];
            }
        }
    }
}
