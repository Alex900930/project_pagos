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
        private KeysSaveRepository $keySaveRepo;
        private OtraInfoRepository $otraInfoRepo;
        public function __construct(KeysSaveRepository $keySaveRepo, OtraInfoRepository $otraInfoRepo,
                                    HttpClientInterface $httpClient) {
            $this->keySaveRepo=$keySaveRepo;
            $this->httpClient=$httpClient;
            $this->otraInfoRepo=$otraInfoRepo;
        }

        #[Route('/met/{met_pago}', name: 'app_met_paypal')]
        public function realizarpago(string $met_pago): JsonResponse
        {
            $create_order= $this->createOrder();

            return new JsonResponse(['data'=>$create_order]);

        }

        //Funcion para obtener el token por el metodo http-client. La voy ha usar al final.
        #[Route('/token', name: 'app_getToken1')]
        public function getToken1()
        {
            $paypal= $this->keySaveRepo->findOneBy(['name'=>'Paypal']);
            $paypal_userName= $paypal->getApiKey1();
            $paypal_Pasword= $paypal->getApiKey2();

            echo $paypal_userName. '<br> Salto <br>'. $paypal_Pasword;

            $login= base64_encode("$paypal_userName:$paypal_Pasword");


            echo "\n". '<br> Esto es un salto <br>'.$login;

            $response= $this->httpClient->request('POST', 'https://api-m.sandbox.paypal.com/v1/oauth2/token',[
                                                  'headers'=>[
                                                            'Content-Type: application/x-www-form-urlencoded',
                                                            'Authorization: Basic '.$login
                                                            ],
                                                  'body' => 'grant_type=client_credentials&ignoreCache=true&return_authn_schemes=true&return_client_metadata=true&return_unconsented_scopes=true',
                                                ]);
            //Hago esto porque lo que devuelve el $response es un string, aun no se porque devuelve esto y no un json.
            // $response_toArray= json_decode($response);
            // return new JsonResponse($response_toJson);
        }

        private function getToken(): string
        {

            $paypal= $this->keySaveRepo->findOneBy(['name'=>'Paypal']);
            $paypal_userName= $paypal->getApiKey1();
            $paypal_Pasword= $paypal->getApiKey2();

            $login= base64_encode($paypal_userName.":".$paypal_Pasword);

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
                'Authorization: Basic '.$login
            ),
            ));

            $response = curl_exec($curl);

            // Comprueba el código de estado HTTP
            if(empty($response))
            {
                echo '500 Internal Server Error';

            }

            $array = json_decode($response, true);

            $token_acces= $array['access_token'] ;

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

            $post_elem = array(
                "intent"=>"CAPTURE",
                "purchase_units"=> array(
                    array(
                        "items"=>array(
                               array(
                                    "name" => $nombre_string,
                                    "description"=> $descrip_string,
                                    "quantity"=> $cantidad_string,
                                    "unit_amount"=> array(
                                        "currency_code"=> $codigoMoneda_string,
                                        "value"=> $montoPagar_string,
                                    )
                                )
                            ),
                            "amount"=> array(
                                "currency_code"=> $codigoMoneda_string,
                                "value"=> $montoPagar_string,
                                "breakdown"=> array(
                                    "item_total"=> array(
                                        "currency_code"=> $codigoMoneda_string,
                                        "value"=> $montoPagar_string,
                                   )
                                )
                            )
                        )
                ),
                "application_context"=> array(
                    "return_url"=> $returnUrl_string,
                    "cancel_url"=> $cancelUrl_string
                   )
            );

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api-m.sandbox.paypal.com/v2/checkout/orders",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($post_elem,JSON_UNESCAPED_SLASHES),
            CURLOPT_HTTPHEADER => array(
                'Content-Type:application/json',
                'Prefer:return=representation',
                'PayPal-Request-Id:f28f2a1e-aa49-4388-b904-092c47088bef',
                'Authorization: Bearer '. $token_acces
              )
            ));

            $response = curl_exec($curl);
            //El Objeto que se devuelve en $response se descodifica a un Array, para poder trabajar con su info. 
            $array = json_decode($response, true);
            //Obteniendo el valor del link, donde se va ha pagar.
            $array_value= $array["links"][1]["href"];
             
            $url_pago= $array_value;

            curl_close($curl);

            return $url_pago;
        }


    }
