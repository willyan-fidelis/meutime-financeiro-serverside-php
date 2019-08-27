<?php
require_once('../class/BTF_DtExg.php');
require_once('../class/Config.php');
require_once('../class/Email.php');
require_once('../class/Text.php');

//URL instance(exemple):
//http://localhost/meutimev1/php/api/StUserForgotPwd.php/?email=tf@gmail.com
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
	$finalURL = "http://localhost/meutimev1/php/api/StUserForgotPwd.php/" . "?" . http_build_query($URLParameters);
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
	$sp_result = $DataExchange->ExecuteSPWithDataSet(true, "StUserForgotPwd", array("selectresults"), array("s", "i"), array("email", "result"), array($data["email"], 0, ""), array("result"));
	//echo $sp_result["Results"][0]["selectresults"];
	if ($sp_result["Parameters"]["result"] == 1)
	{
		$Email = new EmailManager();
		$Text = new TextManipulation();
		//$url = 'http://meutime.co/app/?param1='.$data["email"].'&param2='.$sp_result["Results"][0]["selectresults"].'&weblink=redefinepwd&ctrl='.$Text->generateRandomString();
		
		
		$URLParameters = array(
			'xparam1' => $data["email"],
			'xparam2' => $sp_result["Results"][0]["selectresults"],
			'weblink' => "redefinepwd",
			'ctrl' => $Text->generateRandomString(),
		);
		$url = "http://meutime.co/app/" . "?" . http_build_query($URLParameters);
		echo urldecode($url);
		
		//echo $url;
		$Email->SendGeneralPurposeEmail($data["email"],'cadastro@meutime.co','cadastro@meutime.co', 'Redifinição de senha','<p>Você solicitou recadastro de senha. Clique no link abaixo para redefinir sua senha:</p><a href="'.$url.'">Confirme seu cadastro clicando aqui!</a>');
	}
	else{echo json_encode(array("Parameters"=>array("result"=>"-200")));}
}
else
{
	echo json_encode(array("Parameters"=>array("result"=>"-100")));
	//echo "Fail!";
}
//------------------------------------------------------
?>