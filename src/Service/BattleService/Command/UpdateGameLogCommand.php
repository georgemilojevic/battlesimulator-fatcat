<?php

namespace App\Service\BattleService\Command;

use App\Entity\GameLog;
use Symfony\Component\HttpFoundation\Response;

class UpdateGameLogCommand
{
    /**
     * @param Response $response
     * @param $game
     * @return Response
     * @throws \Exception
     */
    public function __invoke(Response $response, $game)
    {
        if ($response->getContent()) {
            $result = json_decode($response->getContent(), true);

            $gameLog = new GameLog();
            foreach ($result as $battleData) {

                $body = json_encode([
                    'attackingArmy' => [
                        'armyId' => $battleData['armyId'],
                        'attacking_army_name' => $battleData['army_name'],
                        'units_previous' => $battleData['units_previous'],
                        'units_current' => $battleData['units_current'],
                    ],
                    'attackedArmy' => [
                        'armyId' => $battleData['armyId'],
                        'attacked_army_name' => $battleData['army_name'],
                        'units_previous' => $battleData['units_previous'],
                        'units_current' => $battleData['units_current'],
                    ],
                ]);

                $gameLog->setLog([$body]);
                $gameLog->setGame($game);
                $this->em->persist($gameLog);
                $this->em->flush();

                return new Response(sprintf(
                    'Army: %s attacked Army: %s and made %s units damage. Armies left: %s',
                        $battleData['attackingArmy']['army_name'],
                        $battleData['attackedArmy']['army_name'],
                        $battleData['attackedArmy']['units_current'],
                        $battleData['attackingArmy']['units_current']
                    ));
            }
        }

        throw new \Exception('Something went wrong, game log not saved');
    }
}