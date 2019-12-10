<?php

namespace App\Service\BattleService\Command;

use App\Entity\Army;
use App\Entity\Game;
use App\Entity\GameLog;
use App\Service\BattleService\Exception\GameNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class ResetGameCommand
{
    /** @var EntityManagerInterface $em */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param $game Game
     * @throws GameNotFoundException
     */
    public function __invoke($game)
    {
        $firstLog = $this->em->getRepository(GameLog::class)
            ->findBy(['game_id' => $game], ['id' => 'ASC'], 1);

        $data = json_decode($firstLog->getGameLog(), true);
        foreach ($data as $armyLog) {
            $this->resetUnitsCount($armyLog['attackingArmy']['armyId'], $armyLog['attackingArmy']['units_previous']);
            $this->resetUnitsCount($armyLog['attackedArmy']['armyId'], $armyLog['attackedArmy']['units_previous']);
        }

        /** @var Game $gameReset */
        $gameReset[] = $this->em->getRepository(Game::class)
            ->findBy(['id' => $game->getId()]);

        foreach ($gameReset as $game) {
            $this->em->remove($game);
            $this->em->flush();
        }

        throw GameNotFoundException::idNotFound();
    }

    /**
     * @param $id
     * @param $units
     */
    public function resetUnitsCount($id, $units)
    {
        /** @var Army $army */
        $army = $this->em->getRepository(Army::class)->findBy(['id' => $id]);
        $army->setUnits($units);
        $this->em->persist($army);
        $this->em->flush();
    }
}
