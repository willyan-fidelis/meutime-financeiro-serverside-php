<?php
require_once('./SpCall.php');
require_once('../class/Config.php');
require_once('../class/DateTime.php');
require_once('../class/Email.php');
require_once('../class/Text.php');

//URL instance(exemple):
//http://localhost/meutimev1/php/api/FnCreateUser.php/?email=edu@gmail.com&pwd=555&name=Fulano Silva&phone=47988557799&cpf=123&groupOID=1
//http://meutime.co/php/data/meutimev1/api/

//Get arrived data here:
$data = $_GET;

//Monta a URL de teste ------------------------------------------>>>
if(false)//Coloque em true para montar a URL de testes
{
	//Codigo utilizado para montar a URL para teste:
	$URLParameters = array(
		//Parametros para verificar se o usuario esta logado:
		//'email' => 'tf@gmail.com',
		//'session' => 'xzz',
		
		//Parametros especificos da stored procedure:
		'email' => "edu@gmail.com",
		'pwd' => "555",
		'name' => "Fulano Silva",
		'phone' => "47988557799",
		'cpf' => "123",
		'groupOID' => 1, //Null(Apenas cadastro) ou Not Null (Cadastro e isncrição no grupo)
	);
	$finalURL = "http://localhost/meutimev1/php/api/FnCreateUser.php/" . "?" . http_build_query($URLParameters);
	echo urldecode($finalURL);return;
}
//Monta a URL de teste ------------------------------------------<<<

//Stored Procedures Calls:
$SpCall = new StoredProcedure();

//Get date and time:
$DateTime = new DT();
$now = $DateTime->GetNow_MySqlDateTimeFormat();

//Create a Config instance to acess the system configuration data:
$Config = new ServerConfig();

//Call the data base stored procedure(check if is logged-in):
//$sp_result = $SpCall->SpUserIsLoggedIn($data["email"], $data["session"]); //SpUserIsLoggedIn($_email,$session)

//if(true and isset($sp_result["Parameters"]["result"]["a"]["a"]["a"]["a"])){echo "Ok!";}else{echo "Nok!";}

if (true)//($sp_result["Parameters"]["result"] == $SpCall->RESULT["OK"])
{
	$sp_result = $SpCall->StCreateUser($data["email"], $data["pwd"], $data["name"], $data["phone"], $data["cpf"]); //StCreateUser($email, $pwd, $name, $phone, $cpf)
	if ($sp_result["Parameters"]["result"] == 1) //Usuário sem ativar a conta. Vamos-lhe renviar o email de ativação:
	{
		echo json_encode($sp_result);
		
		$Email = new EmailManager();
		$Text = new TextManipulation();
		$url = 'http://meutime.co/app/?xparam1='.$data["email"].'&weblink=confirmaccount&ctrl='.$Text->generateRandomString();
		//echo $url;
		$Email->SendNewUserEmail($data["email"],'cadastro@meutime.co','cadastro@meutime.co', $url);
		
		if(!empty($data["groupOID"])){//Se tiver OID, então queremos adicionar o usuario recem criado em um grupo
			$DateTime = new DT();
			$now = $DateTime->GetNow_MySqlDateTimeFormat();
			$sp_result = $SpCall->StAddUserToGroup($now, $data["groupOID"], $sp_result["Results"][0]["selectresults"], 1);//StAddUserToGroup($date $groupOID, $userOID, $privilegesOID)
		}
	}
	else
	{
		echo json_encode($sp_result);
		echo json_encode(array("Parameters"=>array("result"=>$SpCall->RESULT["NOK_GENERAL_ERROR"])));
	}
	
}
else
{
	echo json_encode(array("Parameters"=>array("result"=>$SpCall->RESULT["NOK_GENERAL_ERROR"])));
}
//------------------------------------------------------
?>