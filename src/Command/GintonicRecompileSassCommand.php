<?php

namespace App\Command;

use App\Service\StyleService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'gintonic:recompile-sass',
    description: 'Recompile your stylesheets with your custom configuration (colors, theming etc)',
)]
class GintonicRecompileSassCommand extends Command
{
    public function __construct(
        private readonly StyleService $styleService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $result = $this->styleService->recompileSass();
        if ($result) {
            $io->success('Sass recompiled successfully');
        } else {
            $io->error('An error occurred while recompiling Sass');
        }

        return Command::SUCCESS;
    }
}
