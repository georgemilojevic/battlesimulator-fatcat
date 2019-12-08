<?php


namespace App\Service\CommandBus\Command;


use App\Entity\Army;

class FetchAttackingArmyCommand
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

    public function getArmyByIdDesc()
    {
        return $this->army->findByIdDesc();
    }
}