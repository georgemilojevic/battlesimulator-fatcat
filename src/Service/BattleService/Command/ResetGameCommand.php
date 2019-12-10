<?php

namespace App\Service\BattleService\Command;

use App\Entity\Army;
use App\Entity\Game;
use App\Entity\GameLog;
use App\Service\BattleService\BattleAction;
use App\Service\BattleService\Exception\GameNotFoundException;

class ResetGameCommand extends BattleAction
{
    /**
     * @param $game Game
     * @throws GameNotFoundException
     */
    public function resetGame($game)
    {
        $firstLog = $this->em->getRepository(GameLog::class)
            ->findBy(['game_id' => $game], ['id' => 'ASC'], 1);

        $data = json_decode($firstLog->getGameLog(), true);
        foreach ($data as $armyLog) {
            $this->resetArmyCount($armyLog['attackingArmy']['armyId'], $armyLog['attackingArmy']['units_previous']);
            $this->resetArmyCount($armyLog['attackedArmy']['armyId'], $armyLog['attackedArmy']['units_previous']);
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
    public function resetArmyCount($id, $units)
    {
        /** @var Army $army */
        $army = $this->em->getRepository(Army::class)->findBy(['id' => $id]);
        $army->setUnits($units);
        $this->em->persist($army);
        $this->em->flush();
    }
}
