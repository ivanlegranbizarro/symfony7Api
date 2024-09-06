<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:user-create',
    description: 'Create a user with password',
)]
class UserCreateCommand extends Command
{
    public function __construct(private UserRepository $userRepository, private UserPasswordHasherInterface $userPasswordHasher)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Username of the new user')
            ->addArgument('password', InputArgument::REQUIRED, 'Password of the new user in plain text')
            ->addArgument('roles', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'Roles of the new user separated by space');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $roles = $input->getArgument('roles');

        if ($this->userRepository->findOneBy(['username' => $username])) {
            $io->error('User with username ' . $username . ' already exists');
            return Command::FAILURE;
        }

        $user = new User();
        $user->setUsername($username);
        $user->setRoles($roles);

        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $password);

        $user->setPassword($hashedPassword);

        $this->userRepository->save($user, true);


        $io->success('User was created with ID ' . $user->getId());
        $io->info(var_export($user, true));

        return Command::SUCCESS;
    }
}
