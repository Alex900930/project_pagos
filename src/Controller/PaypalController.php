<?php
     
   namespace App\Controller;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\Routing\Annotation\Route;
   use Symfony\Component\HttpFoundation\Response;
   use Symfony\Component\HttpFoundation\Request;
   use Symfony\Component\HttpFoundation\JsonResponse; 
   use Doctrine\ORM\EntityManagerInterface;
   
   use App\Entity\KeysSave;
   use App\Repository\KeysSaveRepository;

    class PaypalController extends AbstractController
    {

        //private $keySaveRepo; 
        public function __construct(KeysSaveRepository $keySaveRepo) {
            $this->keySaveRepo=$keySaveRepo; ;
        }
         

        #[Route('/met/{met_pago}', name: 'app_met_paypal')]
        public function realizarpago(string $met_pago): JsonResponse
        {
            $create_order= $this->createOrder();
            
            return new JsonResponse(['data'=>'Hola, '.$create_order.', '. $met_pago]);
             
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
                echo '400 Bad Request';
                
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
                                "name": "T-Shirt",
                                "description": "Green XL",
                                "quantity": "1",
                                "unit_amount": {
                                    "currency_code": "USD",
                                    "value": "100.00"
                                }
                            }
                        ],
                        "amount": {
                            "currency_code": "USD",
                            "value": "100.00",
                            "breakdown": {
                                "item_total": {
                                    "currency_code": "USD",
                                    "value": "100.00"
                                }
                            }
                        }
                    }
                ],
                "application_context": {
                    "return_url": "https://example.com/return",
                    "cancel_url": "https://example.com/cancel"
                }
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Prefer: return=representation',
                'PayPal-Request-Id: f28f2a1e-aa49-4388-b904-092c47088bef',
                "Authorization: Bearer ".$token_acces
            ),
            ));
    
            $response = curl_exec($curl);
                  
            $array = explode(",", $response);
            print_r(gettype($array));
            $arr= explode(":", $array[19]);
            $url_pago=$arr[1].':'.$arr[2];
            
            curl_close($curl);
            
            return $url_pago;
        }    

        
    }
