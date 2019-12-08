<?php

namespace App\Service\CommandBus\Handler;

use App\Entity\Army;
use App\Entity\Game;
use App\Service\CommandBus\Command\CreateGameCommand;
use App\Service\CommandBus\Handler;
use Doctrine\ORM\EntityManagerInterface;
use League\Tactician\CommandBus;

class GameHandler extends Handler
{
    public function __construct(CommandBus $commandBus, EntityManagerInterface $entityManager)
    {
        parent::__construct($commandBus, $entityManager);
    }

    /**
     * @param CreateGameCommand $command
     * @return Game
     * @throws \Exception
     */
    public function handleCreateGame(CreateGameCommand $command)
    {
        $newGame = $command->getGame();

        $army = $this->getEntityManager()->getRepository(Army::class)
            ->findAll();

        if (count($army) < 10) {
            throw new \Exception(
                'Army count is lower than 10'
            );
        }

        $newGame->setStatus(Game::IN_PROGRESS);
        $this->getEntityManager()->persist($newGame);
        $this->getEntityManager()->flush();

        return $newGame;
    }

}
