<?php


namespace App\Service\BattleService;


use App\Service\BattleService\Command\ResetGameCommand;

class BattleReset
{
    /** @var ResetGameCommand $ResetGame */
    private $ResetGame;

    public function __construct(ResetGameCommand $resetGame)
    {
        $this->ResetGame = $resetGame;
    }

    /**
     * @param $id
     * @throws Exception\GameNotFoundException
     */
    public function __invoke($id)
    {
        return ($this->ResetGame)($id);
    }
}