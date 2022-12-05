<?php
     
   namespace App\Controller;

   use App\Repository\KeysSaveRepository;
   use App\Repository\OtraInfoRepository;
   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\Routing\Annotation\Route;
   use Symfony\Component\HttpFoundation\JsonResponse;
   use Symfony\Contracts\HttpClient\HttpClientInterface; 
   
    class WellsfargoController extends AbstractController
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

        #[Route('/met_wellsfargo/{met_pago}', name: 'app_met_well')]
        public function realizarpago(string $met_pago): JsonResponse
        {
            
            $name = $this->getToken();
            return new JsonResponse(['data'=>$name]);

        }

                    
        public function getToken()
        {   
            $wellsfargo= $this->keySaveRepo->findOneBy(['name'=>'Wellsfargo']);
            $wells_userName=$wellsfargo->getApiKey1();
            $wells_password=$wellsfargo->getApiKey2();
            $login= base64_encode($wells_userName.":".$wells_password);    
           
            $response=$this->httpClient->request('POST', 'https://api-sandbox.wellsfargo.com/oauth2/v1/token',[
                                                  'headers'=> array(
                                                    'Content-Type: application/x-www-form-urlencoded',
                                                    'Authorization: Basic '.$login),
                                                    'body'=>'grant_type=client_credentials&scope=%7Bspace-delimited%20scopes%7D'
            ]);
            
            $content= $response->toArray();

            $token= $content['access_token'];
            
            return $token;
        }    
        
    }
