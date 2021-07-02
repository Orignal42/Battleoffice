<?php

namespace App\Controller;
use App\Entity\Order;
use App\Entity\Product;
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
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\CachingHttpClient;
use Symfony\Component\HttpKernel\HttpCache\Store;




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
             $address = $order->getAddress(); 
             
            if ($address->getFirstname()==null && 
                $address->getLastname()==null && 
                $address->getAddressBillingLine1()==null && 
                $address->getAddressBillingLine2()==null && 
                $address->getPhone()==null &&
                $address->getCity()==null && 
                $address->getZipcode()==null){

                $address->setFirstname( $order->getClient()->getFirstname());
                $address->setLastname( $order->getClient()->getLastname());
                $address->setAddressBillingLine1( $order->getClient()->getAddressLine1());
                $address->setAddressBillingLine2( $order->getClient()->getAddressLine2());
                $address->setPhone( $order->getClient()->getPhone());
                $address->setCountry( $order->getClient()->getCountry());
                $address->setCity( $order->getClient()->getCity());
                $address->setZipcode( $order->getClient()->getZipcode());
            
               
            }

            $entityManager->persist($address);
                       // Permet de récuperer l'input
            $paymentid=$request->get('payment');
            // Recuperer l'objet Order pour ensuite inserer dans le setpayment
            $order->setPaymentMethod($paymentid);
            $entityManager->persist($order);
            $entityManager->flush();
        
            
            if($request->get('payment')=='stripe'){
            $content = $this->HTTP($client, $address, $product, $order);         $order->setIdApiResponse($content['order_id']);
            
        
            $entityManager->persist($order);
            $entityManager->flush();
                
                
                return $this->redirectToRoute('stripe' ,[
                        'id' => $order->getId(),
                    
                ]);
            }else{
            //  $this->HTTP($client, $address, $product, $order);
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
     * @Route("/stripe/{id}", name="stripe")
     */
    public function stripe(Request $request,Order $order, ProductRepository $productRepository)
    {   
       
      $product=$productRepository->findOneBy(['id'=>$order->getProduct()]);
        return $this->render('landing_page/partials/stripe.html.twig',[
               
           'price'=>$product->getPrice() ,
           'order'=>$order 
           
        ]
       );
    }
     /**
     * @Route("/stripeprocess/{id}", name="stripe_process")
     */
    public function stripe_process(Request $request,Order $order, ProductRepository $productRepository)
    {   
       
      $product=$productRepository->findOneBy(['id'=>$order->getProduct()]);
      \Stripe\Stripe::setApiKey('sk_test_51IudYJE6zq9JtjMeKaLVqVeD5DU44TdEw2kFMuak62VLwymNNoUTQpvqJEgaHZCAzh10DAo6f6P9O4bJsLc5qzSY00IVms15NF');
      $paymentIntent = \Stripe\PaymentIntent::create([
          'amount' => $product->getPrice()*100,
          'currency' => 'eur'
      ]);
      $order->setStatus('PAID');
      $this->PAID($order);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($order);
      $entityManager->flush();



      return $this->redirectToRoute('confirmation');
    }
      /**
     * @Route("/paypal{id}", name="paypal")
     */
    public function paypal(Order $order, ProductRepository $productRepository)

    {   $product=$productRepository->findOneBy(['id'=>$order->getProduct()]);
        return $this->render('landing_page/partials/paypal.html.twig', [
            'price'=>$product->getPrice() 
        ]);
      
    }

    public function HTTP(Client $client, AddressBilling $Address, Product $product, Order $order)
    {
        $token = 'mJxTXVXMfRzLg6ZdhUhM4F6Eutcm1ZiPk4fNmvBMxyNR4ciRsc8v0hOmlzA0vTaX';
        $data = [
            "order" => [
                "id" => "1",
                "product" => $product->getName(),
                "payment_method" => $order->getPaymentMethod(),
                "status" => $order->getStatus(),
                "client" => [
                    "firstname" => $client->getFirstname(),
                    "lastname" => $client->getLastname(),
                    "email" => $client->getEmail()
                ],
                "addresses" => [
                    "billing" => [
                        "address_line1" => $Address->getAddressBillingLine1(),
                        "address_line2" =>  $Address->getAddressBillingLine2(),
                        "city" => $Address->getCity(),
                        "zipcode" => $Address->getZipcode(),
                        "country" => $Address->getCountry(),
                        "phone" => $Address->getPhone()
                    ],
                    "shipping" => [
                        "address_line1" => $client->getAddressLine1(),
                        "address_line2" => $client->getAddressLine2(),
                        "city" => $client->getCity(),
                        "zipcode" => $client->getZipcode(),
                        "country" => $client->getCountry(),
                        "phone" => $client->getPhone(),
                    ]
                ]
            ]
        ];
        $data = json_encode($data);
        $httpClient = HttpClient::create();
        $response = $httpClient->request('POST', 'https://api-commerce.simplon-roanne.com/order', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
            'body' => $data
        ]);

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        return $content;
    }

    public function PAID( Order $order)
    {   
        $token = 'mJxTXVXMfRzLg6ZdhUhM4F6Eutcm1ZiPk4fNmvBMxyNR4ciRsc8v0hOmlzA0vTaX';
        $data = [
                "status" => $order->getStatus()
        ]; 
    
        $data = json_encode($data);
        $httpClient = HttpClient::create();
        $response = $httpClient->request('POST', 'https://api-commerce.simplon-roanne.com/order/'. $order->getIdApiResponse()."/status", [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
            'body' => $data
        ]);

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content2 = $response->getContent();
        $content2 = $response->toArray();
     
        return $content2;
    }

}

       


