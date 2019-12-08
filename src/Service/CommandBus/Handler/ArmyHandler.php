<?php

namespace App\Service\CommandBus\Handler;

use App\Entity\Army;
use App\Service\CommandBus\Command\GameActionCommand;
use App\Service\CommandBus\Command\FetchAttackingArmyCommand;
use App\Service\CommandBus\Command\GetAttackingArmyCommand;
use App\Service\CommandBus\Handler;
use Doctrine\ORM\EntityManagerInterface;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\Response;

class ArmyHandler extends Handler
{
    public function __construct(CommandBus $commandBus, EntityManagerInterface $entityManager)
    {
        parent::__construct($commandBus, $entityManager);
    }

    public function handleAttackingArmy(FetchAttackingArmyCommand $command)
    {
        return $command->getArmyByIdDesc();
    }

    public function handleAttackingChances($units)
    {
        $chances = mt_rand(0, 100);
        if ($chances < $units) {
            return Response::HTTP_OK;
        }

        throw new \Exception('Attack failed!');
    }

    /**
     * @param GetAttackingArmyCommand $command
     * @param string $attackingStrategy
     * @return Army[]|object|null
     * @throws \Exception
     */
    public function handleAttackedArmy(GetAttackingArmyCommand $command, $attackingStrategy = '')
    {
        $attackedArmy = $command->getArmy();

        if ($attackingStrategy === $attackedArmy::ATTACK_WEAKEST) {
            $weakestArmy = $attackedArmy->findByStrategy($attackingStrategy, 'ASC', 1);

            if ($weakestArmy->getUnits() !== 0) {
                return $weakestArmy;
            }
        }

        if ($attackingStrategy === $attackedArmy::ATTACK_STRONGEST) {
            $strongestArmy = $attackedArmy->findByStrategy($attackingStrategy, 'DESC', 1);

            if ($strongestArmy->getUnits() !== 0) {
                return $strongestArmy;
            }
        }

        if ($attackingStrategy === $attackedArmy::ATTACK_RANDOM) {
            $armies = $this->getEntityManager()->getRepository(Army::class)->findAll();
            foreach ($armies as $randomArmy) {
                $armyId = mt_rand($randomArmy->getId());

                if ($randomArmy->getUnits() !== 0) {
                    return $this->getEntityManager()->getRepository(Army::class)->find($armyId);
                }
            }
        }

        throw new \Exception('All armies have been defeated!');
    }

    /**
     * @param GameActionCommand $command
     * @param $attackedArmy Army
     * @param $attackingArmy Army
     * @return int|Response
     */
    public function handleAttackAction(GameActionCommand $command, $attackedArmy, $attackingArmy)
    {
        $game = $command->getGame();
        $army = $command->getArmy();

        $damagedArmyUnits = $attackedArmy->getUnits();
        $attackingArmyUnits = $attackingArmy->getUnits();

        $damagedArmyUnitsLeft = $damagedArmyUnits / 2;
        $attackingArmyUnitsLeft = $attackingArmyUnits - ceil($damagedArmyUnits);
        $status = $game::IN_PROGRESS;

        if ($damagedArmyUnits === 1) {
            $damagedArmyUnitsLeft = 0;
            $status = $game::COMPLETED;
        }

        $army->setUnits($damagedArmyUnitsLeft);
        $army->setUnits($attackingArmyUnitsLeft);

        $response = new Response();
        return $response->setContent(json_encode([
            'attackingArmyUnits' => $damagedArmyUnitsLeft,
            'attackedArmyUnits' => $attackingArmyUnitsLeft,
            'attackedArmy' => $attackedArmy,
            'attackingArmy' => $attackingArmy,
            'status' => $status,
        ]));
    }
}
