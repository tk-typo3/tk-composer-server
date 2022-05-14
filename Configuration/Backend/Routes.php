<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

use TimonKreis\TkComposerServer\Controller\BackendController;

return [
    'account/downloadAuthJson' => [
        'path' => '/account/downloadAuthJson',
        'target' => BackendController::class . '::downloadAuthJsonAction',
    ],
];
