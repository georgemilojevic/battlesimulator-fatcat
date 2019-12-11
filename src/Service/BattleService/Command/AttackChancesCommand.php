<?php

namespace App\Service\BattleService\Command;

use App\Entity\Army;
use App\Service\BattleService\Exception\ChancesException;

class AttackChancesCommand
{
    /**
     * @param Army $army
     * @return object
     * @throws ChancesException
     */
    public function __invoke(Army $army)
    {
        $chances = random_int(0, 100);
        if ($chances < $army->getUnits()) {
            return;
        }

        throw ChancesException::unsuccessfulAttack();
    }
}
