<?php
     
   namespace App\Controller;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\Routing\Annotation\Route;
   use Symfony\Component\HttpFoundation\Response;
   use Symfony\Component\HttpFoundation\Request;
   use Symfony\Component\HttpFoundation\JsonResponse; 
   use Doctrine\ORM\EntityManagerInterface;
   use App\Entity\KeysSave;


    class TropipayController extends AbstractController
    {
        /**
        * @Route("/realizar_pago/{met_pago}", name="metodo_pago")
        *  
        */
        public function realizarpago($met_pago){
            $response= new Response();
            $response = $response->setContent('Metodo de pago '. $met_pago. ' realizandose...');
            return $response;


        }


    }
?>