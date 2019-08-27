<?php

//require_once('PHPConsoleLog.php');

class AsaasAPI {
	
	//Public & Private properties:
	private $ProductionURL  = array("URL"=>"https://www.asaas.com/api/v3/", "Token"=>"7fdd68db9617f347b505004346445dc10ac789b9c3d6c5329ca1bef3657a4162");
	private $SandboxURL 	= array("URL"=>"https://sandbox.asaas.com/api/v3/", "Token"=>"8bde1c6502c074849ab8d51143aea227e07552a5ee0c88c6ba7319a51b4051e1");
	
	function __construct()
	{
		
	}
	function GetServerInfo()
	{
		return (object)$this->ProductionURL; //ProductionURL//SandboxURL
	}
	function AddSubscriptionViaCreditCard($CUSTOMER_ID,$value,$description,$nextDueDate,$cc_name,$cc_number,$cc_month,$cc_year,$cc_ccv,$cch_name,$cch_email,$cch_cpf,$cch_postalcode,$cch_address_number,$cch_phone)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->GetServerInfo()->URL."subscriptions");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		curl_setopt($ch, CURLOPT_POSTFIELDS, "{
		  \"customer\": \"".$CUSTOMER_ID."\",
		  \"billingType\": \"CREDIT_CARD\",
		  \"nextDueDate\": \"".$nextDueDate."\",//2019-05-11
		  \"value\": ".$value.",
		  \"cycle\": \"MONTHLY\",
		  \"description\": \"".$description."\",//Assinatura Plano Quadra de Esportes
		  \"creditCard\": {
			\"holderName\": \"".$cc_name."\",
			\"number\": \"".$cc_number."\",
			\"expiryMonth\": \"".$cc_month."\",
			\"expiryYear\": \"".$cc_year."\",
			\"ccv\": \"".$cc_ccv."\"
		  },
		  \"creditCardHolderInfo\": {
			\"name\": \"".$cch_name."\",
			\"email\": \"".$cch_email."\",
			\"cpfCnpj\": \"".$cch_cpf."\",
			\"postalCode\": \".$cch_postalcode.\", //89223-005
			\"addressNumber\": \".$cch_address_number.\", //277
			\"addressComplement\": null,
			\"phone\": \"".$cch_phone."\"
		  }
		}");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Content-Type: application/json",
		  "access_token: ".$this->GetServerInfo()->Token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		
		//var_dump($response);
		
		
		$response = json_decode($response);
		if(isset($response->status) and $response->status == "ACTIVE" and isset($response->id)){
			
			return array("Result"=>AsaasAPIGenericResponseStatusEnum::OK,"APIResponse"=>$response,"ID"=>$response->id);
		}
		else{
			return array("Result"=>AsaasAPIGenericResponseStatusEnum::NOK,"APIResponse"=>$response,"ID"=>null);
		}
	}
	function UpdateSubscriptions($id,$value,$nextDueDate)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->GetServerInfo()->URL."subscriptions/".$id);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		curl_setopt($ch, CURLOPT_POSTFIELDS, "{
		  \"nextDueDate\": \"".$nextDueDate."\",
		  \"value\": ".$value."
		}");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Content-Type: application/json",
		  "access_token: ".$this->GetServerInfo()->Token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		
		
		$response = json_decode($response);
		if(isset($response->status) and $response->status == "ACTIVE" and isset($response->id)){
			
			return array("Result"=>AsaasAPIGenericResponseStatusEnum::OK,"APIResponse"=>$response,"ID"=>$response->id);
		}
		else{
			return array("Result"=>AsaasAPIGenericResponseStatusEnum::NOK,"APIResponse"=>$response,"ID"=>null);
		}
	}
	function RemoveSubscriptions($id)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->GetServerInfo()->URL."subscriptions/".$id);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Content-Type: application/json",
		  "access_token: ".$this->GetServerInfo()->Token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		
		
		$response = json_decode($response);
		if(isset($response->deleted) and $response->deleted == true and isset($response->id)){
			
			return array("Result"=>AsaasAPIGenericResponseStatusEnum::OK,"APIResponse"=>$response,"ID"=>$response->id);
		}
		else{
			return array("Result"=>AsaasAPIGenericResponseStatusEnum::NOK,"APIResponse"=>$response,"ID"=>null);
		}
	}
	function AddCustomers($name,$email,$phone,$cpfCnpj,$externalReference)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->GetServerInfo()->URL."customers");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		curl_setopt($ch, CURLOPT_POSTFIELDS, "{
		  \"name\": \"".$name."\",
		  \"email\": \"".$email."\",
		  \"phone\": \"".$phone."\",
		  \"cpfCnpj\": \"".$cpfCnpj."\",
		  \"externalReference\": \"".$externalReference."\",
		  \"notificationDisabled\": false
		}");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Content-Type: application/json",
		  "access_token: ".$this->GetServerInfo()->Token
		));

		$response = curl_exec($ch);
		curl_close($ch);

		//var_dump($response);
		
		
		$response = json_decode($response);
		if(isset($response->object) and $response->object == "customer" and isset($response->id)){
			
			return array("Result"=>AsaasAPIGenericResponseStatusEnum::OK,"APIResponse"=>$response,"ID"=>$response->id);
		}
		else{
			return array("Result"=>AsaasAPIGenericResponseStatusEnum::NOK,"APIResponse"=>$response,"ID"=>null);
		}
	}
	function AddPaymentViaTicket($CUSTOMER_ID,$value,$description,$dueDate,$externalReference)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->GetServerInfo()->URL."payments");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		curl_setopt($ch, CURLOPT_POSTFIELDS, "{
		  \"customer\": \"".$CUSTOMER_ID."\",
		  \"billingType\": \"BOLETO\",
		  \"dueDate\": \"".$dueDate."\", //2017-06-10
		  \"value\": ".$value.",
		  \"description\": \"".$description."\",
		  \"externalReference\": \"".$externalReference."\"
		}");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Content-Type: application/json",
		  "access_token: ".$this->GetServerInfo()->Token
		));

		$response = curl_exec($ch);
		curl_close($ch);

		var_dump($response);
		
		
		$response = json_decode($response);
		if(isset($response->object) and $response->object == "payment" and isset($response->id)){
			
			return array("Result"=>AsaasAPIGenericResponseStatusEnum::OK,"APIResponse"=>$response,"ID"=>$response->id,"BankSlipUrl"=>$response->bankSlipUrl,"InvoiceUrl"=>$response->invoiceUrl);
		}
		else{
			return array("Result"=>AsaasAPIGenericResponseStatusEnum::NOK,"APIResponse"=>$response,"ID"=>null,"BankSlipUrl"=>null,"InvoiceUrl"=>null);
		}
	}
	function AddPaymentViaCreditCard($CUSTOMER_ID,$value,$description,$dueDate,$externalReference,
	$cc_name,$cc_number,$cc_month,$cc_year,$cc_ccv,$cch_name,$cch_email,$cch_cpf,$cch_postalcode,$cch_address_number,$cch_phone)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->GetServerInfo()->URL."payments");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		curl_setopt($ch, CURLOPT_POSTFIELDS, "{
		  \"customer\": \"".$CUSTOMER_ID."\",
		  \"billingType\": \"CREDIT_CARD\",
		  \"dueDate\": \"".$dueDate."\",//2017-03-15
		  \"value\": ".$value.",
		  \"description\": \"".$description."\",
		  \"externalReference\": \"".$externalReference."\",
		  \"creditCard\": {
			\"holderName\": \"".$cc_name."\",
			\"number\": \"".$cc_number."\",
			\"expiryMonth\": \"".$cc_month."\",
			\"expiryYear\": \"".$cc_year."\",
			\"ccv\": \"".$cc_ccv."\"
		  },
		  \"creditCardHolderInfo\": {
			\"name\": \"".$cch_name."\",
			\"email\": \"".$cch_email."\",
			\"cpfCnpj\": \"".$cch_cpf."\",
			\"postalCode\": \".$cch_postalcode.\", //89223-005
			\"addressNumber\": \".$cch_address_number.\", //277
			\"addressComplement\": null,
			\"phone\": \"".$cch_phone."\"
		  }
		}");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Content-Type: application/json",
		  "access_token: ".$this->GetServerInfo()->Token
		));

		$response = curl_exec($ch);
		curl_close($ch);

		//var_dump($response);
		
		
		$response = json_decode($response);
		if(isset($response->object) and $response->object == "payment" and isset($response->id)){
			
			return array("Result"=>AsaasAPIGenericResponseStatusEnum::OK,"APIResponse"=>$response,"ID"=>$response->id,"BankSlipUrl"=>null,"InvoiceUrl"=>$response->invoiceUrl);
		}
		else{
			return array("Result"=>AsaasAPIGenericResponseStatusEnum::NOK,"APIResponse"=>$response,"ID"=>null,"BankSlipUrl"=>null,"InvoiceUrl"=>null);
		}
	}
	private function ListPaymentsByPage($customerID,$offset,$limit)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->GetServerInfo()->URL."payments?customer=".$customerID."&offset=".$offset."&limit=".$limit);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "access_token: ".$this->GetServerInfo()->Token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		
		
		$response = json_decode($response);
		if(isset($response->object) and $response->object == "list" and isset($response->hasMore)){
			
			return array("Result"=>AsaasAPIGenericResponseStatusEnum::OK,"APIResponse"=>$response,"HasMore"=>$response->hasMore,"Data"=>$response->data);
		}
		else{
			return array("Result"=>AsaasAPIGenericResponseStatusEnum::NOK,"APIResponse"=>$response,"HasMore"=>false,"Data"=>null);
		}
	}
	function ListPayments($customerID){
		$step = 100;//Tamanho de cada paginação 
		$offset = 0;//Offset do elemento
		$dataArray = array();//Array com os dados
		$res = array();//Resposta
		
		while (true) {
			$res = $this->ListPaymentsByPage($customerID,$offset,$step);			
			//var_dump("ABC: ");echo $offset;
			$offset = $offset + $step;
			$dataArray = array_merge($dataArray,$res["Data"]);
			if(isset($res["HasMore"]) and $res["HasMore"] == false or $res["HasMore"] == null){break;}
			
		}
		
		//Se a ultima solicitação foi bem, então ever things gona be all ok:
		if($res["Result"] == AsaasAPIGenericResponseStatusEnum::OK){return array("Result"=>AsaasAPIGenericResponseStatusEnum::OK,"APIResponse"=>$res["APIResponse"],"Data"=>$dataArray);}
		else{return array("Result"=>-AsaasAPIGenericResponseStatusEnum::NOK,"APIResponse"=>$res["APIResponse"],"Data"=>null);}
	}
	private function ListSubscriptionsByPage($customerID,$offset,$limit)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->GetServerInfo()->URL."subscriptions?customer=".$customerID."&offset=".$offset."&limit=".$limit);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "access_token: ".$this->GetServerInfo()->Token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		
		
		$response = json_decode($response);
		if(isset($response->object) and $response->object == "list" and isset($response->hasMore)){
			
			return array("Result"=>AsaasAPIGenericResponseStatusEnum::OK,"APIResponse"=>$response,"HasMore"=>$response->hasMore,"Data"=>$response->data);
		}
		else{
			return array("Result"=>AsaasAPIGenericResponseStatusEnum::NOK,"APIResponse"=>$response,"HasMore"=>false,"Data"=>null);
		}
	}
	function ListSubscriptions($customerID){
		$step = 100;//Tamanho de cada paginação 
		$offset = 0;//Offset do elemento
		$dataArray = array();//Array com os dados
		$res = array();//Resposta
		
		while (true) {
			$res = $this->ListSubscriptionsByPage($customerID,$offset,$step);			
			//var_dump("ABC: ");echo $offset;
			$offset = $offset + $step;
			$dataArray = array_merge($dataArray,$res["Data"]);
			if(isset($res["HasMore"]) and $res["HasMore"] == false or $res["HasMore"] == null){break;}
			
		}
		
		//Se a ultima solicitação foi bem, então ever things gona be all ok:
		if($res["Result"] == AsaasAPIGenericResponseStatusEnum::OK){return array("Result"=>AsaasAPIGenericResponseStatusEnum::OK,"APIResponse"=>$res["APIResponse"],"Data"=>$dataArray);}
		else{return array("Result"=>AsaasAPIGenericResponseStatusEnum::NOK,"APIResponse"=>$res["APIResponse"],"Data"=>null);}
	}
	
}

abstract class AsaasAPIGenericResponseStatusEnum
{
	//https://stackoverflow.com/questions/254514/php-and-enumerations
    const OK = 1;
	//const OK_EMPTYARRAY = 2;
    const NOK = -100;
}
?>