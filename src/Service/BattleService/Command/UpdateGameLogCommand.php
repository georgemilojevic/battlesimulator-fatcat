<?php

namespace App\Service\BattleService\Command;

use App\Entity\Game;
use App\Entity\GameLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class UpdateGameLogCommand
{
    /** @var EntityManagerInterface $em */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param Response $response
     * @param Game $game
     * @return Response
     * @throws \Exception
     */
    public function __invoke(Response $response, Game $game)
    {
        $body = [];
        if ($response->getContent()) {
            $result = json_decode($response->getContent(), true);

            $gameLog = new GameLog();
            foreach ($result as $battleData) {

//                $body = json_encode([
//                    'attackingArmy' => [
//                        'armyId' => $battleData['attackingArmy']['armyId'],
//                        'attacking_army_name' => $battleData['attackingArmy']['army_name'],
//                        'units_previous' => $battleData['attackingArmy']['units_previous'],
//                        'units_current' => $battleData['attackingArmy']['units_current'],
//                    ],
//                    'attackedArmy' => [
//                        'armyId' => $battleData['attackedArmy']['armyId'],
//                        'attacked_army_name' => $battleData['attackedArmy']['army_name'],
//                        'units_previous' => $battleData['attackedArmy']['units_previous'],
//                        'units_current' => $battleData['attackedArmy']['units_current'],
//                    ],
//                ]);
            }

            $responseMessage = sprintf(
                'Army: %s attacked Army: %s and made %s units damage. Armies left: %s',
                $result['attackingArmy']['army_name'],
                $result['attackedArmy']['army_name'],
                $result['attackedArmy']['units_current'],
                $result['attackingArmy']['units_current']
            );

            $gameLog->setLog([$response->getContent()]);
            $gameLog->setGame($game);
            $this->em->persist($gameLog);
            $this->em->flush();

            return new Response($responseMessage);
        }

        throw new \Exception('Something went wrong, game log not saved');
    }
}