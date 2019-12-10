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
        $criteria = new Criteria();
        $criteria
            ->where(Criteria::expr()->neq('units', 0))
            ->andWhere(Criteria::expr()->eq('game_id', $game->getId()))
            ->orderBy(['id' => 'DESC'])
            ->setMaxResults(1);

        $army = $this->em->getRepository(Army::class)->findBy([$criteria]);

        if (!$army) {
            throw ZeroArmiesCountException::noArmiesLeftStanding();
        }

        return $army;
    }
}
