<?php
//http://localhost/meutime/api/asaas_sub.php/?email=edu@hotmail.com&asaasID=cus_000006327675&cc_name=Eduardo Pereira


//Get arrived data here:
$data = $_GET;


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://www.asaas.com/api/v3/subscriptions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS, "{
  \"customer\": \"".$data["asaasID"]."\",
  \"billingType\": \"CREDIT_CARD\",
  \"nextDueDate\": \"2019-01-11\",
  \"value\": ".$data["debt"].",
  \"cycle\": \"MONTHLY\",
  \"description\": \"Assinatura Plano Quadra de Esportes\",
  \"creditCard\": {
    \"holderName\": \"".$data["cc_name"]."\",
    \"number\": \"".$data["cc_number"]."\",
    \"expiryMonth\": \"".$data["cc_month"]."\",
    \"expiryYear\": \"".$data["cc_year"]."\",
    \"ccv\": \"".$data["cc_ccv"]."\"
  },
  \"creditCardHolderInfo\": {
    \"name\": \"".$data["cch_name"]."\",
    \"email\": \"".$data["cch_email"]."\",
    \"cpfCnpj\": \"".$data["cch_cpf"]."\",
    \"postalCode\": \"89223-005\",
    \"addressNumber\": \"277\",
    \"addressComplement\": null,
    \"phone\": \"4738010919\",
    \"mobilePhone\": \"".$data["cch_phone"]."\"
  }
}");

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/json",
  "access_token: 7fdd68db9617f347b505004346445dc10ac789b9c3d6c5329ca1bef3657a4162"
));

$response = curl_exec($ch);
curl_close($ch);

//var_dump($response);



if(true){
	$toSend = json_decode($response);
	echo json_encode(array("Parameters"=>array("result"=>"1"),"Results"=>$toSend));
}
else{
	echo json_encode(array("Parameters"=>array("result"=>"-100")));
}//------------------------------------------------------
?>