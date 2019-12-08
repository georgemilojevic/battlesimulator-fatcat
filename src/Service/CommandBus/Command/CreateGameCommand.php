<?php


namespace App\Service\CommandBus\Command;


use App\Entity\Game;

class CreateGameCommand
{
    /** @var Game $game */
    private $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function getGame(): Game
    {
        return $this->game;
    }
}