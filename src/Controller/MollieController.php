<?php
     
   namespace App\Controller;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\Routing\Annotation\Route;
   use Symfony\Component\HttpFoundation\Response;
   use Symfony\Component\HttpFoundation\Request;
   use Symfony\Component\HttpFoundation\JsonResponse; 
   use Doctrine\ORM\EntityManagerInterface;
   use App\Entity\KeysSave;


    class MollieController extends AbstractController
    {
        #[Route('/met_mollie/{met_pago}', name: 'app_met_mollie')]
        public function realizarpago(Request $request, string $met_pago): JsonResponse
        {
            
            return new JsonResponse(['data'=>'Hola ' .$met_pago]);

        }

        
    }
