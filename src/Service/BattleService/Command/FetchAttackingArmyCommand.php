<?php

namespace App\Service\BattleService\Command;

use App\Entity\Army;
use App\Service\BattleService\BattleAction;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class FetchAttackingArmyCommand extends BattleAction
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function __invoke()
    {
        return $this->fetchArmy();
    }

    public function fetchArmy()
    {
        return $this->em->getRepository(Army::class)
            ->find(9);
    }
}
