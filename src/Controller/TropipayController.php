<?php
     
   namespace App\Controller;

   use App\Repository\OtraInfoRepository;
   use App\Repository\UsuarioContraRepository;
   use Exception;
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
            return new JsonResponse(['url'=>$name]);

        }

        #[Route('/getToken1', name: 'app_getToken1')]
        public function getToken1(){

            $tropipay= $this->usuarioContra->findOneBy(['usuario'=>'aherreramilet@gmail.com']);
            $tropipay_userName= $tropipay->getUsuario();
            $tropipay_Pasword= $tropipay->getContraseña();

            $post_user_pass= array(
                "email" => $tropipay_userName,
                "password"=> $tropipay_Pasword,
              );
            
            $response=$this->httpClient->request('POST','https://tropipay-dev.herokuapp.com/api/access/login',[
                                        'headers'=>array(
                                                   'Content-Type: application/json'
                                                ),
                                        'json'=>$post_user_pass        
            ]);
            
            $status=$response->getStatusCode();
            
            if($status !== 200){
                throw new Exception("Error Processing Request", 1);               
            }

            $content= $response->toArray();

            $token= $content["token"];
            
            return $token;
        }

        private function getUrl(){
        
            $token=$this->getToken1();

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

            $response=$this->httpClient->request('POST','https://tropipay-dev.herokuapp.com/api/paymentcards',[
                                                'headers'=>array(
                                                            'Content-Type: application/json',
                                                            'Authorization: Bearer '.$token
                                                        ),
                                                'json'=> $postField       
            ]);

            $status=$response->getStatusCode();
            
            if($status !== 200){
                   throw new Exception("Error Processing Request", 1);
                }
            $content= $response->toArray();

            $url_pago= $content["shortUrl"]; 
            
            return $url_pago;    

        }

               
        


    }
