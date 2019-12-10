<?php


namespace App\Service\BattleService\Command;

use App\Entity\Army;
use App\Entity\Game;
use App\Service\BattleService\BattleAction;
use App\Service\BattleService\BattleInterface;
use App\Service\BattleService\Exception\NotEnoughArmiesException;
use Doctrine\ORM\EntityManagerInterface;

class CreateGameCommand extends BattleAction
{
    /** @var Army $army */
    private $army;

    public function __construct(EntityManagerInterface $entityManager, Army $army)
    {
        parent::__construct($entityManager);
        $this->army = $army;
    }

    /**
     * @return Game
     * @throws NotEnoughArmiesException
     */
    public function __invoke()
    {
        return $this->createGame($this->army);
    }

    /**
     * @param $attackingArmy
     * @return Game
     * @throws NotEnoughArmiesException
     */
    public function createGame($attackingArmy)
    {
        $game = new Game();

        $army = $this->em->getRepository(Army::class)
            ->findAll();

        if (count($army) <= 5) {
            // should be thrown only once and only first time
            throw NotEnoughArmiesException::lessThanFiveArmies();
        }

        if (count($army) < 10) {
            throw NotEnoughArmiesException::lessThanTenArmies();
        }

        $game->setStatus(Game::IN_PROGRESS);
        $game->setArmyId($attackingArmy);
        $this->em->persist($game);
        $this->em->flush();

        return $game;
    }
}
