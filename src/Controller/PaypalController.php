<?php
     
   namespace App\Controller;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\Routing\Annotation\Route;
   use Symfony\Component\HttpFoundation\Response;
   use Symfony\Component\HttpFoundation\Request;
   use Symfony\Component\HttpFoundation\JsonResponse;
   use Symfony\Contracts\HttpClient\HttpClientInterface;
   use Doctrine\ORM\EntityManagerInterface;
   
   use App\Entity\KeysSave;
   use App\Repository\KeysSaveRepository;
   use App\Repository\OtraInfoRepository;

    class PaypalController extends AbstractController
    {

        private HttpClientInterface $httpClient;
        public function __construct(KeysSaveRepository $keySaveRepo, OtraInfoRepository $otraInfoRepository, 
                                    HttpClientInterface $httpClient) {
            $this->keySaveRepo=$keySaveRepo;
            $this->httpClient=$httpClient;
            $this->otraInfoRepo=$otraInfoRepository;
        }
         
        #[Route('/met/{met_pago}', name: 'app_met_paypal')]
        public function realizarpago(string $met_pago): JsonResponse
        {
            $create_order= $this->createOrder();
            
            return new JsonResponse(['data'=>'Hola, '.$create_order.', '. $met_pago]);
             
        }
        
        //Funcion para obtener el token por el metodo http-client. La voy ha usar al final.
        #[Route('/token', name: 'app_met_a')]
        public function getToken1()
        {   
            $paypal= $this->keySaveRepo->findOneBy(['name'=>'Paypal']);
            $paypal_t= $paypal->getApiKey3();
            $paypal_string=strval($paypal_t);

            $requestData = ['grant_type=client_credentials&ignoreCache=true&return_authn_schemes=true&return_client_metadata=true&return_unconsented_scopes=true'];
            $expectedRequestData = json_encode($requestData, JSON_THROW_ON_ERROR);



            $response= $this->httpClient->request('POST', 'https://api-m.sandbox.paypal.com/v1/oauth2/token',[ 
                                                  'headers'=>[
                                                    'Content-Type: application/x-www-form-urlencoded',
                                                    'Authorization: Basic '.$paypal_string],
                                                    'body' => $expectedRequestData
                                                ]);
            return new JsonResponse($response);                                    
        }

        private function getToken(): string
        {   
            
            $paypal= $this->keySaveRepo->findOneBy(['name'=>'Paypal']);
            $paypal_t= $paypal->getApiKey3();
            $paypal_string=strval($paypal_t);

            $curl = curl_init();
            
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-m.sandbox.paypal.com/v1/oauth2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials&ignoreCache=true&return_authn_schemes=true&return_client_metadata=true&return_unconsented_scopes=true',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic '.$paypal_string
            ),
            ));
        
            $response = curl_exec($curl);
            // Comprueba el código de estado HTTP
            if($response == null)
            {
                echo '500 Internal Server Error';
                
            }
           
            
            $array = explode(",", $response);
            $token=explode(":", $array[1]);
            $token_acces= str_replace('"', '', $token[1]);
            
            
            curl_close($curl);
           
            return $token_acces;
        }
            
        public function createOrder(): string
        {   
            $token_acces= $this->getToken();

            $otrainf= $this->otraInfoRepo->findOneBy(['nombre'=>'Hat']);
            $otrainf_Nombre= $otrainf->getNombre();
            $otrainf_Descrip= $otrainf->getDescripcion();
            $otrainf_Cantidad= $otrainf->getCantidad();
            $otrainf_CodigoMoneda= $otrainf->getCodigoMoneda();
            $otrainf_MontoPagar= $otrainf->getMontoPagar();
            $otrainf_ReturnUrl= $otrainf->getReturnUrl();
            $otrainf_CancelUrl= $otrainf->getCancelUrl();

            $nombre_string=strval($otrainf_Nombre);
            $descrip_string=strval($otrainf_Descrip);
            $cantidad_string=strval($otrainf_Cantidad);
            $codigoMoneda_string=strval($otrainf_CodigoMoneda);
            $montoPagar_string=strval($otrainf_MontoPagar);
            $returnUrl_string=strval($otrainf_ReturnUrl);
            $cancelUrl_string=strval($otrainf_CancelUrl);
        

            $curl = curl_init();
             
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-m.sandbox.paypal.com/v2/checkout/orders',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "intent": "CAPTURE",
                "purchase_units": [
                    {
                        "items": [
                            {
                                "name":'. $nombre_string,
                                '"description":'.$descrip_string,
                                '"quantity": '.$cantidad_string,
                                '"unit_amount": {
                                    "currency_code":'.$codigoMoneda_string,
                                    '"value": '.$montoPagar_string,
                                '}
                            }
                        ],
                        "amount": {
                            "currency_code":'.$codigoMoneda_string,
                            '"value": '.$montoPagar_string,
                            '"breakdown": {
                                "item_total": {
                                    "currency_code": '.$codigoMoneda_string,
                                    '"value": '.$montoPagar_string,
                               ' }
                            }
                        }
                    }
                ],
                "application_context": {
                    "return_url": '.$returnUrl_string,
                    '"cancel_url": '.$cancelUrl_string,
               ' }
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Prefer: return=representation',
                'PayPal-Request-Id: f28f2a1e-aa49-4388-b904-092c47088bef',
                "Authorization: Bearer ". $token_acces,
            ),
            ));
    
            $response = curl_exec($curl);
                  
            $array = explode(",", $response);
            print_r($array);
            $arr= explode(":", $array[19]);
            $url_pago=$arr[1].':'.$arr[2];
            
            curl_close($curl);
            
            return $url_pago;
        }    

        
    }
