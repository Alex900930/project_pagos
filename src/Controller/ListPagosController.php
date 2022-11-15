<?php
     namespace App\Controller;

     use App\Repository\KeysSaveRepository;
     use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
     use Symfony\Component\Routing\Annotation\Route;
     use Symfony\Component\HttpFoundation\JsonResponse;   

     class ListPagosController extends AbstractController
     {
          /**
           * @Route("/listpagos/mostrarpagos", name="mostrar_pagos")
           * 
           */
          public function mostrarpagos(KeysSaveRepository $keysSaveRepository){
               
               $keysSave = $keysSaveRepository->findAll();
               $apisSaveasArray= [];
               foreach($keysSave as $value)
                {
                    $apisSaveasArray= [
                          'id'=> $value->getId(),
                          'name'=> $value->getName(),
                          'apiKey1'=> $value->getApiKey1(),
                          'apiKey2'=> $value->getApiKey2()

                    ];
                }
               $response = new JsonResponse();
               $response->setData([
                    'success'=>true,
                    'data'=>$apisSaveasArray
                    
               ]);
               return $response;

          }
     }



?>
