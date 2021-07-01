<?php

namespace App\Controller;
use App\Entity\Order;
use App\Entity\Client;
use App\Entity\AddressBilling;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use App\Form\ClientType;
use App\Form\AddressBillingType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;




class LandingPageController extends AbstractController
{
    /**
     * @Route("/", name="landing_page")
     * @throws \Exception
     */


    public function index(Request $request, ProductRepository $productRepository)
    {      
        $order= new Order();

     
       //Your code here LES LIGNES DESSOUS PERMETTENT DE CREER UN FORMULAIRE
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);    

          if ($form->isSubmitted() && $form->isValid()) { 
                               
         
            
            $productid=$request->get('id');
            $product=$productRepository->findOneBy(['id'=>$productid]);
            $order->setProduct($product); 
   
            $order->setStatus('WAITING');
            $client = $order->getClient();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();
            $adress = $order->getAddress();  
            $adress->setUser($client);
            $entityManager->persist($adress);
            // Permet de récuperer l'input
            $paymentid=$request->get('payment');
            // Recuperer l'objet Order pour ensuite inserer dans le setpayment
            $order->setPaymentMethod($paymentid);
            $entityManager->persist($order);
            $entityManager->flush();        
       if($request->get('payment')=='stripe'){
            return $this->redirectToRoute('stripe' ,[
                'id' => $order->getId(),
            
        ]);
    }
        else{
            return $this->redirectToRoute('paypal' ,[
                'id' => $order->getId(),
             
        ]);
    }
  }
        


       //PERMET DE RETOURNER ET DE CREER Les cartes DES PRODUITS dans l'index 
        return $this->render('landing_page/index.html.twig', [
            'products'=>$productRepository-> findall(),
        //PERMET DE CREER LA Vue DU FORMULAIRE
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
     * @Route("/{id}/stripe", name="stripe")
     */
    public function stripe(Order $order, ProductRepository $productRepository)
    {
      $product=$productRepository->findOneBy(['id'=>$order->getProduct()]);
  
      \Stripe\Stripe::setApiKey('sk_test_51IudYJE6zq9JtjMeKaLVqVeD5DU44TdEw2kFMuak62VLwymNNoUTQpvqJEgaHZCAzh10DAo6f6P9O4bJsLc5qzSY00IVms15NF');
      $paymentIntent = \Stripe\PaymentIntent::create([
          'price' => $product->getPrice()*100,
          'currency' => 'eur'
      ]);
      $output = [
          'clientSecret' => $paymentIntent->client_secret,
      ];
        return $this->render('landing_page/partials/stripe.html.twig',[

           'price'=>$product->getPrice() 
           
        ]
       );

      
    }

      /**
     * @Route("/paypal", name="paypal")
     */
    public function paypal()
    { 
        return $this->render('landing_page/partials/paypal.html.twig', [

        ]);
    }
}
