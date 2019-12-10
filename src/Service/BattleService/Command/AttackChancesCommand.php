<?php

namespace App\Service\BattleService\Command;

use App\Entity\Army;
use App\Service\BattleService\BattleAction;
use App\Service\BattleService\Exception\ChancesException;

class AttackChancesCommand extends BattleAction
{
    /** @var Army $army */
    private $army;

    /**
     * @return int
     * @throws ChancesException
     */
    public function __invoke()
    {
        return $this->checkChances($this->army);
    }

    /**
     * @param $army Army
     * @return int
     * @throws ChancesException
     */
    public function checkChances($army)
    {
        $chances = mt_rand(0, 100);
        if ($chances < $army->getUnits()) {
            return true;
        }

        throw ChancesException::unsuccessfulAttack();
    }
}
