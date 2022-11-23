<?php
   
   namespace App\Controller;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\Routing\Annotation\Route;
   use Symfony\Component\HttpFoundation\Response;
   use Symfony\Component\HttpFoundation\Request;
   use Symfony\Component\HttpFoundation\JsonResponse; 
   use Doctrine\ORM\EntityManagerInterface;
   use App\Entity\KeysSave;
use Doctrine\Common\Collections\Expr\Value;

   class PrincipalController extends AbstractController
   {
     
    #[Route('/', name: 'app_home')]
    public function index(): JsonResponse
    {
      
      return new JsonResponse(['data'=>'Hola']);
    } 

    #[Route('/{met_pago}', name:'app_met_mercado')]
     public function obtener_metodo_pago($met_pago)
     {   
        
         $tipos_Metodo= ['tropipay', 'wellsfargo', 'paypal', 'mercadopago', 'amazon', 'redsys', 'mollie'];
         foreach($tipos_Metodo as $value){
            if($met_pago != $value && !in_array($met_pago, $tipos_Metodo)){
               echo "Entre un metodo de pago correcto...";
              }else{
               // echo "Este es el metodo de pago " .$met_pago;
               switch ($met_pago) {
                  case $met_pago== 'tropipay':
                     return new JsonResponse($this->forward('App\Controller\TropipayController::pagotropipay', [
                        'met_pago' => $met_pago
                     ]));
                     
                      break;
                  case $met_pago== 'wellsfargo':
                     return new Response($this->forward('App\Controller\WellsfargoController::realizarpago', [
                        'met_pago' => $met_pago
                     ]));
                     
                      break;
                  case $met_pago== 'paypal':
                     return new Response($this->forward('App\Controller\PaypalController::realizarpago', [
                        'met_pago' => $met_pago
                     ]));
                       break;
                  case $met_pago== 'mercadopago':
                     return new Response($this->forward('App\Controller\MercadopagoController::realizarpago', [
                        'met_pago' => $met_pago
                     ]));
                       break;
                  case $met_pago== 'amazon':
                     return new Response($this->forward('App\Controller\AmazonController::realizarpago', [
                        'met_pago' => $met_pago
                     ]));          
                      break;
                  case $met_pago== 'redsys':
                     return new Response($this->forward('App\Controller\MollieController::realizarpago', [
                        'met_pago' => $met_pago
                     ]));          
                       break;
                  case $met_pago== 'mollie':
                     return new Response($this->forward('App\Controller\RedsysController::realizarpago', [
                        'met_pago' => $met_pago
                     ]));          
                        break;         
              }
               
            }break;
         }   
             

     }

      /**
     * @Route("/api/createapi", name="create_api_key")
     *  
     */
     public function createApiKey(Request $request, EntityManagerInterface $em){

        $key= new KeysSave();
        $response = new JsonResponse();
        $key->setName('wellsfargo');
        $key->setApiKey1('NXp43wxU5JMX6dGF');
        $key->setApiKey2('PVqOVoMm0CMdmwHxcXQ77ABISW9guX1N');
        $em->persist($key);
        $em->flush();
        $response->setData([
            'success'=>true,
            'data'=>[
                 [
                   'id'=>$key->getId(),
                   'nombre'=>$key->getName(),
                   'apiKey1'=>$key->getApiKey1(),
                   'apiKey2'=>$key->getApiKey2()
                 ]                 
            ]
            
       ]);
       return $response;
     }


   }


