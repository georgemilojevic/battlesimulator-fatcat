<?php

namespace App\Service\BattleService\Command;

use App\Entity\Army;
use App\Entity\Game;
use App\Service\BattleService\Exception\ZeroArmiesCountException;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class FetchAttackedArmyCommand
{
    /** @var EntityManagerInterface $em */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param Army $army
     * @param Game $game
     * @return mixed
     * @throws ZeroArmiesCountException
     */
    public function __invoke(Army $army, Game $game)
    {
        if ($army->getAttackStrategy() === Army::ATTACK_WEAKEST) {
            $weakestArmy = $this->em->createQueryBuilder()
                ->select('a')
                ->from(Army::class, 'a')
                ->where('a.units > 0')
                ->andWhere('a.game = :game')
                ->andWhere('a.id != :army')
                ->setParameter('army', $army)
                ->setParameter('game', $game)
                ->orderBy('a.units', 'ASC')
                ->setMaxResults(1)
                ->getQuery()
                ->getResult();

            if ($weakestArmy) {
                return $weakestArmy[0];
            }
        }

        if ($army->getAttackStrategy() === Army::ATTACK_STRONGEST) {
            $strongestArmy = $this->em->createQueryBuilder()
                ->select('a')
                ->from(Army::class, 'a')
                ->where('a.units > 0')
                ->andWhere('a.game = :game')
                ->andWhere('a.id != :army')
                ->setParameter('army', $army)
                ->setParameter('game', $game)
                ->orderBy('a.units', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getResult();

            if ($strongestArmy) {
                return $strongestArmy[0];
            }
        }

        if ($army->getAttackStrategy() === Army::ATTACK_RANDOM) {
            $armies = $this->em->createQueryBuilder()
                ->select('a')
                ->from(Army::class, 'a')
                ->where('a.units > 0')
                ->andWhere('a.game = :game')
                ->andWhere('a.id !== :army')
                ->setParameter('army', $army)
                ->setParameter('game', $game)
                ->getQuery()
                ->getResult();

            if (!empty($armies) && count($armies) > 1) {
                foreach ($armies as $randomArmy) {
                    $armyId = mt_rand($randomArmy->getId());

                    $randomArmy = $this->em->getRepository(Army::class)->find($armyId);
                    return $randomArmy[0];
                }
            }
        }

        throw ZeroArmiesCountException::noArmiesLeftStanding();
    }
}
