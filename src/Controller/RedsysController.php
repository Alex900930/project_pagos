<?php
     
   namespace App\Controller;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\Routing\Annotation\Route;
   use Symfony\Component\HttpFoundation\Response;
   use Symfony\Component\HttpFoundation\Request;
   use Symfony\Component\HttpFoundation\JsonResponse; 
   use Doctrine\ORM\EntityManagerInterface;
   use App\Entity\KeysSave;


    class RedsysController extends AbstractController
    {
        #[Route('/met_redsys/{met_pago}', name: 'app_met_redsys')]
        public function realizarpago(Request $request, string $met_pago): JsonResponse
        {
            
            return new JsonResponse(['data'=>'Hola ' .$met_pago]);

        }

        
    }
