<?php

namespace App\Service\CommandBus\Command;

use App\Entity\Army;

class GetAttackingArmyCommand
{
    /** @var Army $army */
    private $army;

    public function __construct(Army $army)
    {
        $this->army = $army;
    }

    public function getArmy(): Army
    {
        return $this->army;
    }

    public function getStrategy()
    {
        return $this->army->getAttackStrategy();
    }
}
