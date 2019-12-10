<?php

namespace App\Service\BattleService\Command;

use App\Entity\Army;
use App\Service\BattleService\BattleAction;
use App\Service\BattleService\Exception\ZeroArmiesCountException;
use Doctrine\Common\Collections\Criteria;

class FetchAttackedArmyCommand extends BattleAction
{
    /** @var Army $army */
    private $army;

    /** @var Criteria $criteria */
    private $criteria;

    public function __construct(Criteria $criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * @return object[]
     * @throws ZeroArmiesCountException
     */
    public function __invoke()
    {
        return self::fetchAttackedArmyByStrategy($this->army);
    }

    /**
     * @param $attackingArmy Army
     * @return object[]
     * @throws ZeroArmiesCountException
     */
    public function fetchAttackedArmyByStrategy($attackingArmy)
    {
        $criteria = new Criteria();
        $criteria
            ->where(Criteria::expr()->neq('units', 0))
            ->andWhere(Criteria::expr()->neq('id', $attackingArmy->getId()))
            ->setMaxResults(1);

        if ($attackingArmy->getAttackStrategy() === Army::ATTACK_WEAKEST) {
            $weakestArmy = $this->em
                ->getRepository(Army::class)
                ->findBy([$criteria], ['units' => 'ASC']);

            if ($weakestArmy) {
                return $weakestArmy;
            }
        }

        if ($attackingArmy->getAttackStrategy() === Army::ATTACK_STRONGEST) {
            $strongestArmy = $this->em
                ->getRepository(Army::class)
                ->findBy([$criteria], ['units' => 'DESC']);

            if ($strongestArmy) {
                return $strongestArmy;
            }
        }

        if ($attackingArmy->getAttackStrategy() === Army::ATTACK_RANDOM) {
            $armies = $this->em
                ->getRepository(Army::class)
                ->findBy([$criteria]);

            if (count($armies) > 1 && !empty($armies)) {
                foreach ($armies as $randomArmy) {
                    $armyId = mt_rand($randomArmy->getId());

                    return $this->em->getRepository(Army::class)->find($armyId);
                }
            }
        }

        throw ZeroArmiesCountException::noArmiesLeftStanding();
    }
}
