<?php
require_once('../class/BTF_DtExg.php');
require_once('../class/Config.php');
require_once('../class/DateTime.php');

//URL instance(exemple):
//http://localhost/meutimev1/php/api/StCreateCashIn.php/?email=tf@gmail.com&session=xzz&for_count=2&groupOID_0=1&userOID_0=1&cleanValue_0=100&grossValue_0=109&fee_0=9.5&groupOID_1=1&userOID_1=2&cleanValue_1=200&grossValue_1=218&fee_1=9.5
//http://meutime.co/php/data/meutimev1/api/

//Monta a URL de teste ------------------------------------------>>>
if(false)//Coloque em true para montar a URL de testes
{
	//Codigo utilizado para montar a URL para teste:
	$URLParameters = array(
		//Parametros para verificar se o usuario esta logado:
		'email' => 'tf@gmail.com',
		'session' => 'xzz',
		
		//Parametros especificos da stored procedure:
		'for_count' => 2,
		
		'groupOID_0' => 1,
		'userOID_0' => 1,
		'cleanValue_0' => 100,
		'grossValue_0' => 109,
		'fee_0' => 9.50,
		
		'groupOID_1' => 1,
		'userOID_1' => 2,
		'cleanValue_1' => 200,
		'grossValue_1' => 218,
		'fee_1' => 9.50,
	);
	$finalURL = "http://localhost/meutimev1/php/api/StCreateCashIn.php/" . "?" . http_build_query($URLParameters);
	echo urldecode($finalURL);return;
}
//Monta a URL de teste ------------------------------------------<<<

//Get arrived data here:
$data = $_GET;

//Create a Config instance to acess the system configuration data:
$Config = new ServerConfig();

//Create a data base conection:
$DataExchange = new BackToFrontEndDtExg($Config->GetServerInfo()->Address, $Config->GetServerInfo()->UserName, $Config->GetServerInfo()->Pwd, $Config->GetServerInfo()->DBName); //$DBServerName, $DBUserName, $DBUserPwd, $DBName

//Call the data base stored procedure(check if is logged-in):
$sp_result = $DataExchange->ExecuteSPWithDataSet(false, "SpUserIsLoggedIn", array("Email"), array("s", "s", "i"), array("email", "sessionCode", "result"), array($data["email"], $data["session"], 0), array("result"));

//------------------------------------------------------
//Get the 'Parameters' that have the 'result' tag:
$sp_result=$sp_result["Parameters"];

//Get date and time:
$DateTime = new DT();
$now = $DateTime->GetNow_MySqlDateTimeFormat();

//echo $now;
if ($sp_result["result"] == 1)
{	$res = true;
	//Call the data base stored procedure:
	//$sp_result = $DataExchange->ExecuteSPWithDataSet(true, "StCreateCashIn", array("Res"), array("s","i","i","d","d","d","i"), array("firstDateToPay","groupOID","userOID","cleanValue","grossValue","fee","result"), array($now,$data["groupOID"],$data["userOID"],$data["cleanValue"],$data["grossValue"],$data["fee"],0), array("result"));
	for ($i = 0; $i < $data["for_count"]; $i++) {
		$sp_result = $sp_result = $DataExchange->ExecuteSPWithDataSet(false, "StCreateCashIn", array("Res"), array("s","i","i","d","d","d","i"), array("firstDateToPay","groupOID","userOID","cleanValue","grossValue","fee","result"), array($now,$data["groupOID"."_".$i],$data["userOID"."_".$i],$data["cleanValue"."_".$i],$data["grossValue"."_".$i],$data["fee"."_".$i],0), array("result"));
		if($sp_result <> true){$res = false;}
	}
	if($res){echo json_encode(array("Parameters"=>array("result"=>"1")));}else{echo json_encode(array("Parameters"=>array("result"=>"-200")));}
}
else
{
	echo json_encode(array("Parameters"=>array("result"=>"-100")));
	//echo "Fail!";
}
//------------------------------------------------------
?>