<?php

namespace App\Controller;

use App\Form\OrderType;
use App\Form\ClientType;
use App\Form\AddressBillingType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Client;
use App\Entity\AddressBilling;


class LandingPageController extends AbstractController
{
    /**
     * @Route("/", name="landing_page")
     * @throws \Exception
     */
    public function index(Request $request, ProductRepository $productRepository, ClientType $clients)
    {
        //Your code here LES LIGNES DESSOUS PERMETTENT DE CREER UN FORMULAIRE
        $form = $this->createForm(OrderType::class);
        $form->handleRequest($request);
       //PERMET DE RETOURNER ET DE CREER LA VIEW DES PRODUITS
        return $this->render('landing_page/index.html.twig', [
            'products'=>$productRepository-> findall(),
//PERMET DE CREER LA VIEW DU FORMULAIRE
       'form'=>$form->createView()
        
        ]);
    }
    /**
     * @Route("/confirmation", name="confirmation")
     */
    public function confirmation()
    {
        return $this->render('landing_page/confirmation.html.twig', [

        ]);
    }


     /**
     * @Route("/stripe", name="stripe")
     */
    public function stripe()
    {
        return $this->render('landing_page/partials/stripe.html.twig', [

        ]);
    }
}
