<?php


namespace App\Service\BattleService\Command;

use App\Entity\Army;
use App\Entity\Game;
use App\Service\BattleService\BattleAction;
use App\Service\BattleService\BattleInterface;
use App\Service\BattleService\Exception\NotEnoughArmiesException;
use Doctrine\ORM\EntityManagerInterface;

class StartGameCommand
{
    /** @var EntityManagerInterface $em */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param Game $game
     * @return Game
     * @throws NotEnoughArmiesException
     */
    public function __invoke(Game $game)
    {
        $army = $this->em->getRepository(Army::class)
            ->findAll();

        if ($game->getStatus() !== Game::IN_PROGRESS) {
            if (count($army) <= 5) {
                $game->setStatus(Game::IN_PROGRESS);
                $this->em->persist($game);
                $this->em->flush();
                // should be thrown only once and only first time
                throw NotEnoughArmiesException::lessThanFiveArmies();
            }
        }

        if (count($army) < 10) {
            throw NotEnoughArmiesException::lessThanTenArmies();
        }

        return $game;
    }
}
