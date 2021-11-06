<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

return [
    'frontend' => [
        'tk-typo3/tk-composer-server' => [
            'target' => TimonKreis\TkComposerServer\Middleware\Frontend::class,
            'before' => ['typo3/cms-frontend/backend-user-authentication'],
        ],
    ],
];
