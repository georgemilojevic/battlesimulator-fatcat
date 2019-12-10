<?php

namespace App\Controller;

use App\Entity\Army;
use App\Entity\Game;
use App\Form\ArmyType;
use App\Service\BattleService\BattleAction;
use App\Service\BattleService\Exception\ExceptionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    /** @var BattleAction $battleAction */
    private $battleAction;

    public function __construct(BattleAction $battleAction)
    {
        $this->battleAction = $battleAction;
    }

    /**
     * @return RedirectResponse
     *
     * @Route("/game/create", name="create-game")
     */
    public function createGame()
    {
        $em = $this->getDoctrine()->getManager();

        $game = new Game();
        $em->persist($game);
        $em->flush();

        return $this->redirectToRoute('show-game', ['id' => $game->getId()]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/game/{id}", name="show-game")
     */
    public function show(Request $request, $id)
    {
        /** @var Game $game */
        $game = $this->getDoctrine()
            ->getRepository(Game::class)
            ->find($id);

        /** @var Army $army */
        $army = new Army();

        $form = $this->createForm(ArmyType::class, $army);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $army = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $army->setGame($game);
            $em->persist($army);
            $em->flush();

            $this->addFlash('info', 'Army created');
            return $this->redirectToRoute('show-game', ['id' => $id]);
        }

        return $this->render('game/show.html.twig', [
            'game' => $game,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param BattleAction $battleAction
     * @return string
     *
     * @Route("/start-game", name="start-game")
     */
    public function startAction(BattleAction $battleAction, $game)
    {
        try {
            ($battleAction)($game);
        } catch (ExceptionInterface $exception) {
            $this->addFlash('info', $exception->getMessage());
        }

        return $this->render('game/show.html.twig', []);
    }

    /**
     * @Route("/list-all", name="list-all-games")
     */
    public function listGamesAction()
    {
        $games = $this->getDoctrine()->getRepository(Game::class)->findAll();
        return $this->render('game/list.html.twig', ['games' => $games]);
    }
}
