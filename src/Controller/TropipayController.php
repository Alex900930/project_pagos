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
            $name = $this->getUrl();
            return new JsonResponse(['data'=>'Hola ' . $met_pago. ' '.$name]);


        }

        private function getToken(){

            $curl = curl_init();
      
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://tropipay-dev.herokuapp.com/api/access/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
            "email": "aherreramilet@gmail.com",
            "password": "Harold*1845"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            ));
      
            $response = curl_exec($curl);
            $array = explode(",", $response);
            $token1=explode(":", $array[0]);
            $token2= str_replace('"', '', $token1[1]);
            
            
            curl_close($curl);
            
      
            return $token2;
          }


        private function getUrl(){

            $token=$this->getToken();
            $curl = curl_init();
        
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://tropipay-dev.herokuapp.com/api/paymentcards',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
            "reference": "your-own-system-reference",
            "concept": "test",
            "description": "test",
            "amount": 100000,
            "currency": "EUR",
            "singleUse": false,
            "reasonId": 2,
            "expirationDays": 1,
            "lang": "es",
            "urlSuccess": "https://mi-negocio.com/pago-ok",
            "urlFailed": "https://mi-negocio.com/pago-ko",
            "urlNotification": "https://mi-negocio.com/notificacion-pago"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.$token
            ),
            ));
        
            $response = curl_exec($curl);
            
            $array = explode(",", $response);
            $array1=explode(":", $array[25]);
            $arr=$array1[1].':'.$array1[2];
            //print_r($arr);
                        
            curl_close($curl);
            return $arr;
        
        }

        
        


    }
