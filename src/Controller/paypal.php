<?php
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
    'Authorization: Basic QVl0WVhGQmJUX05XcUs4cWs2ZnVYTk92cGNzbldqU0xiVmN4VlljMlZsTVFVM1FHU2lKWVhfSlJkSXV5QWlhV0c4UVU5SUdFbklIbE1MU3c6RUdyc3Y1VGhuZnQ0RzdGOTdPcVJRSFlOQU1sSlR3eTl2ZGF4YkwxNUl5OFJCOXZZLVU4Q001U0pDTGZYS0F3SV9uUXJZN1RLSjRQYWZZcUc=',
    'Content-Type: application/x-www-form-urlencoded'
  ),
));

$response = curl_exec($curl);
$array = explode(",", $response);
$token=explode(":", $array[1]);
$token_acces= str_replace('"', '', $token[1]);

//echo '<br><br> Este es el token: '. $token_acces. ' <br><br>';
curl_close($curl);
//echo $response;

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
$arr=explode(":", $array[19]);
$url_pago=$arr[1].':'.$arr[2];

//var_dump($array[19]);
curl_close($curl);
//echo $response;
echo $url_pago;
