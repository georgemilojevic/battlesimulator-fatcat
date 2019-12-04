<?php

namespace App\Controller;

use App\Entity\Army;
use App\Form\ArmyType;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GameController extends AbstractController
{
    /** @var ValidatorInterface $validator */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @Route("/", name="index")
     * @return Response
     */
    public function index(Request $request, ValidatorInterface $validator)
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
            'errors' => null,
        ]);
    }

    /**
     * @Route("/game/create", name="create-game")
     */
    public function createAction()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/GameController.php',
        ]);
    }

    /**
     * @Route("/game/list", name="list-games")
     */
    public function listAction()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/GameController.php',
        ]);
    }
}
