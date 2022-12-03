<?php

   namespace App\Controller;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\Config\Definition\Exception\Exception;
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
            $create_order= $this->createOrder1();

            return new JsonResponse(['url'=>$create_order]);

        }

        //Funcion para obtener el token por el metodo http-client. La voy ha usar al final.
        #[Route('/token', name: 'app_getToken1')]
        public function getToken1()
        {
            $paypal= $this->keySaveRepo->findOneBy(['name'=>'Paypal']);
            $paypal_userName= $paypal->getApiKey1();
            $paypal_Pasword= $paypal->getApiKey2();
                        
            $login= base64_encode("$paypal_userName:$paypal_Pasword");
            
            $response= $this->httpClient->request('POST', 'https://api-m.sandbox.paypal.com/v1/oauth2/token',[
                                                  'headers'=>[
                                                            'Content-Type: application/x-www-form-urlencoded',
                                                            'Authorization: Basic '.$login
                                                            ],
                                                  'body' => 'grant_type=client_credentials&ignoreCache=true&return_authn_schemes=true&return_client_metadata=true&return_unconsented_scopes=true',
                                                ]);
            //Hago esto porque lo que devuelve el $response es un string, aun no se porque devuelve esto y no un json.
            $status= $response->getStatusCode();
            if($status !== 200){
                throw new Exception("Error Processing Request", 1);               
            }
            $content=$response->getContent();
            
            $response_toArray= json_decode($content, true);

            $token= $response_toArray['access_token'];
            
            return $token;
        }

        #[Route('/orden', name: 'app_getOrden')]
        public function createOrder1(){

            $token_acces= $this->getToken1();
            
            $otrainf= $this->otraInfoRepo->findOneBy(['nombre'=>'Pullover']);
            $otrainf_Nombre= $otrainf->getNombre();
            $otrainf_Descrip= $otrainf->getDescripcion();
            $otrainf_Cantidad= $otrainf->getCantidad();
            $otrainf_CodigoMoneda= $otrainf->getCodigoMoneda();
            $otrainf_MontoPagar= $otrainf->getMontoPagar();
            $otrainf_ReturnUrl= $otrainf->getReturnUrl();
            $otrainf_CancelUrl= $otrainf->getCancelUrl();

            $post_elem = array(
                "intent"=>"CAPTURE",
                "purchase_units"=> array(
                    array(
                        "items"=>array(
                               array(
                                    "name" => $otrainf_Nombre,
                                    "description"=> $otrainf_Descrip,
                                    "quantity"=> $otrainf_Cantidad,
                                    "unit_amount"=> array(
                                        "currency_code"=> $otrainf_CodigoMoneda,
                                        "value"=> $otrainf_MontoPagar,
                                    )
                                )
                            ),
                            "amount"=> array(
                                "currency_code"=> $otrainf_CodigoMoneda,
                                "value"=> $otrainf_MontoPagar,
                                "breakdown"=> array(
                                    "item_total"=> array(
                                        "currency_code"=> $otrainf_CodigoMoneda,
                                        "value"=> $otrainf_MontoPagar,
                                   )
                                )
                            )
                        )
                ),
                "application_context"=> array(
                    "return_url"=> $otrainf_ReturnUrl,
                    "cancel_url"=> $otrainf_CancelUrl
                   )
            );

            $response= $this->httpClient->request(
                                    'POST',
                                    'https://api-m.sandbox.paypal.com/v2/checkout/orders',
                                    ['headers'=>[
                                        'Content-Type:application/json',
                                        'Prefer:return=representation',
                                        'PayPal-Request-Id:f28f2a1e-aa49-4388-b904-092c47088bef',
                                        'Authorization: Bearer '.$token_acces
                                                ],
                                      'json'=> $post_elem        
                                            ]);
                                                                          
            $status= $response->getStatusCode();
            
            if($status !== 200){
                       throw new Exception("Error Processing Request", 1);               
            }
            
            $content= $response->toArray();

            $url_pago=$content["links"][1]["href"];
              
            return $url_pago;                                                              
        }

        
    }