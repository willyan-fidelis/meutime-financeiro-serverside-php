<?php
require_once('./SpCall.php');
require_once('../class/Config.php');
require_once('../class/DateTime.php');
require_once('../class/Asaas.php');
require_once('../class/Payment.php');//PaymentAPI

//URL instance(exemple):
//http://localhost/meutimev1/php/api/APIPayment.php/?method=PayAndSubscribe
//http://meutime.co/php/data/meutimev1/api/

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
$sp_result = $SpCall->SpUserIsLoggedIn($data["email"], $data["session"]); 
if ($sp_result["Parameters"]["result"] == $SpCall->RESULT["OK"]){}else{echo json_encode(array("Parameters"=>array("result"=>$SpCall->RESULT["NOK_GENERAL_ERROR"])));return;}


//Payment instance:
$PaymentAPI = new PaymentAPI();

switch ($data["method"]) {
    case "test":
        //$result1 = $PHPQuery->getUserRootWalletOID($data["userOID"]);
		$result1 = $PHPQuery->createManualCashFlow(1,1,10,1,1);
		echo json_encode ($result1);
        break;
	case "createManualCashFlow":
		$result = $PHPQuery->createManualCashFlow($data["userOID"], $data["groupOID"], $data["value"], $data["description"], $data["cashflowtype"]);

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
	case "deleteManualCashFlow":
		$result = $PHPQuery->deleteManualCashFlow($data["CashOID"]);

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
		case "updateManualCashFlow":
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
    case "GroupCashFlow":
        $result1 = $PHPQuery->get_groupCashFlow_userToGroup($data["startDate"],$data["endDate"],$data["groupOID"]);//('2016-03-09 19:45:00','2020-03-09 19:45:00',1);
		$result2 = $PHPQuery->get_groupCashFlow_groupToUser($data["startDate"],$data["endDate"],$data["groupOID"]);//('2016-03-09 19:45:00','2020-03-09 19:45:00',1);
		
		$res = ($result1["Result"] = true and $result2["Result"] = true) ? true : false;
		$abc = array_merge($result1["Results"]["Results"],$result2["Results"]["Results"]);
		
		//https://stackoverflow.com/questions/10000005/php-sort-array-by-field
		function cmp($a, $b)
		{
			return strcmp($a["CashOID"], $b["CashOID"]);//Order by 'CashOID'
		}
		usort($abc, "cmp");
		
		echo json_encode (array("All"=>$abc,"UserToGroup"=>$result1["Results"]["Results"],"GroupToUser"=>$result2["Results"]["Results"],"Result"=>$res));
        break;
}
?>