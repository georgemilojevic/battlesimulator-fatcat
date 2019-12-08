<?php

namespace App\Service\CommandBus\Command;

use App\Entity\Army;
use App\Entity\Game;

class GameActionCommand
{
    /** @var Game $game */
    private $game;

    /** @var Army $army*/
    private $army;

    public function __construct(Game $game, Army $army)
    {
        $this->game = $game;
        $this->army = $army;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function getArmy(): Army
    {
        return $this->army;
    }
}
