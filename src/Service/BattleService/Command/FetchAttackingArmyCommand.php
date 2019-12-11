<?php

namespace App\Service\BattleService\Command;

use App\Entity\Army;
use App\Entity\Game;
use App\Service\BattleService\Exception\ZeroArmiesCountException;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class FetchAttackingArmyCommand
{
    /** @var EntityManagerInterface $em */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param Game $game
     * @return object[]
     * @throws ZeroArmiesCountException
     */
    public function __invoke(Game $game)
    {
        $army = $this->em->createQueryBuilder()
            ->select('a')
            ->from(Army::class, 'a')
            ->where('a.units IS NOT NULL')
            ->andWhere('a.game = :game')
            ->setParameter('game', $game)
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        if (!$army) {
            throw ZeroArmiesCountException::noArmiesLeftStanding();
        }

        return $army[0];
    }
}
