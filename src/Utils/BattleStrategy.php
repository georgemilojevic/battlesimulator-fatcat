<?php

namespace App\Utils;

use App\Entity\Army;
use App\Entity\Game;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

final class BattleStrategy
{
    /** @var Army */
    private $army;

    /** @var Game */
    private $game;

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(Army $army, Game $game, EntityManagerInterface $entityManager)
    {
        $this->army = $army;
        $this->game = $game;
        $this->em = $entityManager;
    }

    /**
     * @return object|null
     */
    public function getAttackingArmy()
    {
        $army = $this->em->getRepository(Army::class)
            ->findOneBy(['id' => 'DESC']);

        $game = new Game();
        $game->setArmyId($army);
        $game->setStatus(Game::IN_PROGRESS);
        $this->em->persist($game);
        $this->em->flush();

        $this->attackChances($army);

        return new Response(json_encode([
            'army' => $army,
            'game' => $game->getId(),
        ]));
    }

    /**
     * @param string $attackingStrategy
     * @return int|object|object[]|null
     */
    public function getAttackedArmy($attackingStrategy = '')
    {
        if ($attackingStrategy === $this->army::ATTACK_WEAKEST) {
            return $this->em->getRepository(Army::class)->findBy([
                'attack_strategy' => $this->army::ATTACK_WEAKEST,
            ], ['units' => 'ASC'], 1);
        }

        if ($attackingStrategy === $this->army::ATTACK_STRONGEST) {
            return $this->em->getRepository(Army::class)->findBy([
                'attack_strategy' => $this->army::ATTACK_STRONGEST,
            ], ['units' => 'DESC'], 1);
        }

        if ($attackingStrategy === $this->army::ATTACK_RANDOM) {
            $armies = $this->em->getRepository(Army::class)->findAll();
            foreach ($armies as $randomArmy) {
                $armyId = mt_rand($randomArmy->getId());

                return $this->em->getRepository(Army::class)->find($armyId);
            }
        }

        return Response::HTTP_NO_CONTENT;
    }

    /**
     * @param $army Army
     * @return int|Response
     */
    public function attackChances($army): int
    {
        $chances = mt_rand(0, 100);
        if ($chances < $army->getUnits()) {
            $attackedArmy = $this->getAttackedArmy($army->getAttackStrategy());
            $this->attackAction($army, $attackedArmy);
            return Response::HTTP_OK;
        }
        return Response::HTTP_NO_CONTENT;
    }

    /**
     * @param $attackingArmy Army
     * @param $attackedArmy Army
     * @return int|Response
     */
    public function attackAction($attackingArmy, $attackedArmy)
    {
        $damagedArmyUnits = $attackedArmy->getUnits();
        $attackingArmyUnits = $attackingArmy->getUnits();

        $damagedArmyUnitsLeft = $damagedArmyUnits / 2;
        $attackingArmyUnitsLeft = $attackingArmyUnits - ceil($damagedArmyUnits);
        $status = Game::IN_PROGRESS;

        if ($damagedArmyUnits === 1) {
            $damagedArmyUnitsLeft = 0;
            $status = Game::COMPLETED;
        }

        $this->updateUnits($attackedArmy, $damagedArmyUnitsLeft);
        $this->updateUnits($attackingArmy, $attackingArmyUnitsLeft);

        $response = new Response();
        $response->setContent(json_encode([
            'attackedArmy' => $attackedArmy,
            'attackingArmy' => $attackingArmy,
            'status' => $status
        ]));
        $this->setGameLog($response);

        return Response::HTTP_OK;
    }

    /**
     * @param $army Army
     * @param $units
     */
    public function updateUnits($army, $units)
    {
        $army->setUnits($units);
        $this->em->persist($army);
        $this->em->flush();
    }

    /**
     * @param $response Response
     */
    public function setGameLog($response)
    {
        if ($response->getStatusCode() === 200) {
            $body = json_decode($response->getContent());
            var_dump($body['attackedArmy']);
            var_dump($body['attackingArmy']);
        }
    }
}
