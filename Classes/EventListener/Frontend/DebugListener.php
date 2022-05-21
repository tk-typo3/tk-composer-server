<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\EventListener\Frontend;

use TimonKreis\TkComposerServer\Service\ExtconfService;
use TYPO3\CMS\Core\Core\Environment;

/**
 * @noinspection PhpUnused
 * @package TimonKreis\TkComposerServer\EventListener\Frontend
 */
class DebugListener extends AbstractFrontendListener
{
    /**
     *
     */
    protected function execute() : void
    {
        if (!ExtconfService::get('debugUri')
            || $this->frontendRequestEvent->getUri() !== ExtconfService::get('debugUri')
        ) {
            return;
        }

        $data = [
            '$_GET' => $_GET ?? [],
            '$_POST' => $_POST ?? [],
            'server' => $this->frontendRequestEvent->getRequest()->getServerParams(),
            'headers' => $this->frontendRequestEvent->getRequest()->getHeaders(),
            'body' => json_decode($this->frontendRequestEvent->getRequest()->getBody()->getContents(), true),
        ];

        $file = Environment::getVarPath() . '/log/composer-server-debug.json';
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        if (@file_put_contents($file, $json)) {
            $this->setJsonResponse(['status' => 'ok']);
        } else {
            $this->setJsonResponse(['status' => 'error'], 400);
        }
    }
}
