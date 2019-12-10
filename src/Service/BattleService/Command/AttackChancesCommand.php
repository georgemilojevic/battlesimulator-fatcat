<?php

namespace App\Service\BattleService\Command;

use App\Entity\Army;
use App\Service\BattleService\Exception\ChancesException;

class AttackChancesCommand
{
    /**
     * @param Army $army
     * @return bool
     * @throws ChancesException
     */
    public function __invoke(Army $army)
    {
        $chances = mt_rand(0, 100);
        if ($chances < $army->getUnits()) {
            return true;
        }

        throw ChancesException::unsuccessfulAttack();
    }
}
