<?php

namespace App\Service\BattleService;

use App\Entity\Army;
use App\Service\BattleService\Command\AttackChancesCommand;
use App\Service\BattleService\Command\AttackCommand;
use App\Service\BattleService\Command\CreateGameCommand;
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

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ChancesException
     * @throws Exception\NotEnoughArmiesException
     * @throws Exception\ZeroArmiesCountException
     */
    public function attack()
    {
        $attackingArmy = new FetchAttackingArmyCommand($this->em);
        $createGame = new CreateGameCommand($this->em, $attackingArmy());
        $createGame();
        $chances = new AttackChancesCommand($this->em, $attackingArmy());
        $chances();
        $attackedArmy = new FetchAttackedArmyCommand($this->em, $attackingArmy());
        $attackedArmy();
        $attack = new AttackCommand($this->em);
        $response = $attack->doAttack($attackedArmy(), $attackingArmy());
        $log = new UpdateGameLogCommand($this->em);
        return $log->setGameLog($response, $attackingArmy());
    }

    public static function reset($id)
    {
        $resetGame = new ResetGameCommand();
        return $resetGame->resetGame($id);
    }
}