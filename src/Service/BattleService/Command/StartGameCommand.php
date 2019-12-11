<?php


namespace App\Service\BattleService\Command;

use App\Entity\Army;
use App\Entity\Game;
use App\Service\BattleService\BattleAction;
use App\Service\BattleService\BattleInterface;
use App\Service\BattleService\Exception\NotEnoughArmiesException;
use Doctrine\ORM\EntityManagerInterface;

class StartGameCommand
{
    /** @var EntityManagerInterface $em */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param Game $game
     * @return bool
     * @throws NotEnoughArmiesException
     */
    public function __invoke(Game $game)
    {
        $army = $this->em->getRepository(Army::class)
            ->findBy([
                'game' => $game,
            ]);

        if ($game->getStatus() !== Game::IN_PROGRESS && count($army) <= 5) {
            $game->setStatus(Game::IN_PROGRESS);
            $this->em->persist($game);
            $this->em->flush();

            throw NotEnoughArmiesException::lessThanFiveArmies();
        }

        if (count($army) < 10) {
            throw NotEnoughArmiesException::lessThanTenArmies();
        }

        return true;
    }
}
