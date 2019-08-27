<?php
require_once('./SpCall.php');
require_once('../class/Config.php');
require_once('../class/DateTime.php');

//Get arrived data here:
$data = $_GET;

//Stored Procedures Calls:
$SpCall = new StoredProcedure();
$ReplayQuery = new ReplayMySqlQuery();

//Get date and time:
$DateTime = new DT();
$now = $DateTime->GetNow_MySqlDateTimeFormat();

//Create a Config instance to acess the system configuration data:
$Config = new ServerConfig();



//Antes de mais nada autentifica o usuario:
//$sp_result = $SpCall->SpUserIsLoggedIn($data["email"], $data["session"]); 
//if ($sp_result["Parameters"]["result"] == $SpCall->RESULT["OK"]){}else{echo json_encode(array("Parameters"=>array("result"=>$SpCall->RESULT["NOK_GENERAL_ERROR"])));return;}



switch ($data["method"]) {
    case "test":
        //$result1 = $PHPQuery->getUserRootWalletOID($data["userOID"]);
		$result1 = $ReplayQuery->createManualCashFlow(1,1,10,1,1);
		echo json_encode ($result1);
        break;
	case "updateButtonConnection":
		$result = $ReplayQuery->updateButtonConnection($data["buttonOID"],$data["pwd"]);

		/*Resposta JSON:
			{"Time":"2019-04-10 22:53:53","Result":true}
		*/
		echo json_encode ($result);
		
		break;
	case "addButtonShot":
		$result = $ReplayQuery->addButtonShot($data["buttonOID"],$data["pwd"]);

		/*Resposta JSON:
			{"Time":"2019-04-10 22:53:53","Result":true}
		*/
		echo json_encode ($result);
		
		break;
	
}
?>