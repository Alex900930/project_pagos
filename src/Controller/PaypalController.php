<?php
     
   namespace App\Controller;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\Routing\Annotation\Route;
   use Symfony\Component\HttpFoundation\Response;
   use App\Controller\PaypalPago;
   use Symfony\Component\HttpFoundation\Request;
   use Symfony\Component\HttpFoundation\JsonResponse; 
   use Doctrine\ORM\EntityManagerInterface;
   use App\Entity\KeysSave;


    class PaypalController extends AbstractController
    {
        private $paypalPago;
        public function __construct(PaypalPago $paypalPago){
            $this->paypalPago= $paypalPago;
        }
        
        /**
        * @Route("/pago_paypal/{met_pago}", name="metodo_pago")
        *  
        */
        public function pago_paypal($met_pago){
            
            
            $create_order= $this->paypalPago->createOrder();
            return new Response('Esta es la orden: '.$create_order);
             
        }

        
    }
?>