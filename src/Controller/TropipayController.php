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
        private UsuarioContraRepository $usuarioContra;
        private OtraInfoRepository $otraInfoRepo;
        public function __construct(UsuarioContraRepository $usuarioContra, OtraInfoRepository $otraInfoRepo, 
                                    HttpClientInterface $httpClient) {
            $this->usuarioContra=$usuarioContra;
            $this->httpClient=$httpClient;
            $this->otraInfoRepo=$otraInfoRepo;
        }

        #[Route('/met_tropipay/{met_pago}', name: 'app_met_tropipay')]
        public function pagotropipay(string $met_pago): JsonResponse
        {
            $name = $this->getUrl();
            return new JsonResponse(['data'=>$name]);

        }

        #[Route('/getToken', name: 'app_getToken')]
        private function getToken()
        {
            $tropipay= $this->usuarioContra->findOneBy(['usuario'=>'aherreramilet@gmail.com']);
            $tropipay_userName= $tropipay->getUsuario();
            $tropipay_Pasword= $tropipay->getContraseña();

            $data= 
            '{
                "email":'.$tropipay_userName;
                '"password":'.$tropipay_Pasword;
            '}';
            
            echo $tropipay_userName. '<br> Salto <br>'. $tropipay_Pasword .'<br> Salto <br>'. $data;
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
            CURLOPT_POSTFIELDS =>$data,
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
            var_dump($array);
            $token2= $array[1];
                
            
            curl_close($curl);
            
      
            return $token2;
          }


        private function getUrl(){

            $token=$this->getToken();
            var_dump($token);
            $curl = curl_init();
            $postField= '{
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
                }';
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://tropipay-dev.herokuapp.com/api/paymentcards',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$postField,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.$token
            ),
            ));
        
            $response = curl_exec($curl);
            
            // Comprueba el código de estado HTTP
            if(empty($response))
            {
                echo '500 Internal Server Error';
                
            }
            
            $array = explode(",", $response);
            $array1=explode(":", $array[25]);
            $arr=$array1[1].':'.$array1[2];
                        
            curl_close($curl);
            return $arr;
        
        }

        
        


    }
