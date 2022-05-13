<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\EventListener\Frontend;

/**
 * @noinspection PhpUnused
 * @package TimonKreis\TkComposerServer\EventListener\Frontend
 */
class RobotsTxtListener extends AbstractFrontendListener
{
    /**
     *
     */
    protected function execute() : void
    {
        if ($this->frontendRequestEvent->getUri() !== 'robots.txt') {
            return;
        }

        $this->setPlainResponse([
            'User-agent: *',
            'Disallow: /',
        ]);
    }
}
