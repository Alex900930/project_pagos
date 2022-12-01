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

            $post_user_pass= array(
                                    "email" => $tropipay_userName,
                                    "password"=> $tropipay_Pasword,
                                  );

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
            CURLOPT_POSTFIELDS => json_encode($post_user_pass, JSON_UNESCAPED_SLASHES),
            CURLOPT_HTTPHEADER => 
                array(
                    'Content-Type: application/json'
                ),
                ));

            $response = curl_exec($curl);

            curl_close($curl);
                        
            // Comprueba el código de estado HTTP
            if(empty($response))
            {
                echo 'Status Code: 500 Please Review';
            }
            
            $array = json_decode($response, true);
            
            $token= $array["token"];
                       
            return $token;
          }


        private function getUrl(){

            $token=$this->getToken();

            $otraInfo=$this->otraInfoRepo->findOneBy(['nombre'=>'Motorcycle']);
            $referencia=$otraInfo->getReferencia();
            $concepto=$otraInfo->getNombre();
            $descripcion=$otraInfo->getDescripcion();
            $amount=$otraInfo->getMontoPagar();
            $currency=$otraInfo->getCodigoMoneda();
            $singleuse=$otraInfo->getTipoUso();
            $reasonId=$otraInfo->getReasonId();
            $expirationDays=$otraInfo->getExpiraDias();
            $lang=$otraInfo->getLenguaje();
            $urlSuccess=$otraInfo->getReturnUrl();
            $urlFailed=$otraInfo->getCancelUrl();
            $urlNotification=$otraInfo->getNotificacionUrl();

            $curl = curl_init();
            $postField= array(
                "reference"=> $referencia,
                "concept"=> $concepto,
                "description"=>$descripcion,
                "amount"=> $amount,
                "currency"=> $currency,
                "singleUse"=> $singleuse,
                "reasonId"=> $reasonId,
                "expirationDays"=> $expirationDays,
                "lang"=>$lang,
                "urlSuccess"=> $urlSuccess,
                "urlFailed"=> $urlFailed,
                "urlNotification"=> $urlNotification
            );
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://tropipay-dev.herokuapp.com/api/paymentcards',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode($postField,JSON_UNESCAPED_SLASHES),
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
            
            $array = json_decode($response, true);
            
            $url_pago= $array["shortUrl"];
                        
            curl_close($curl);

            return $url_pago;
        
        }

        
        


    }
