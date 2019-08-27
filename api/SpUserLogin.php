<?php
require_once('../class/BTF_DtExg.php');
require_once('../class/Config.php');
require_once('../class/Email.php');
require_once('../class/Text.php');
require_once('../class/DateTime.php');

//URL instance(exemple):
//http://localhost/meutimev1/php/api/SpUserLogin.php/?email=tf@gmail.com&pwd=123456
//http://meutime.co/php/data/meutimev1/api/SpUserLogin.php/?email=tf@gmail.com&pwd=123456

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
		'pwd' => "123456",
		'groupOID' => 1, //Null(Apenas cadastro) ou Not Null (Cadastro e isncrição no grupo)
	);
	$finalURL = "http://localhost/meutimev1/php/api/SpUserLogin.php/" . "?" . http_build_query($URLParameters);
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
//$sp_result = $DataExchange->ExecuteSPWithDataSet(false, "SpUserIsLoggedIn", array("Email"), array("s", "s", "i"), array("email", "sessionCode", "result"), array($data["email"], $data["session"], 0), array("result"));

//------------------------------------------------------
//Get the 'Parameters' that have the 'result' tag:
//$sp_result=$sp_result["Parameters"];

//if ($sp_result["result"] == 1)
if(true)
{
	//Call the data base stored procedure:
	$sp_result = $DataExchange->ExecuteSPWithDataSet(true, "SpUserLogin", array("SessionCode", "OID"), array("s", "s", "i"), array("email", "pwd", "result"), array($data["email"], $data["pwd"], 0), array("result"));
	
	//Get the 'Parameters' that have the 'result' tag:
	//$sp_result=$sp_result["Parameters"];
	if ($sp_result["Parameters"]["result"] == -200) //Usuário sem ativar a conta. Vamos-lhe renviar o email de ativação:
	{
		$Email = new EmailManager();
		$Text = new TextManipulation();
		$url = 'http://meutime.co/app/?param1='.$data["email"].'&weblink=confirmaccount&ctrl='.$Text->generateRandomString();
		//echo $url;
		$Email->SendNewUserEmail($data["email"],'cadastro@meutime.co','cadastro@meutime.co', $url);
	}
	if ($sp_result["Parameters"]["result"] == -300) //Usuário com conta desativada. Vamos-lhe renviar o email de reativação:
	{
		$Email = new EmailManager();
		$Text = new TextManipulation();
		$url = 'http://meutime.co/app/?param1='.$data["email"].'&weblink=confirmaccount&ctrl='.$Text->generateRandomString();
		//echo $url;
		$Email->SendUserAccountReactivationEmail($data["email"],'cadastro@meutime.co','cadastro@meutime.co', $url);
	}
	
	if($sp_result["Parameters"]["result"] == 1 and !empty($data["groupOID"])){
		$DateTime = new DT();
		$now = $DateTime->GetNow_MySqlDateTimeFormat();
		$sp_result = $DataExchange->ExecuteSPWithDataSet(false, "StAddUserToGroup", array("selectresults"), array("s","i","i","i","i"), array("date", "groupOID", "UserOID", "priv", "result"), array($now, $data["groupOID"], $sp_result["Results"][0]["OID"], 1, 0), array("result"));
	}
}
else
{
	echo json_encode(array("Parameters"=>array("result"=>"-100")));
	//echo "Fail!";
}
//------------------------------------------------------
?>