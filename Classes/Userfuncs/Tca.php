<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\Userfuncs;

/**
 * @package TimonKreis\TkComposerServer\Userfuncs
 */
class Tca
{
    /**
     * Render the repository label
     *
     * @param array $parameters
     */
    public function repositoryLabel(array &$parameters) : void
    {
        $parameters['title'] = $parameters['row']['package_name'] ?: '[' . $parameters['row']['url'] . ']';
    }
}
