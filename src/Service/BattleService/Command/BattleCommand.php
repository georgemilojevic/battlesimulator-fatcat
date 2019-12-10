<?php

namespace App\Service\BattleService\Command;

use App\Entity\Army;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class BattleCommand
{
    /** @var EntityManagerInterface $em */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param $attackedArmy Army
     * @param $attackingArmy Army
     * @return Response
     */
    public function __invoke($attackedArmy, $attackingArmy)
    {
        $attackedArmyUnits = $attackedArmy[0]->getUnits();
        $attackingArmyUnits = $attackingArmy->getUnits();

        ini_set("precision", 3);
        $damagedArmyUnitsLeft = $attackingArmyUnits / 2;

        $attackingArmyUnitsLeft = $attackingArmyUnits - floor($damagedArmyUnitsLeft);

        if ($attackedArmyUnits === 1) {
            $damagedArmyUnitsLeft = 0;
        }

        $attackedArmy[0]->setUnits($damagedArmyUnitsLeft);
        $this->em->persist($attackedArmy[0]);
        $attackingArmy->setUnits($attackingArmyUnitsLeft);
        $this->em->persist($attackingArmy);
        $this->em->flush();

        $response = new Response();
        return $response->setContent(json_encode([
            [
                'attackedArmy' => [
                    'army' => $attackedArmy,
                    'armyId' => $attackedArmy[0]->getId(),
                    'army_name' => $attackedArmy[0]->getName(),
                    'units_previous' => $attackedArmyUnits,
                    'units_current' => $attackedArmy[0]->getUnits(),
                ],
            ],
            [
                'attackingArmy' => [
                    'army' => $attackingArmy,
                    'armyId' => $attackingArmy->getId(),
                    'army_name' => $attackingArmy->getName(),
                    'units_previous' => $attackingArmy,
                    'units_current' => $attackingArmy->getUnits(),
                ],
            ],
        ]));
    }
}
