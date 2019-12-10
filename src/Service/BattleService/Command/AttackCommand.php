<?php

namespace App\Service\BattleService\Command;

use App\Entity\Army;
use App\Entity\Game;
use App\Service\BattleService\BattleAction;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class AttackCommand extends BattleAction
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    /**
     * @param $attackedArmy Army
     * @param $attackingArmy Army
     * @return Response
     */
    public function doAttack($attackedArmy, $attackingArmy)
    {

        $attackedArmyUnits = $attackedArmy[0]->getUnits();
        $attackingArmyUnits = $attackingArmy->getUnits();

        ini_set("precision", 3);
        $damagedArmyUnitsLeft = $attackingArmyUnits / 2;

        $attackingArmyUnitsLeft = $attackingArmyUnits - ceil($attackedArmyUnits);
        $status = Game::IN_PROGRESS;

        if ($attackedArmyUnits === 1) {
            $damagedArmyUnitsLeft = 0;
            $status = Game::COMPLETED;
        }

        $attackedArmy[0]->setUnits($damagedArmyUnitsLeft);
        $this->em->persist($attackedArmy[0]);
        $attackingArmy->setUnits($attackingArmyUnitsLeft);
        $this->em->persist($attackingArmy);
        $this->em->flush();

        $response = new Response();
        return $response->setContent(json_encode([
            'attackedArmy' => [
                'army' => $attackedArmy,
                'armyId' => $attackedArmy[0]->getId(),
                'army_name' => $attackedArmy[0]->getName(),
                'units_previous' => $attackedArmyUnits,
                'units_current' => $attackedArmy[0]->getUnits(),
            ],
            'attackingArmy' => [
                'army' => $attackingArmy,
                'armyId' => $attackingArmy->getId(),
                'army_name' => $attackingArmy->getName(),
                'units_previous' => $attackingArmy,
                'units_current' => $attackingArmy->getUnits(),
            ],
            'status' => $status,
        ]));
    }
}
