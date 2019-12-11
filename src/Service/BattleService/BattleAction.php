<?php

namespace App\Service\BattleService;

use App\Entity\Army;
use App\Entity\Game;
use App\Service\BattleService\Command\AttackChancesCommand;
use App\Service\BattleService\Command\BattleCommand;
use App\Service\BattleService\Command\StartGameCommand;
use App\Service\BattleService\Command\FetchAttackedArmyCommand;
use App\Service\BattleService\Command\FetchAttackingArmyCommand;
use App\Service\BattleService\Command\ResetGameCommand;
use App\Service\BattleService\Command\UpdateGameLogCommand;
use App\Service\BattleService\Exception\ChancesException;
use Doctrine\ORM\EntityManagerInterface;

class BattleAction
{
    /** @var EntityManagerInterface $em */
    protected $em;

    /** @var StartGameCommand $StartGame */
    private $StartGame;

    /** @var FetchAttackingArmyCommand $FetchAttackingArmy */
    private $FetchAttackingArmy;

    /** @var AttackChancesCommand $AttackChances */
    private $AttackChances;

    /** @var FetchAttackedArmyCommand $FetchAttackedArmy */
    private $FetchAttackedArmy;

    /** @var BattleCommand $Battle */
    private $Battle;

    /** @var UpdateGameLogCommand $UpdateGameLog */
    private $UpdateGameLog;

    /** @var ResetGameCommand $ResetGame */
    private $ResetGame;

    public function __construct(
        EntityManagerInterface $entityManager,
        StartGameCommand $startGame,
        FetchAttackedArmyCommand $fetchAttackingArmy,
        AttackChancesCommand $attackChances,
        FetchAttackedArmyCommand $fetchAttackedArmy,
        BattleCommand $battle,
        UpdateGameLogCommand $updateGameLog,
        ResetGameCommand $resetGame
    )
    {
        $this->em = $entityManager;
        $this->StartGame = $startGame;
        $this->FetchAttackingArmy = $fetchAttackingArmy;
        $this->AttackChances = $attackChances;
        $this->FetchAttackedArmy = $fetchAttackedArmy;
        $this->Battle = $battle;
        $this->UpdateGameLog = $updateGameLog;
        $this->ResetGame = $resetGame;
    }

    /**
     * @param $game
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ChancesException
     * @throws Exception\NotEnoughArmiesException
     * @throws Exception\ZeroArmiesCountException
     */
    public function __invoke($game)
    {
        ($this->StartGame)($game);

        $attackingArmy = ($this->FetchAttackingArmy)($game);

        ($this->AttackChances)($attackingArmy);

        $attackedArmy = ($this->FetchAttackedArmy)($attackingArmy);
        $result = ($this->Battle)($attackingArmy, $attackedArmy);

        $response = ($this->UpdateGameLog)($result, $game);

        return $response;
    }

//    public function reset($id)
//    {
//        return ($this->ResetGame)($id);
//    }
}