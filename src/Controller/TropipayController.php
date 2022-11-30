<?php
     
   namespace App\Controller;

   use App\Repository\OtraInfoRepository;
   use App\Repository\UsuarioContraRepository;
   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\Routing\Annotation\Route;
   use Symfony\Component\HttpFoundation\Request;
   use Symfony\Component\HttpFoundation\JsonResponse; 
   use Symfony\Contracts\HttpClient\HttpClientInterface;


    class TropipayController extends AbstractController
    {   
        private HttpClientInterface $httpClient;
        public function __construct(UsuarioContraRepository $usuarioContra, OtraInfoRepository $otraInfoRepository, 
                                    HttpClientInterface $httpClient) {
            $this->usuarioContra=$usuarioContra;
            $this->httpClient=$httpClient;
            $this->otraInfoRepo=$otraInfoRepository;
        }

        #[Route('/met_tropipay/{met_pago}', name: 'app_met_tropipay')]
        public function pagotropipay(Request $request, string $met_pago): JsonResponse
        {
            $name = $this->getUrl();
            return new JsonResponse(['data'=>'Hola ' . $met_pago. ' '.$name]);


        }

        #[Route('/nueva', name: 'app_getToken')]
        private function getToken()
        {

            $tropipay= $this->usuarioContra->findOneBy(['nombre_metodo'=>'Tropipay']);
            $tropipay_userName= $tropipay->getUsuario();
            $tropipay_Pasword= $tropipay->getContraseña();
            
            echo $tropipay_userName. '<br> Salto <br>'. $tropipay_Pasword;
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
            CURLOPT_POSTFIELDS =>
            '{
                 "email":'.$tropipay_userName,
                 '"password":'.$tropipay_Pasword,
             '}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            ));
            
            $response = curl_exec($curl);
            
            // Comprueba el código de estado HTTP
            if(empty($response))
            {
                echo 'Status Code: 500 Please Review';
            }
            
            $array = json_decode($response);
            
            $token2= $array[1];
                
            var_dump($array);
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
            
            // Comprueba el código de estado HTTP
            if(curl_exec($curl) === false)
            {
                echo 'Status Code: ' . curl_error($curl). 'Please Review';
            }
            else
            {
                echo 'Operación completada sin errores';
            }
            
            $array = explode(",", $response);
            $array1=explode(":", $array[25]);
            $arr=$array1[1].':'.$array1[2];
                        
            curl_close($curl);
            return $arr;
        
        }

        
        


    }
