<?php
require_once('../class/BTF_DtExg.php');
require_once('../class/Config.php');
require_once('../class/Email.php');
require_once('../class/Text.php');

//URL instance(exemple):
//http://localhost/meutimev1/php/api/UtilSendEmail.php/?title=Solicitação Transferencia&content=Conteudo do email aqui.
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
		'title' => "Solicitação Transferencia",
		'content' => "Conteudo do email aqui.",
	);
	$finalURL = "http://localhost/meutimev1/php/api/UtilSendEmail.php/" . "?" . http_build_query($URLParameters);
	echo urldecode($finalURL);return;
}
//Monta a URL de teste ------------------------------------------<<<

//Get arrived data here:
$data = $_GET;

//Create a Config instance to acess the system configuration data:
$Config = new ServerConfig();

//Create a data base conection:
$DataExchange = new BackToFrontEndDtExg($Config->GetServerInfo()->Address, $Config->GetServerInfo()->UserName, $Config->GetServerInfo()->Pwd, $Config->GetServerInfo()->DBName); //$DBServerName, $DBUserName, $DBUserPwd, $DBName

$Email = new EmailManager();
$Text = new TextManipulation();
$Email->SendFreeEmail('edu@gmail.com','cadastro@meutime.co','cadastro@meutime.co', $data["title"],$data["content"]);


echo json_encode(array("Parameters"=>array("result"=>1,"title"=>$data["title"],"content"=>$data["content"])));

?>