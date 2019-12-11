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
            ->findBy(['game' => $game], ['id' => 'ASC'], 1);

        $data = json_decode($firstLog[0]->getLog(), true);
        foreach ($data as $armyLog) {
            $this->resetUnitsCount($armyLog['armyId'], $armyLog['units_previous']);
            $this->resetUnitsCount($armyLog['armyId'], $armyLog['units_previous']);
        }

        /** @var Game $gameReset */
        $gameReset = $this->em->getRepository(Game::class)
            ->findBy(['id' => $game->getId()]);

        foreach ($gameReset as $reset) {
            $this->em->remove($reset);
            $this->em->flush();
        }

        throw GameNotFoundException::idNotFound();
    }

    /**
     * @param $id
     * @param $units
     */
    private function resetUnitsCount($id, $units)
    {
        /** @var Army $army */
        $army = $this->em->getRepository(Army::class)->find($id);
        $army->setUnits($units);
        $this->em->persist($army);
        $this->em->flush();
    }
}
