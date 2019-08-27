<?php
require_once('../class/BTF_DtExg.php');
require_once('../class/Config.php');
require_once('../class/DateTime.php');

//URL instance(exemple):
//http://localhost/meutimev1/php/api/SpReturnGroupBalance.php/?email=tiagoparadao@hotmail.com&session=xzz&groupOID=1&year=2018
//http://meutime.co/php/data/meutimev1/api/

//Monta a URL de teste ------------------------------------------>>>
if(false)//Coloque em true para montar a URL de testes
{
	//Codigo utilizado para montar a URL para teste:
	$URLParameters = array(
		//Parametros para verificar se o usuario esta logado:
		'email' => 'tiagoparadao@hotmail.com',
		'session' => 'xzz',
		
		//Parametros especificos da stored procedure:
		'groupOID' => 1,
		'year' => '2018',
	);
	$finalURL = "http://localhost/meutimev1/php/api/SpReturnGroupBalance.php/" . "?" . http_build_query($URLParameters);
	echo urldecode($finalURL);return;
}
//Monta a URL de teste ------------------------------------------<<<

//Get arrived data here:
//$data = $_POST;
$data = $_GET;

//Create a Config instance to acess the system configuration data:
$Config = new ServerConfig();

//Data e hora:
$DateTime = new DT();
$now = $DateTime->GetNow_MySqlDateTimeFormat();

//Create a data base conection:
$DataExchange = new BackToFrontEndDtExg($Config->GetServerInfo()->Address, $Config->GetServerInfo()->UserName, $Config->GetServerInfo()->Pwd, $Config->GetServerInfo()->DBName); //$DBServerName, $DBUserName, $DBUserPwd, $DBName

//Call the data base stored procedure(check if is logged-in):
$sp_result = $DataExchange->ExecuteSPWithDataSet(false, "SpUserIsLoggedIn", array("Email"), array("s", "s", "i"), array("email", "sessionCode", "result"), array($data["email"], $data["session"], 0), array("result"));

//------------------------------------------------------
//Get the 'Parameters' that have the 'result' tag:
$sp_result=$sp_result["Parameters"];

if ($sp_result["result"] == 1)
{
	//Call the data base stored procedure:
	$sp_result = $DataExchange->ExecuteSPWithDataSet(false, "SpReturnUserInGroupCashIn", array("Name", "Email", "Date", "TFinancialTransactionStatus", "CleanValue", "GrossValue", "Fee", "Description", "CashOID", "UserOID"), array("s","s","i","i"), array("start","end","groupOID", "result"), array($data["year"]."-01-01 00:00:00",$data["year"]."-12-31 23:59:59",$data["groupOID"], 0), array("result"));
		
	$cashIN_paid = 0;
	$cashIN_open = 0;
	$cashIN_notpaid = 0;
	foreach ($sp_result["Results"] as &$value) {
		//echo 'CleanValue is: ' . $value["CleanValue"];
		if ($value["TFinancialTransactionStatus"] >= 1000){ //Cobranças pagas
			$cashIN_paid = $cashIN_paid + $value["CleanValue"];
		}
		elseif ($value["TFinancialTransactionStatus"] < 0){ //Cobranças em aberto
			$cashIN_open = $cashIN_open + $value["CleanValue"];
		}
		else{ //Cobranças não pagas
			$cashIN_notpaid = $cashIN_notpaid + $value["CleanValue"];
		}
	}
	//echo $cashIN_paid;echo "\r\n";echo $cashIN_open;echo "\r\n";echo $cashIN_notpaid;
	
	$sp_result = $DataExchange->ExecuteSPWithDataSet(false, "SpReturnUserInGroupCashOut", array("Name", "Email", "Date", "TFinancialTransactionStatus", "CleanValue", "GrossValue", "Fee", "Description", "CashOID", "UserOID"), array("s","s","i", "i"), array("start","end","groupOID", "result"), array($data["year"]."-01-01 00:00:00",$data["year"]."-12-31 23:59:59",$data["groupOID"], 0), array("result"));
	
	$cashOUT_paid = 0;
	$cashOUT_open = 0;
	$cashOUT_notpaid = 0;
	foreach ($sp_result["Results"] as &$value) {
		//echo 'CleanValue is: ' . $value["CleanValue"];
		if ($value["TFinancialTransactionStatus"] >= 1000){ //Cobranças pagas
			$cashOUT_paid = $cashOUT_paid + $value["CleanValue"];
		}
		elseif ($value["TFinancialTransactionStatus"] < 0){ //Cobranças em aberto
			$cashOUT_open = $cashOUT_open + $value["CleanValue"];
		}
		else{ //Cobranças não pagas
			$cashOUT_notpaid = $cashOUT_notpaid + $value["CleanValue"];
		}
	}
	//echo $cashOUT_paid;echo "\r\n";echo $cashOUT_open;echo "\r\n";echo $cashOUT_notpaid;
	
	$cashTotal = $cashIN_paid - $cashOUT_paid;
	
	echo json_encode(array("Parameters"=>array("result"=>"1","cashTotal"=>$cashTotal,"cashIN_paid"=>$cashIN_paid,"cashOUT_paid"=>$cashOUT_paid,"cashIN_notpaid"=>$cashIN_notpaid,"cashOUT_notpaid"=>$cashOUT_notpaid,"cashIN_open"=>$cashIN_open,"cashOUT_open"=>$cashOUT_open)));
}
else
{
	echo json_encode(array("Parameters"=>array("result"=>"-100")));
	//echo "Fail!";
}
//------------------------------------------------------
?>