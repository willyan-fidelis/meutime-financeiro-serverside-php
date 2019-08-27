<?php
require_once('../class/BTF_DtExg.php');
require_once('../class/Config.php');
require_once('../class/DateTime.php');

//URL instance(exemple):
//http://localhost/meutimev1/php/api/StCreateCashIn.php/?email=tf@gmail.com&session=xzz&for_count=2&groupOID_0=1&userOID_0=1&cleanValue_0=100&grossValue_0=109&fee_0=9.5&groupOID_1=1&userOID_1=2&cleanValue_1=200&grossValue_1=218&fee_1=9.5
//http://meutime.co/php/data/meutimev1/api/

//Monta a URL de teste ------------------------------------------>>>
if(false)//Coloque em true para montar a URL de testes
{
	//Codigo utilizado para montar a URL para teste:
	$URLParameters = array(
		'format' => 'MySQL',
	);
	$finalURL = "http://localhost/meutimev1/php/api/Util_GetDateTime.php/" . "?" . http_build_query($URLParameters);
	echo urldecode($finalURL);return;
}
//Monta a URL de teste ------------------------------------------<<<

//Get date and time:
$DateTime = new DT();
$now = $DateTime->GetNow_MySqlDateTimeFormat();
echo json_encode(array("Parameters"=>array("result"=>"-100","dateTime"=>$now)));
?>