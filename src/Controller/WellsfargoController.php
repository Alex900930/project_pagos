<?php
     
   namespace App\Controller;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\Routing\Annotation\Route;
   use Symfony\Component\HttpFoundation\Response;
   use Symfony\Component\HttpFoundation\Request;
   use Symfony\Component\HttpFoundation\JsonResponse; 
   use Doctrine\ORM\EntityManagerInterface;
   use App\Entity\KeysSave;


    class WellsfargoController extends AbstractController
    {
        #[Route('/met_wellsfargo/{met_pago}', name: 'app_met_well')]
        public function realizarpago(Request $request, string $met_pago): JsonResponse
        {
            
            $name = $this->metod1();
            return new JsonResponse(['data'=>'Hola ' .$name . ' '.$met_pago]);

        }

        private function metod1(){
            return 'Pepe';
        }
        
    }
