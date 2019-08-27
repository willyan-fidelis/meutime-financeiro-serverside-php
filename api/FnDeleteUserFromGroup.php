<?php
require_once('./SpCall.php');

//URL instance(exemple):
//http://localhost/meutimev1/php/api/FnDeleteUserFromGroup.php/?email=tf@gmail.com&session=xzz&groupOID=1&userToBeRemovedOID=2&userOID=1
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
		'groupOID' => 1,
		'userToBeRemovedOID' => 2,
		'userOID' => 1,
	);
	$finalURL = "http://localhost/meutimev1/php/api/FnDeleteUserFromGroup.php/" . "?" . http_build_query($URLParameters); 
	echo urldecode($finalURL);return;
}
//Monta a URL de teste ------------------------------------------<<<

//Stored Procedures Calls:
$SpCall = new StoredProcedure();

//Call the data base stored procedure(check if is logged-in):
$sp_result = $SpCall->SpUserIsLoggedIn($data["email"], $data["session"]); //SpUserIsLoggedIn($_email,$session)


if ($sp_result["Parameters"]["result"] == 1)
{
	//Deleta o grupo:
	$sp_result = $SpCall->StDeleteUserFromGroup($data["groupOID"],$data["userToBeRemovedOID"],$data["userOID"]); //StDeleteUserFromGroup($_groupOID,$_userToBeRemovedOID,$_userOID)
	
	//Se o grupo foi deletado, então retorna a lista de grupos:
	if ($sp_result["Parameters"]["result"] == $SpCall->RESULT["OK"])
	{
		$sp_result = $SpCall->SpReturnGroupUsers($data["groupOID"]); //SpReturnGroupUsers($groupOID)
		echo json_encode($sp_result);
	}
	else
	{
		echo json_encode(array("Parameters"=>array("result"=>$SpCall->RESULT["NOK_GENERAL_ERROR"])));
	}
}
else
{
	echo json_encode(array("Parameters"=>array("result"=>$SpCall->RESULT["NOK_GENERAL_ERROR"])));
}
//------------------------------------------------------
?>