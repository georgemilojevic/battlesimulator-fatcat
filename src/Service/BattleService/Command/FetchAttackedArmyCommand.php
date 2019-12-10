<?php

namespace App\Service\BattleService\Command;

use App\Entity\Army;
use App\Service\BattleService\BattleAction;
use App\Service\BattleService\Exception\ZeroArmiesCountException;
use Doctrine\ORM\EntityManagerInterface;

class FetchAttackedArmyCommand extends BattleAction
{
    /** @var Army $army */
    private $army;

    public function __construct(EntityManagerInterface $entityManager, Army $army)
    {
        $this->army = $army;
        parent::__construct($entityManager);
    }

    /**
     * @return object[]
     * @throws ZeroArmiesCountException
     */
    public function __invoke()
    {
        return $this->fetchAttackedArmyByStrategy($this->army);
    }

    /**
     * @param $attackingArmy Army
     * @return object[]
     * @throws ZeroArmiesCountException
     */
    public function fetchAttackedArmyByStrategy($attackingArmy)
    {

        if ($attackingArmy->getAttackStrategy() === $this->army::ATTACK_WEAKEST) {
            $weakestArmy = $this->em
                ->getRepository(Army::class)
                ->findBy([
//                    'id' => !$attackingArmy->getId(),
//                    'units' => !false,
                ], ['units' => 'ASC'], 1);

            if ($weakestArmy) {
                return $weakestArmy;
            }
        }

        if ($attackingArmy->getAttackStrategy() === $this->army::ATTACK_STRONGEST) {
            $strongestArmy = $this->em
                ->getRepository(Army::class)
                ->findBy([
//                    'id' => !$attackingArmy->getId(),
//                    'units' => !false,
                ], ['units' => 'DESC'], 1);


            if ($strongestArmy) {
                return $strongestArmy;
            }
        }

        if ($attackingArmy->getAttackStrategy() === $this->army::ATTACK_RANDOM) {
            $armies = $this->em
                ->getRepository(Army::class)
                ->findBy([
                    'army_id !=' => $attackingArmy->getId(),
                    'units !=' => 0,
                ]);

            if (count($armies) > 1) {
                foreach ($armies as $randomArmy) {
                    $armyId = mt_rand($randomArmy->getId());

                    return $this->em->getRepository(Army::class)->find($armyId);
                }
            }
        }

        throw ZeroArmiesCountException::noArmiesLeftStanding();
    }
}
