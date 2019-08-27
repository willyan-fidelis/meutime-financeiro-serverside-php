<?php
require_once('./SpCall.php');
require_once('../class/Config.php');
require_once('../class/DateTime.php');

//URL instance(exemple):
//http://localhost/meutimev1/php/api/FnCreateGroup.php/?email=tiagoparadao@hotmail.com&session=xzz&name=Novo Grupo&desc=Jogo as Terças&value=50&ownerOID=1&DateToPay=2018-02-25 23:54:59
//http://meutime.co/php/data/meutimev1/api/

//Get arrived data here:
$data = $_GET;

//Monta a URL de teste ------------------------------------------>>>
if(false)//Coloque em true para montar a URL de testes
{
	//Codigo utilizado para montar a URL para teste:
	$URLParameters = array(
		//Parametros para verificar se o usuario esta logado:
		'email' => 'tiagoparadao@hotmail.com',
		'session' => 'xzz',
		
		//Parametros especificos da stored procedure:
		'name' => "Novo Grupo",
		'desc' => "Jogo as Terças",
		'value' => 50,
		'ownerOID' => 1,
		'DateToPay' => "2018-02-25 23:54:59",
	);
	$finalURL = "http://localhost/meutimev1/php/api/FnCreateGroup.php/" . "?" . http_build_query($URLParameters);
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
$sp_result = $SpCall->SpUserIsLoggedIn($data["email"], $data["session"]); //SpUserIsLoggedIn($_email,$session)

//if(true and isset($sp_result["Parameters"]["result"]["a"]["a"]["a"]["a"])){echo "Ok!";}else{echo "Nok!";}

if ($sp_result["Parameters"]["result"] == $SpCall->RESULT["OK"])
{
	$sp_result = $SpCall->StCreateGroup($data["name"],$data["desc"],$now,$data["DateToPay"],$data["value"],$Config->BusinessRule_GetCurrentRates()->Fee,$data["ownerOID"]); //StCreateGroup($name,$desc,$createdDate,$DateToPay,$value,$Fee,$ownerOID)
	echo json_encode($sp_result);
}
else
{
	echo json_encode(array("Parameters"=>array("result"=>$SpCall->RESULT["NOK_GENERAL_ERROR"])));
	//echo "Fail!";
}
//------------------------------------------------------
?>