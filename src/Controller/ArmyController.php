<?php

namespace App\Controller;

use App\Entity\Army;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArmyController extends AbstractController
{
    /**
     * @Route("/army/add", name="add-army")
     */
    public function addArmy()
    {
    }
}
