<?php

namespace App\Service\BattleService\Command;

use App\Entity\Army;
use App\Service\BattleService\BattleAction;
use App\Service\BattleService\Exception\ZeroArmiesCountException;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class FetchAttackingArmyCommand extends BattleAction
{
    /**
     * @return object[]
     * @throws ZeroArmiesCountException
     */
    public function __invoke()
    {
        return $this->fetchArmy();
    }

    public function fetchArmy()
    {
        $criteria = new Criteria();
        $criteria
            ->where(Criteria::expr()->neq('units', 0))
            ->orderBy(['id' => 'DESC'])
            ->setMaxResults(1);

        $army = $this->em->getRepository(Army::class)->findBy([$criteria]);

        if (!$army) {
            throw ZeroArmiesCountException::noArmiesLeftStanding();
        }

        return $army;
    }
}
