<?php

namespace App\Service\BattleService\Command;

use App\Entity\Army;
use App\Entity\Game;
use App\Service\BattleService\BattleAction;
use App\Service\BattleService\Exception\GameNotFoundException;

class ResetGameCommand extends BattleAction
{
    public function __construct()
    {
        parent::__construct($this->em);
    }

    /**
     * @param $armyId Army
     * @throws GameNotFoundException
     */
    public function resetGame($armyId)
    {
        $firstLog = $this->em->getRepository(Game::class)
            ->findBy(['army_id' => $armyId], ['id' => 'ASC'], 1);

        $data = json_decode($firstLog->getGameLog(), true);
        foreach ($data as $armyLog) {
            $this->resetArmyCount($armyLog['attackingArmy']['armyId'], $armyLog['attackingArmy']['units_previous']);
            $this->resetArmyCount($armyLog['attackedArmy']['armyId'], $armyLog['attackedArmy']['units_previous']);
        }

        /** @var Game $gameLogs */
        $gameLogs[] = $this->em->getRepository(Game::class)
            ->findBy(['army_id' => $armyId, 'status' => Game::IN_PROGRESS]);
        foreach ($gameLogs as $gameLog) {
            $this->em->remove($gameLog);
            $this->em->flush();
        }

        throw GameNotFoundException::idNotFound();
    }

    /**
     * @param $id
     * @param $units
     */
    public function resetArmyCount($id, $units)
    {
        /** @var Army $army */
        $army = $this->em->getRepository(Army::class)->findBy(['id' => $id]);
        $army->setUnits($units);
        $this->em->persist($army);
        $this->em->flush();
    }
}
