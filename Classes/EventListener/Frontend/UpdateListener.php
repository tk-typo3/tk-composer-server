<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\EventListener\Frontend;

use TimonKreis\TkComposerServer\Domain\Model\Repository;
use TimonKreis\TkComposerServer\Service\UpdateService;

/**
 * @package TimonKreis\TkComposerServer\EventListener\Frontend
 */
class UpdateListener extends AbstractFrontendListener
{
    /**
     * @var UpdateService
     */
    protected $updateService;

    /**
     * @param UpdateService $updateService
     */
    public function __construct(UpdateService $updateService)
    {
        $this->updateService = $updateService;
    }

    /**
     *
     */
    protected function execute() : void
    {
        $updateUri = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tk_composer_server']['updateUri'] ?? 'update';

        if ($this->frontendRequestEvent->getUri() != $updateUri) {
            return;
        }

        $errors = $this->updateService->updateAllRepositories();
        $data = [
            'status' => 'ok',
        ];

        if ($errors) {
            $data['status'] = 'error';
            $data['repositories'] = [];

            foreach ($errors as $error) {
                /** @var Repository $repository */
                $repository = $error['repository'];
                /** @var \Exception $exception */
                $exception = $error['exception'];

                $data['repositories'][$repository->getUrl()] = $exception->getMessage();
            }
        }

        $this->setJsonResponse($data, $errors ? 400 : 200);
    }
}
