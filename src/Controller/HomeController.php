<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Sav;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(Request $request): Response
    {
        $numero = $request->request->get('numero');
        $commande = null;
        $progress  = 0;

        if ($numero != null) {
            $numero = trim($numero);
            $commande = $this->getDoctrine()->getRepository(Commande::class)->findOneBy(['numero' => $numero]);
        }



        return $this->render('home/index.html.twig', ['numero' => $numero, 'cmd' => $commande]);
    }
}