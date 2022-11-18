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
        #[Route('/met_tropipay/{met_pago}', name: 'app_met_tropipay')]
        public function pagotropipay(Request $request, string $met_pago): JsonResponse
        {
            $name = $this->metod1();
            return new JsonResponse(['data'=>'Hola ' . $met_pago. ' '.$name]);


        }

        private function metod1(){
            return 'Pepe';
        }


    }
