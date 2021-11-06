<?php
/**
 * @author Timon Kreis <mail@timonkreis.de>
 * @license https://www.gnu.org/licenses/gpl-3.0.de.html
 */
declare(strict_types = 1);

namespace TimonKreis\TkComposerServer\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TimonKreis\TkComposerServer\Domain\Model\Repository;
use TimonKreis\TkComposerServer\Service\UpdateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @package TimonKreis\TkComposerServer\Command
 */
class UpdatePackagesCommand extends Command
{
    /**
     * @var UpdateService
     */
    protected $updateService;

    /**
     * @inheritDoc
     */
    protected function configure() : void
    {
        $this->setDescription('Update packages')
            ->setHelp('Updates all composer packages in database.')
            ->addOption('force-reload', 'f', InputOption::VALUE_NONE, 'Force full reload of all packages');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output) : void
    {
        $this->updateService = GeneralUtility::makeInstance(UpdateService::class);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Updating all packages');

        try {
            $forceReload = $input->getOption('force-reload') || is_string($input->getOption('force-reload'));

            if ($forceReload) {
                $io->note('Running force reload');
            }

            $errors = $this->updateService->updateAllRepositories($forceReload);
        } catch (\Exception $e) {
            $io->error($e->getMessage());

            return $e->getCode() ?: $this::FAILURE;
        }

        if ($errors) {
            foreach ($errors as $error) {
                /** @var Repository $repository */
                $repository = $error['repository'];
                /** @var \Exception $exception */
                $exception = $error['exception'];

                $io->error($repository->getUrl() . ': ' . $exception->getMessage());
            }

            return $this::FAILURE;
        }

        $io->success('Packages successfully updated');

        return $this::SUCCESS;
    }
}
