<?php
require_once('./SpCall.php');

//URL instance(exemple):
//http://localhost/meutimev1/php/api/FnReturnGroupUsers.php/?email=tiagoparadao@hotmail.com&session=xzz&groupOID=80
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
		'groupOID' => 80,
	);
	$finalURL = "http://localhost/meutimev1/php/api/FnReturnGroupUsers.php/" . "?" . http_build_query($URLParameters);
	echo urldecode($finalURL);return;
}
//Monta a URL de teste ------------------------------------------<<<

//Stored Procedures Calls:
$SpCall = new StoredProcedure();

//Call the data base stored procedure(check if is logged-in):
$sp_result = $SpCall->SpUserIsLoggedIn($data["email"], $data["session"]); //SpUserIsLoggedIn($_email,$session)

//if(true and isset($sp_result["Parameters"]["result"]["a"]["a"]["a"]["a"])){echo "Ok!";}else{echo "Nok!";}

if ($sp_result["Parameters"]["result"] == $SpCall->RESULT["OK"])
{
	$sp_result = $SpCall->SpReturnGroupUsers($data["groupOID"]); //SpReturnGroupUsers($groupOID)
	echo json_encode($sp_result);
}
else
{
	echo json_encode(array("Parameters"=>array("result"=>$SpCall->RESULT["NOK_GENERAL_ERROR"])));
	//echo "Fail!";
}
//------------------------------------------------------
?>