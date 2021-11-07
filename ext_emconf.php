<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 *
 * @var $_EXTKEY
 */
$EM_CONF[$_EXTKEY] = [
    'title' => 'Composer Server',
    'description' => 'Serve composer packages to authorized accounts.',
    'category' => 'services',
    'author' => 'Timon Kreis',
    'author_email' => 'mail@timonkreis.de',
    'state' => 'stable',
    'clearCacheOnLoad' => 1,
    'version' => '1.1.1',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-10.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
