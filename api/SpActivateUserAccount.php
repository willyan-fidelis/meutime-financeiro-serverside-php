<?php
require_once('../class/BTF_DtExg.php');
require_once('../class/Config.php');
require_once('../class/Email.php');
require_once('../class/Text.php');

//URL instance(exemple):
//http://localhost/meutimev1/php/api/SpActivateUserAccount.php/?email=tf@gmail.com
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
	);
	$finalURL = "http://localhost/meutimev1/php/api/SpActivateUserAccount.php/" . "?" . http_build_query($URLParameters);
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
	$sp_result = $DataExchange->ExecuteSPWithDataSet(true, "SpActivateUserAccount", array("SelectResults"), array("s", "i"), array("email", "result"), array($data["email"], 0), array("result"));
	
	//Get the 'Parameters' that have the 'result' tag:
	$sp_result=$sp_result["Parameters"];
	if ($sp_result["result"] == 1) //Cadastro ativado
	{
		$Email = new EmailManager();
		$Text = new TextManipulation();
		$url = 'http://meutime.co/app/?xparam1='.$data["email"].'&weblink=confirmaccount&ctrl='.$Text->generateRandomString();
		//echo $url;
		$Email->SendGeneralPurposeEmail($data["email"],'cadastro@meutime.co','cadastro@meutime.co', 'Cadastro concluido com sucesso','Entre no app e convide os amigos!');
	}
	if ($sp_result["result"] == -200) //Usuário não cadastrado
	{
		$Email = new EmailManager();
		$Text = new TextManipulation();
		$url = 'http://meutime.co/app/?xparam1='.$data["email"].'&weblink=confirmaccount&ctrl='.$Text->generateRandomString();
		//echo $url;
		$Email->SendGeneralPurposeEmail($data["email"],'cadastro@meutime.co','cadastro@meutime.co', 'Usuário não encontrado','Ocorreu um erro ao executar a operação. Entre em contato com <b>contato@meutime.co</b> para maiores detalhes');
	}
	if ($sp_result["result"] == -100) //Erro!
	{
		$Email = new EmailManager();
		$Text = new TextManipulation();
		$url = 'http://meutime.co/app/?xparam1='.$data["email"].'&weblink=confirmaccount&ctrl='.$Text->generateRandomString();
		//echo $url;
		//$Email->SendGeneralPurposeEmail($data["email"],'cadastro@meutime.co','cadastro@meutime.co', 'Erro na confirmação de cadastro','Ocorreu um erro ao executar a operação. Entre em contato com <b>contato@meutime.co</b> para maiores detalhes');
		
	}
}
else
{
	echo json_encode(array("Parameters"=>array("result"=>"-100")));
	//echo "Fail!";
}
//------------------------------------------------------
?>