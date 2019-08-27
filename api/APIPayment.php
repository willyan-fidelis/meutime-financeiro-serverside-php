<?php
require_once('./SpCall.php');
require_once('../class/Config.php');
require_once('../class/DateTime.php');
require_once('../class/Asaas.php');
require_once('../class/Payment.php');//PaymentAPI

//URL instance(exemple):
//http://localhost/meutimev1/php/api/APIPayment.php/?method=PayAndSubscribe
//http://meutime.co/php/data/meutimev1/api/

//Get arrived data here:
$data = $_GET;

//Monta a URL de teste ------------------------------------------>>>
if(false)//Coloque em true para montar a URL de testes
{
	//Codigo utilizado para montar a URL para teste:
	$URLParameters = array(
		//Parametros para verificar se o usuario esta logado:
		'method' => 'PayAndSubscribe',
	);
	$finalURL = "http://localhost/meutimev1/php/api/APIPayment.php/" . "?" . http_build_query($URLParameters);
	echo urldecode($finalURL);return;
}
//Monta a URL de teste ------------------------------------------<<<

//Stored Procedures Calls:
$SpCall = new StoredProcedure();
$PHPQuery = new PHPMySqlQuery();

//Get date and time:
$DateTime = new DT();
$now = $DateTime->GetNow_MySqlDateTimeFormat();

//Create a Config instance to acess the system configuration data:
$Config = new ServerConfig();

//Stored Procedures Calls:
$AsaasAPI = new AsaasAPI();
//AddSubscriptions($customer,$value,$nextDueDate,$cc_name,$cc_number,$cc_month,$cc_year,$cc_ccv,$cch_name,$cch_email,$cch_cpf,$cch_postalcode,$cch_address_number,$cch_phone)
//$result = $AsaasAPI->AddSubscriptions("Assinatuta Pelada Segunda","317404",30.50,"2019-03-18","EDUARDO S PEREIRA","5555901273279999","04","27","999","EDUARDO S PEREIRA","edu@hotmail.com","05588999901","89201205","155","47999339999");
//var_dump($result);

//AddCustomers($name,$email,$phone,$cpfCnpj,$externalReference)
//$result = $AsaasAPI->AddCustomers("Will Teste","teste@gmail.com","47988339355","05284998901","123");
//var_dump($result);

//AddPaymentViaTicket($CUSTOMER_ID,$value,$description,$dueDate,$externalReference)
//$result = $AsaasAPI->AddPaymentViaTicket("cus_000000317506",30.55,"Fatura Teste","2019-06-10","123456");
//var_dump($result);

//AddPaymentViaCreditCard($CUSTOMER_ID,$value,$description,$dueDate,$externalReference,
//	$cc_name,$cc_number,$cc_month,$cc_year,$cc_ccv,$cch_name,$cch_email,$cch_cpf,$cch_postalcode,$cch_address_number,$cch_phone)
//$result = $AsaasAPI->AddPaymentViaCreditCard("cus_000000317506",40.78,"Fatura CC Teste","2019-08-15","123456",
//	"EDUARDO S PEREIRA","5555999977779999","04","27","999","EDUARDO S PEREIRA","edu@hotmail.com","05588999901","89201205","155","47999339999");
//var_dump($result);


//ListPayments($customerID,$offset,$limit)
//$result = $AsaasAPI->ListPayments("317404");
//var_dump($result);

//ListSubscriptions($customerID,$offset,$limit)
//$result = $AsaasAPI->ListSubscriptions("317404");
//var_dump($result);

//UpdateSubscriptions($id,$value,$nextDueDate)
//$result = $AsaasAPI->UpdateSubscriptions("sub_XHJedUq7OZ4s",50.75,"2020-08-15");
//var_dump($result);

//RemoveSubscriptions($id,$value,$nextDueDate)
//$result = $AsaasAPI->RemoveSubscriptions("sub_XHJedUq7OZ4s");
//var_dump($result);





//Payment instance:
$PaymentAPI = new PaymentAPI();

switch ($data["method"]) {
    case "PayTodayAndSubscribeNextMonthWithCC":
        //$result = $PaymentAPI->PayTodayAndSubscribeNextMonthWithCC("cus_000000317506",40.78,"Fatura CC Teste","25",
		//"EDUARDO S PEREIRA","5555999977779999","04","27","999","EDUARDO S PEREIRA","edu@hotmail.com","05588899901","89201205","155","479998889999");
		
		$customer_id = $data["CUSTOMER_ID"];
		
		if($data["CUSTOMER_ID"] == "xxx"){
			
			$result = $AsaasAPI->AddCustomers($data["user_name"],$data["user_email"],$data["user_phone"],$data["user_cpf"],$data["user_email"]);
			if($result["Result"] == AsaasAPIGenericResponseStatusEnum::NOK){echo json_encode(array("Result"=>-100,"APIResponse"=>$result["APIResponse"]));return;}
			
			$customer_id = $result["ID"];
			$result = $PHPQuery->update_userAsaasID($data["user_email"],$result["ID"]);
		}
		$value = $data["value"] + (($data["fee"]/100)*$data["value"]);
		$result = $PaymentAPI->PayTodayAndSubscribeNextMonthWithCC($customer_id,$value,$data["description"],$data["dayToPay"],
		$data["cc_name"],$data["cc_number"],$data["cc_month"],$data["cc_year"],$data["cc_ccv"],$data["cch_name"],$data["cch_email"],$data["cch_cpf"],$data["cch_postalcode"],$data["cch_address_number"],$data["cch_phone"]);
		if($result["Result"] == 1){echo json_encode(array("Result"=>1,"APIResponsePay"=>$result["APIResponsePay"],"APIResponseSub"=>$result["APIResponseSub"]));}
		else{echo json_encode(array("Result"=>-100,"APIResponsePay"=>$result["APIResponsePay"],"APIResponseSub"=>$result["APIResponseSub"]));}
		break;
    case "x":
		//$result = $SpCall->SpMySqlExecute_AlterRead("ALTER", "UPDATE `meutimec_mt_db`.`tuser` SET `AsaasID`='xxx' WHERE `OID`='315';",true, array("result"));
        $result = $PHPQuery->update_userAsaasID("edu@gmail.com","xxx");
		var_dump($result);
        break;
    case "y":
        $result = $PHPQuery->get_groupCashFlow_userToGroup('2016-03-09 19:45:00','2020-03-09 19:45:00',1);
		var_dump($result);
        break;
}




//Call the data base stored procedure(check if is logged-in):
//$sp_result = $SpCall->SpUserIsLoggedIn($data["email"], $data["session"]); //SpUserIsLoggedIn($_email,$session)

//if(true and isset($sp_result["Parameters"]["result"]["a"]["a"]["a"]["a"])){echo "Ok!";}else{echo "Nok!";}

/*
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
*/
//------------------------------------------------------
?>