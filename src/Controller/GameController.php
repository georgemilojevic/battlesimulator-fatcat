<?php

namespace App\Controller;

use App\Entity\Army;
use App\Entity\Game;
use App\Form\ArmyType;
use App\Utils\BattleStrategy;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    /** @var BattleStrategy $battleStrategy */
    private $battleStrategy;

    public function __construct(BattleStrategy $battleStrategy)
    {
        $this->battleStrategy = $battleStrategy;
    }

    /**
     * @param Request $request
     * @Route("/", name="index")
     * @return Response
     */
    public function index(Request $request)
    {
        $army = new Army();

        $form = $this->createForm(ArmyType::class, $army);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $army = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($army);
            $em->flush();

            $this->addFlash('success', 'Army created');
            return $this->redirectToRoute('index');
        }

        return $this->render('main.html.twig', [
            'form' => $form->createView(),
            'armyCount' => count($this->getDoctrine()->getRepository(Army::class)->findAll()),
            'games' => null,
        ]);
    }

    /**
     * @Route("/game-start", name="start-game")
     */
    public function startAction()
    {
        $this->addFlash('warning', 'Once at least 10 armies have joined, the game can start.');
        return $this->redirect('/');
    }

    /**
     * @Route("/game/list-all", name="list-all-games")
     */
    public function listAllGamesAction()
    {
        $games = $this->getDoctrine()->getRepository(Game::class)->findAll();
        return $this->render('game/list-games.html.twig', ['games' => $games]);
    }
}
