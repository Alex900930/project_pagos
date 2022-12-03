<!-- 

 PHP: PrincipalController.php
 BASE DE DATOS: pagos_bd

-->
<?php
   
   namespace App\Controller;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\Routing\Annotation\Route;
   use Symfony\Component\HttpFoundation\Response;
   use Symfony\Component\HttpFoundation\Request;
   use Symfony\Component\HttpFoundation\JsonResponse; 
   use Doctrine\ORM\EntityManagerInterface;
   use App\Entity\KeysSave;


   class PrincipalController extends AbstractController
   {
    
   //    * Funcion inicial o raiz
   //    *
   //    * @return un json
   
    #[Route('/', name: 'app_home')]
    public function index(): JsonResponse
    {
      
      return new JsonResponse(['data'=>'Hola']);
    } 

   // 
   // * Obtiene el tipo de metodo de pago, pasado en la ruta y llama al controlador de dicho metodo y lo ejecuta.
   // *
   // * @return Otro Controlador
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
                     return ($this->forward('App\Controller\TropipayController::pagotropipay', [
                        'met_pago' => $met_pago
                     ]));
                     
                  case $met_pago== 'wellsfargo':
                     return ($this->forward('App\Controller\WellsfargoController::realizarpago', [
                        'met_pago' => $met_pago
                     ]));
                  
                  case $met_pago== 'paypal':
                     return ($this->forward('App\Controller\PaypalController::realizarpago', [
                        'met_pago' => $met_pago
                     ]));
                       
                  case $met_pago== 'mercadopago':
                     return ($this->forward('App\Controller\MercadopagoController::realizarpago', [
                        'met_pago' => $met_pago
                     ]));
                       
                  case $met_pago== 'amazon':
                     return new Response($this->forward('App\Controller\AmazonController::realizarpago', [
                        'met_pago' => $met_pago
                     ]));          
                     
                  case $met_pago== 'redsys':
                     return ($this->forward('App\Controller\MollieController::realizarpago', [
                        'met_pago' => $met_pago
                     ]));          
                     
                  case $met_pago== 'mollie':
                     return ($this->forward('App\Controller\RedsysController::realizarpago', [
                        'met_pago' => $met_pago
                     ]));          
                                 
              }
               
            }
         }   
             

     }
         
   /**
    * Funcion contructor de la Clase.
    */
   public function __construct() {
   }
}


