<?php
require_once('../class/BTF_DtExg.php');
require_once('../class/Config.php');

//URL instance(exemple):
//http://localhost/meutimev1/php/api/StUserForgotPwdRegisterNewPwd.php/?email=tf@gmail.com&newpwd=xxx&oldpwd=123456
//http://meutime.co/php/data/meutimev1/api/

//Monta a URL de teste ------------------------------------------>>>
if(false)//Coloque em true para montar a URL de testes
{
	//Codigo utilizado para montar a URL para teste:
	$URLParameters = array(
		//Parametros para verificar se o usuario esta logado:
		//'email' => 'tf@gmail.com',
		//'session' => 'xzz',
		
		//Parametros especificos da stored procedure:
		'email' => "tf@gmail.com",
		'newpwd' => "xxx",
		'oldpwd' => "123456",
	);
	$finalURL = "http://localhost/meutimev1/php/api/StUserForgotPwdRegisterNewPwd.php/" . "?" . http_build_query($URLParameters);
	echo urldecode($finalURL);return;
}
//Monta a URL de teste ------------------------------------------<<<

//Get arrived data here:
//$data = $_POST;
$data = $_GET;

//Create a Config instance to acess the system configuration data:
$Config = new ServerConfig();

//Create a data base conection:
$DataExchange = new BackToFrontEndDtExg($Config->GetServerInfo()->Address, $Config->GetServerInfo()->UserName, $Config->GetServerInfo()->Pwd, $Config->GetServerInfo()->DBName); //$DBServerName, $DBUserName, $DBUserPwd, $DBName

//Call the data base stored procedure(check if is logged-in):
//$sp_result = $DataExchange->ExecuteSPWithDataSet(false, "SpUserIsLoggedIn", array("Email"), array("s", "s", "i"), array("email", "sessionCode", "result"), array($data["email"], $data["session"], 0), array("result"));

//------------------------------------------------------
//Get the 'Parameters' that have the 'result' tag:
//$sp_result=$sp_result["Parameters"];

if (true)
{
	//Call the data base stored procedure:
	$sp_result = $DataExchange->ExecuteSPWithDataSet(true, "SpEditUserPwd", array("newpwd"), array("s", "s", "s", "i"), array("email", "newpwd", "oldpwd", "result"), array($data["email"], $data["newpwd"], $data["oldpwd"], 0), array("result"));
}
else
{
	echo json_encode(array("Parameters"=>array("result"=>"-100")));
	//echo "Fail!";
}
//------------------------------------------------------
?>