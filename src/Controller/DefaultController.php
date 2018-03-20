<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {

        $flights = $this->getDoctrine()
            ->getRepository("App:Flight")
            ->findAll();
        $hotels = $this->getDoctrine()
            ->getRepository("App:Hotel")
            ->findAll();


        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'flights' => $flights,
            'hotels' => $hotels
        ]);
    }
}
