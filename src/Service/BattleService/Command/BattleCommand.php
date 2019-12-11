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
     * @param $armyUnderSiege Army
     * @param $attackingArmy Army
     * @return Response
     */
    public function __invoke(Army $armyUnderSiege, Army $attackingArmy)
    {
        $unitsUnderSiege = $armyUnderSiege->getUnits();
        $attackingArmyUnits = $attackingArmy->getUnits();

        $damagedArmyUnitsLeft = $unitsUnderSiege / 2;

        $attackingArmyUnitsLeft = $attackingArmyUnits - ceil($damagedArmyUnitsLeft);

        if ($attackingArmyUnitsLeft <= 1) {
            $attackingArmyUnitsLeft = max($attackingArmyUnitsLeft, 0);
        }

        if ($damagedArmyUnitsLeft <= 1 ) {
            $damagedArmyUnitsLeft = max($damagedArmyUnitsLeft, 0);
        }

        $armyUnderSiege->setUnits($damagedArmyUnitsLeft);
        $this->em->persist($armyUnderSiege);
        $attackingArmy->setUnits($attackingArmyUnitsLeft);
        $this->em->persist($attackingArmy);
        $this->em->flush();

        $body = json_encode([
            'attackedArmy' => [
                'armyId' => $armyUnderSiege->getId(),
                'army_name' => $armyUnderSiege->getName(),
                'units_previous' => $unitsUnderSiege,
                'units_current' => $armyUnderSiege->getUnits(),
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
