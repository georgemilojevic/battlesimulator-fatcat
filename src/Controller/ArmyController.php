<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArmyController extends AbstractController
{
    /**
     * @Route("/army/show", name="army-show")
     */
    public function indexAction()
    {
    }
}
