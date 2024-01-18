<?php

namespace App\Command;

use App\Entity\User;
use App\Helper\Enum\Role\User as UserRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand('app:create-admin', 'Create admin user')]
class CreateAdminCommand extends Command
{
    public function __construct(
        protected EntityManagerInterface      $entityManager,
        protected UserPasswordHasherInterface $hasher,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = (new User())
            ->setRoles([UserRole::ADMIN->value]);

        $helper = $this->getHelper('question');

        $question = new Question(
            '<question>Please type login</question>: ',
        );
        $user->setLogin($helper->ask($input, $output, $question));

        $question = new Question(
            '<question>Please type password</question>: ',
        );
        $user->setPassword($this->hasher->hashPassword($user, $helper->ask($input, $output, $question)));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return Command::SUCCESS;
    }
}