<?php
     
   namespace App\Controller;

   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\Routing\Annotation\Route;
   use Symfony\Component\HttpFoundation\JsonResponse; 
   
    class WellsfargoController extends AbstractController
    {
        #[Route('/met_wellsfargo/{met_pago}', name: 'app_met_well')]
        public function realizarpago(string $met_pago): JsonResponse
        {
            
            $name = $this->getToken();
            return new JsonResponse(['data'=>$name]);

        }

                    
        public function getToken()
        {   
            $wells_userName='CJzbqWy8sa6ZlLjVk1vz4RMyXpFBw83w';
            $wells_password='MoBrolXyouXe6fjf';
            $login= base64_encode($wells_userName.":".$wells_password);    
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-sandbox.wellsfargo.com/oauth2/v1/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials&scope=%7Bspace-delimited%20scopes%7D',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic '.$login
            ),
            ));

            $response = curl_exec($curl);

            $array= json_decode($response, true);

            $token=$array["access_token"];
            
            curl_close($curl);
            
            return $token;
        }    
        
    }
