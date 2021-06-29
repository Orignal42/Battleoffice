<?php

namespace App\Controller;

use App\Form\AddressBillingType;
use App\Form\ClientType;
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
        //Your code here
        $form = $this->createForm(ClientType::class);
        $client= new Client();
        $adress= new AddressBilling();
        return $this->render('landing_page/index.html.twig', [
            'products'=>$productRepository-> findall(),
            'form'=>$form->createView(),
            'client' => $client,
            'adressbilling'=>$adress
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
}
