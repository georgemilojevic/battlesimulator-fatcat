<?php

namespace App\Service\CommandBus;

use Doctrine\ORM\EntityManagerInterface;
use League\Tactician\CommandBus;

class Handler
{
    /** @var EntityManagerInterface * */
    private $entityManager;

    /** @var CommandBus * */
    private $commandBus;
    /**
     * @param CommandBus $commandBus
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(CommandBus $commandBus, EntityManagerInterface $entityManager)
    {
        $this->commandBus = $commandBus;
        $this->entityManager = $entityManager;
    }
    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @return CommandBus
     */
    public function getCommandBus(): CommandBus
    {
        return $this->commandBus;
    }
}
