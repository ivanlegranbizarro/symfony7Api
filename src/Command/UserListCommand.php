<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:user-list',
    description: 'List all available users in the Database',
)]
class UserListCommand extends Command
{
    public function __construct(private UserRepository $userRepository)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $table = new Table($output);

        $users = $this->userRepository->findAll();

        $table->setHeaders(['ID', 'Username', 'Roles']);

        $table->setRows(array_map(function (User $user) {
            return [$user->getId(), $user->getUsername(), implode(', ', $user->getRoles())];
        }, $users));

        $table->render();

        $io->success('Listed ' . count($users) . ' users');

        return Command::SUCCESS;
    }
}
