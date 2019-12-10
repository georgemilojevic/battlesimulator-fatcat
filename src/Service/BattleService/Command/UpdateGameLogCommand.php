<?php

namespace App\Service\BattleService\Command;

use App\Entity\Game;
use App\Service\BattleService\BattleAction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class UpdateGameLogCommand extends BattleAction
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    /**
     * @param $response Response
     * @return Response
     * @throws \Exception
     */
    public function setGameLog(Response $response, $attackingArmy)
    {
        if ($response->getContent()) {
            $result = json_decode($response->getContent(), true);

            $game = new Game();
            foreach ($result as $resultLog) {
dump($resultLog);
                $log = json_encode([
                    'attackingArmy' => [
                        'armyId' => $resultLog['armyId'],
                        'attacking_army_name' => $resultLog['army_name'],
                        'units_previous' => $resultLog['units_previous'],
                        'units_current' => $resultLog['units_current'],
                    ],
                    'attackedArmy' => [
                        'armyId' => $resultLog['armyId'],
                        'attacked_army_name' => $resultLog['army_name'],
                        'units_previous' => $resultLog['units_previous'],
                        'units_current' => $resultLog['units_current'],
                    ],
                ]);

                $game->setStatus($result['status']);
                $game->setArmyId($attackingArmy);
                $game->setGameLog($log);
                $this->em->persist($game);
                $this->em->flush();

                return new Response(sprintf(
                    'Army: %s attacked Army: %s and made %s units damage. Armies left: %s',
                        $result['attackingArmy']['army_name'],
                        $result['attackedArmy']['army_name'],
                        $result['attackedArmy']['units_current'],
                        $result['attackingArmy']['units_current']
                    ));
            }
        }

        throw new \Exception('Something went wrong, game log not saved');
    }
}