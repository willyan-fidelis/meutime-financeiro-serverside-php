<?php
//require_once('../api/SpCall.php');
require_once('Config.php');
require_once('DateTime.php');
require_once('Asaas.php');

class PaymentAPI {
	
	//Public & Private properties:
	//private $ProductionURL  = array("URL"=>"https://www.asaas.com/api/v3/", "Token"=>"7fdd68db9617f347b505004346445dc10ac789b9c3d6c5329ca1bef3657a4162");
	//private $SandboxURL 	= array("URL"=>"https://sandbox.asaas.com/api/v3/", "Token"=>"8bde1c6502c074849ab8d51143aea227e07552a5ee0c88c6ba7319a51b4051e1");
	
	function __construct()
	{
		//Declara e instancia as classes necessarias:
		$this->Config 		= new ServerConfig();
		$this->DateTime 	= new DT();
		$this->AsaasAPI 	= new AsaasAPI();
	}
	//function GetServerInfo()
	//{
		//return (object)$this->SandboxURL; //ProductionURL//SandboxURL
	//}
	function PayTodayAndSubscribeNextMonthWithCC($CUSTOMER_ID,$value,$description,$dayToPay,$cc_name,$cc_number,$cc_month,$cc_year,$cc_ccv,$cch_name,$cch_email,$cch_cpf,$cch_postalcode,$cch_address_number,$cch_phone)
	{
		//Calculamos o mes e ano da proxima cobrança(mes que vem):
		$nextPaymentMonth = $this->DateTime->GetNow_Month() + 1;
		$nextPaymentYear  = $this->DateTime->GetNow_Year();
		if($nextPaymentMonth > 12){$nextPaymentMonth=1;$nextPaymentYear  = $this->DateTime->GetNow_Year()+1;}
		//var_dump($nextPaymentMonth);var_dump($nextPaymentYear);
		
		$result1 = $this->AsaasAPI->AddPaymentViaCreditCard($CUSTOMER_ID,$value,$description,$this->DateTime->GetNow_AsaasFormat(),"xyz",$cc_name,$cc_number,$cc_month,$cc_year,$cc_ccv,$cch_name,$cch_email,$cch_cpf,$cch_postalcode,$cch_address_number,$cch_phone);
		//var_dump($result1);
		$result2 = $this->AsaasAPI->AddSubscriptionViaCreditCard($CUSTOMER_ID,$value,$description,$nextPaymentYear."-".$nextPaymentMonth."-".$dayToPay,$cc_name,$cc_number,$cc_month,$cc_year,$cc_ccv,$cch_name,$cch_email,$cch_cpf,$cch_postalcode,$cch_address_number,$cch_phone);
		//var_dump($result2);
		
		
		if($result1["Result"] == 1 and $result2["Result"] == 1){
			
			return array("Result"=>AsaasAPIGenericResponseStatusEnum::OK,"APIResponsePay"=>$result1["APIResponse"],"APIResponseSub"=>$result2["APIResponse"]);
		}
		else{
			return array("Result"=>AsaasAPIGenericResponseStatusEnum::NOK,"APIResponsePay"=>$result1["APIResponse"],"APIResponseSub"=>$result2["APIResponse"]);
		}
	}
	
	
}

abstract class PaymentXYZEnum
{
	//https://stackoverflow.com/questions/254514/php-and-enumerations
    const OK = 1;
    const NOK = -100;
}
?>