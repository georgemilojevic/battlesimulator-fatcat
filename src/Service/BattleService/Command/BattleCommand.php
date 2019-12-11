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
    public function __invoke(Army $attackedArmy, Army $attackingArmy)
    {
        $attackedArmyUnits = $attackedArmy->getUnits();
        $attackingArmyUnits = $attackingArmy->getUnits();

        ini_set("precision", 3);
        $damagedArmyUnitsLeft = $attackingArmyUnits / 2;

        $attackingArmyUnitsLeft = $attackingArmyUnits - ceil($damagedArmyUnitsLeft);

        if ($attackedArmyUnits === 1) {
            $damagedArmyUnitsLeft = 0;
        }

        $attackedArmy->setUnits($damagedArmyUnitsLeft);
        $this->em->persist($attackedArmy);
        $attackingArmy->setUnits($attackingArmyUnitsLeft);
        $this->em->persist($attackingArmy);
        $this->em->flush();

        $body = json_encode([
            'attackedArmy' => [
                'armyId' => $attackedArmy->getId(),
                'army_name' => $attackedArmy->getName(),
                'units_previous' => $attackedArmyUnits,
                'units_current' => $attackedArmy->getUnits(),
            ],
            'attackingArmy' => [
                'armyId' => $attackingArmy->getId(),
                'army_name' => $attackingArmy->getName(),
                'units_previous' => $attackingArmyUnitsLeft,
                'units_current' => $attackingArmy->getUnits(),
            ],
        ]);

        $response = new Response();
        return $response->setContent($body);
    }
}
