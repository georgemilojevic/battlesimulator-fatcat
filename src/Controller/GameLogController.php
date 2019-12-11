<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameLog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GameLogController extends AbstractController
{
    /**
     * @Route("game-log/{id}", name="get-game-log")
     */
    public function getLog(Game $id)
    {
        $logs = $this->getDoctrine()->getRepository(GameLog::class)
            ->findBy(['game' => $id]);

        return $this->render('game-log/index.html.twig', ['logs' => $logs]);
    }
}
