<?php
//https://www.youtube.com/watch?v=ZeMfrsf4TG4
require_once('./SpCall.php');
require_once('../class/Config.php');
require_once('../class/DateTime.php');
require_once('../class/Asaas.php');
require_once('../class/Payment.php');//PaymentAPI

//URL instance(exemple):
//http://localhost/meutimev1/php/api/APIPayment.php/?method=PayAndSubscribe
//http://meutime.co/php/data/meutimev1/api/

//New Line Teste!
//

//Get arrived data here:
$data = $_GET;

//Monta a URL de teste ------------------------------------------>>>
if(false)//Coloque em true para montar a URL de testes
{
	//Codigo utilizado para montar a URL para teste:
	$URLParameters = array(
		//Parametros para verificar se o usuario esta logado:
		'method' => 'PayAndSubscribe',
	);
	$finalURL = "http://localhost/meutimev1/php/api/APIGeneralRequest.php/" . "?" . http_build_query($URLParameters);
	echo urldecode($finalURL);return;
}
//Monta a URL de teste ------------------------------------------<<<

//Stored Procedures Calls:
$SpCall = new StoredProcedure();
$PHPQuery = new PHPMySqlQuery();

//Get date and time:
$DateTime = new DT();
$now = $DateTime->GetNow_MySqlDateTimeFormat();

//Create a Config instance to acess the system configuration data:
$Config = new ServerConfig();

//Stored Procedures Calls:
$AsaasAPI = new AsaasAPI();


//Antes de mais nada autentifica o usuario:
//$sp_result = $SpCall->SpUserIsLoggedIn($data["email"], $data["session"]); 
//if ($sp_result["Parameters"]["result"] == $SpCall->RESULT["OK"]){}else{echo json_encode(array("Parameters"=>array("result"=>$SpCall->RESULT["NOK_GENERAL_ERROR"])));return;}


//Payment instance:
$PaymentAPI = new PaymentAPI();

switch ($data["method"]) {
		case "xxx":
		$result = $PHPQuery->updateManualCashFlow($data["value"],$data["desc"],$data["status"],$data["CashOID"]);

		/*Resposta JSON:
			{
				"Results": {
					"Parameters": {
						"result": 1
					},
					"Results": [
						{
							"SelectResult": 1
						}
					]
				},
				"Result": true
			}
			*/
		echo json_encode ($result);

        break;
    case "Time":
        //Get date and time:
		//$DateTime = new DT();
		//$now = $DateTime->GetNow_MySqlDateTimeFormat();
		echo json_encode(array("Result" => true, "Now"=> $now ));
        break;
}
?>