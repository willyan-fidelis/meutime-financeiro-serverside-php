<?php
require_once('../class/BTF_DtExg.php');
require_once('../class/Config.php');
require_once('../class/DateTime.php');

class StoredProcedure {
	
	public $RESULT  = array("OK"=>1,"OK_EMPTY_ARRAY"=>2,"NOK_GENERAL_ERROR"=>-100);
	
	function __construct()
	{
		//Create a Config instance to acess the system configuration data:
		$this->Config = new ServerConfig();
		
		//Create a data base conection:
		$this->DataExchange = new BackToFrontEndDtExg($this->Config->GetServerInfo()->Address, $this->Config->GetServerInfo()->UserName, $this->Config->GetServerInfo()->Pwd, $this->Config->GetServerInfo()->DBName); //$DBServerName, $DBUserName, $DBUserPwd, $DBName

	}
	
	function SpUserIsLoggedIn($_email,$session)
	{
		$sp_result = $this->DataExchange->ExecuteSPWithDataSet(
		false, "SpUserIsLoggedIn",
		//Selects Reults:
		array("Email"),
		//Parameters arguments Types, incliding outputs arguments:
		array("s", "s", "i"),
		//Parameters arguments Names Alias, incliding outputs arguments:
		array("email", "sessionCode", "result"),
		//Parameters arguments Passing, incliding outputs arguments:
		array($_email, $session, 0),
		//Parameters arguments that we need get back, incliding outputs arguments:
		array("result")
		);
		
		return $sp_result;
	}
	function StDeleteGroup($_groupOID,$_userOID)
	{
		$sp_result = $this->DataExchange->ExecuteSPWithDataSet(
		false, "StDeleteGroup",
		//Selects Reults:
		array("selectresults"),
		//Parameters arguments Types, incliding outputs arguments:
		array("i","i","i"),
		//Parameters arguments Names Alias, incliding outputs arguments:
		array("groupOID", "userOID", "result"),
		//Parameters arguments Passing, incliding outputs arguments:
		array($_groupOID, $_userOID, 0),
		//Parameters arguments that we need get back, incliding outputs arguments:
		array("result")
		);
		
		return $sp_result;
	}
	function SpReturnUserGroups($_userOID)
	{
		$sp_result = $this->DataExchange->ExecuteSPWithDataSet(
		false, "SpReturnUserGroups",
		//Selects Reults:
		array("TGroup_OID", "Name", "Description", "Date", "DateToPay", "Value", "Fee", "SubLink", "OwnerName", "OwnerEmail", "OwnerPhone", "UserPrivileges"),
		//Parameters arguments Types, incliding outputs arguments:
		array("i", "i"),
		//Parameters arguments Names Alias, incliding outputs arguments:
		array("userOID", "result"),
		//Parameters arguments Passing, incliding outputs arguments:
		array($_userOID, 0),
		//Parameters arguments that we need get back, incliding outputs arguments:
		array("result")
		);
		
		return $sp_result;
	}
	function StDeleteUserFromGroup($_groupOID,$_userToBeRemovedOID,$_userOID)
	{
		$sp_result = $this->DataExchange->ExecuteSPWithDataSet(
		false, "StDeleteUserFromGroup",
		//Selects Reults:
		array("selectresults"),
		//Parameters arguments Types, incliding outputs arguments:
		array("i","i","i","i"),
		//Parameters Arguments Names Alias, incliding outputs arguments:
		array("groupOID","userToBeRemovedOID","userOID","result"),
		//Parameters arguments Passing, incliding outputs arguments:
		array($_groupOID,$_userToBeRemovedOID,$_userOID, 0),
		//Parameters arguments that we need get back(Must be the same name in 'Parameters Arguments Names Alias'), incliding outputs arguments:
		array("result")
		);
		
		return $sp_result;
	}
	function SpReturnGroupUsers($groupOID)
	{
		$sp_result = $this->DataExchange->ExecuteSPWithDataSet(
		false, "SpReturnGroupUsers",
		//Selects Reults:
		array("UserOID", "Name", "Email", "Phone", "UserPrivileges"),
		//Parameters arguments Types, incliding outputs arguments:
		array("i", "i"),
		//Parameters Arguments Names Alias, incliding outputs arguments:
		array("groupOID", "result"),
		//Parameters arguments Passing, incliding outputs arguments:
		array($groupOID, 0),
		//Parameters arguments that we need get back(Must be the same name in 'Parameters Arguments Names Alias'), incliding outputs arguments:
		array("result")
		);
		
		return $sp_result;
	}
	function StCreateGroup($name,$desc,$createdDate,$DateToPay,$value,$Fee,$ownerOID)
	{
		$sp_result = $this->DataExchange->ExecuteSPWithDataSet(
		false, "StCreateGroup",
		//Selects Reults:
		array("selectresults"),
		//Parameters arguments Types, incliding outputs arguments:
		array("s","s","s","s","d","d","i","i"),
		//Parameters Arguments Names Alias, incliding outputs arguments:
		array("name", "desc", "date", "dateToPay", "value", "fee", "ownerOID", "result"),
		//Parameters arguments Passing, incliding outputs arguments:
		array($name, $desc, $createdDate, $DateToPay, $value, $Fee, $ownerOID, 0),
		//Parameters arguments that we need get back(Must be the same name in 'Parameters Arguments Names Alias'), incliding outputs arguments:
		array("result")
		);
		
		return $sp_result;
	}
	function StCreateUser($email, $pwd, $name, $phone, $cpf)
	{
		$sp_result = $this->DataExchange->ExecuteSPWithDataSet(
		false, "StCreateUser",
		//Selects Reults:
		array("selectresults"),
		//Parameters arguments Types, incliding outputs arguments:
		array("s","s","s","s","s","i"),
		//Parameters Arguments Names Alias, incliding outputs arguments:
		array("email", "pwd", "name", "phone", "cpf", "result"),
		//Parameters arguments Passing, incliding outputs arguments:
		array($email, $pwd, $name, $phone, $cpf, 0),
		//Parameters arguments that we need get back(Must be the same name in 'Parameters Arguments Names Alias'), incliding outputs arguments:
		array("result")
		);
		
		return $sp_result;
	}
	function StAddUserToGroup($date, $groupOID, $userOID, $privilegesOID)
	{
		$sp_result = $this->DataExchange->ExecuteSPWithDataSet(
		false, "StAddUserToGroup",
		//Selects Reults:
		array("selectresults"),
		//Parameters arguments Types, incliding outputs arguments:
		array("s","i","i","i","i"),
		//Parameters Arguments Names Alias, incliding outputs arguments:
		array("date", "groupOID", "UserOID", "priv", "result"),
		//Parameters arguments Passing, incliding outputs arguments:
		array($date, $groupOID, $userOID, $privilegesOID, 0),
		//Parameters arguments that we need get back(Must be the same name in 'Parameters Arguments Names Alias'), incliding outputs arguments:
		array("result")
		);
		
		return $sp_result;
	}
	function SpMySqlExecute_AlterRead($_queryType, $_query, $selectResult, $selectRessultsArrayAlias)
	{
		/*	Todas as requisões para as stored procedures retornam esse tipo de resposta, onde 'Parameters' são parametros que passamos a SP
		e que desejamos recuperar. Ao menos um parametro deve ser consultado, nesse casso 'result'.O array 'Results' possui dados retornados do 'SELECT' do mysql.
		
		Convenção de resultado 'result':
		Para requições que retornam uma coleção(array) esse valor corresponde ao numero de elementos requsitados, ou seja, valores >= 0 são respostas OK.
		Para outras consultas = 1 é OK
		Valores negativos são erros.
		
		Resposta tipica:
		echo json_encode( array("Parameters"=>array("result"=>"1"),"Results"=>array(0=>"x",1=>"y",2=>"z")) );
		*/
		
		$sp_result = $this->DataExchange->ExecuteSPWithDataSet(
		false, "SpMySqlExecute_AlterRead",
		//Selects Reults:
		$selectRessultsArrayAlias,
		//Parameters arguments Types, incliding outputs arguments:
		array("s","s","i","i"),
		//Parameters Arguments Names Alias, incliding outputs arguments:
		array("queryType", "query", "selectResult", "result"),
		//Parameters arguments Passing, incliding outputs arguments:
		array($_queryType, $_query,($selectResult == true) ? 1 : 0, 0),
		//Parameters arguments that we need get back(Must be the same name in 'Parameters Arguments Names Alias'), incliding outputs arguments:
		array("result")
		);
		
		return $sp_result;
	}
	
}

class PHPMySqlQuery { //Classe com codigos/query criadas quase que diretamentes do PHP, com o intermedio da classe 'StoredProcedure'
	
	public $RESULT  = array("OK"=>1,"OK_EMPTY_ARRAY"=>2,"NOK_GENERAL_ERROR"=>-100);
	
	function __construct()
	{
		//Create a Config instance to acess the system configuration data:
		$this->Config = new ServerConfig();
		
		//Create a data base conection:
		$this->SpCall = new StoredProcedure();

	}
	
	function update_userAsaasID($userEmail, $asaasID): bool
	{
		$sp_result = $this->SpCall->SpMySqlExecute_AlterRead("ALTER", "UPDATE `meutimec_mt_db`.`tuser` SET `AsaasID`='".$asaasID."' WHERE `Email`='".$userEmail."';",true, array("result"));
		
		return ($sp_result["Parameters"]["result"] >= 0) ? true : false;
	}
	
	function get_groupCashFlow_userToGroup($startDate, $endDate, $groupOID)
	{
		$sp_result = $this->SpCall->SpMySqlExecute_AlterRead("READ",
		"
		SELECT tuser.Name,tuser.Email,tcash.Date,tcash.TFinancialTransactionStatus_OID, tcash.CleanValue, tcash.GrossValue, tcash.Fee, tcash.Description, tcash.OID, tcash.tcashflowtype_OID, tcash.tcashtype_OID, tcash.AsaasID, tcash.AsaasDetail FROM tcash
		INNER JOIN tgroup ON tgroup.OID = tcash.TSourceWallet_OID
		INNER JOIN tuser ON tuser.OID = tgroup.Owner_TUser_OID
		WHERE tcash.tcashflowtype_OID = 1 and tcash.TDestinationWallet_OID = ".$groupOID." and tcash.Date BETWEEN '".$startDate."' AND '".$endDate."';
		",
		false, array("Name", "Email", "Date", "TFinancialTransactionStatus", "CleanValue", "GrossValue", "Fee", "Description", "CashOID", "TCashFlowType_OID", "TCashType_OID","AsaasID","AsaasDetail"));
		
		$res = ($sp_result["Parameters"]["result"] >= 0) ? true : false;
		return array("Results"=>$sp_result,"Result"=>$res);
	}
	function get_groupCashFlow_groupToUser($startDate, $endDate, $groupOID)
	{
		$sp_result = $this->SpCall->SpMySqlExecute_AlterRead("READ",
		"
		SELECT tuser.Name,tuser.Email,tcash.Date,tcash.TFinancialTransactionStatus_OID, tcash.CleanValue, tcash.GrossValue, tcash.Fee, tcash.Description, tcash.OID, tcash.tcashflowtype_OID, tcash.tcashtype_OID, tcash.AsaasID, tcash.AsaasDetail FROM tcash
		INNER JOIN tgroup ON tgroup.OID = tcash.TDestinationWallet_OID
		INNER JOIN tuser ON tuser.OID = tgroup.Owner_TUser_OID
		WHERE tcash.tcashflowtype_OID = 2 and tcash.TSourceWallet_OID = ".$groupOID." and tcash.Date BETWEEN '".$startDate."' AND '".$endDate."';
		",
		false, array("Name", "Email", "Date", "TFinancialTransactionStatus", "CleanValue", "GrossValue", "Fee", "Description", "CashOID", "TCashFlowType_OID", "TCashType_OID","AsaasID","AsaasDetail"));
		
		$res = ($sp_result["Parameters"]["result"] >= 0) ? true : false;
		return array("Results"=>$sp_result,"Result"=>$res);
	}

	/**
	 * Cria uma fluxo de caixa manual.
	 * @param int $userOID ID do usuario.
	 * @param int $groupOID ID do grupo.
	 * @param float $value Valor.
	 * @param string $description Descrição da cobrança.
	 * @param bool $userToGroupFlow Dinheiro do usuario para o grupo.
	 * @return array(array(Results), bool Result)
	 * 
	*/
	function createManualCashFlow(int $userOID, $groupOID, float $value, string $description, int $cashflowtype)
	{
		
		$DateTime = new DT();
		$now = $DateTime->GetNow_MySqlDateTimeFormat();
		
		$result = $this->getUserRootWalletOID($userOID); //$result["WalletOID"]
		if($cashflowtype == 1){$sourceOID=$result["WalletOID"];$destinationOID=$groupOID;}else{$sourceOID=$groupOID;$destinationOID=$result["WalletOID"];}
		
		$sp_result = $this->SpCall->SpMySqlExecute_AlterRead("ALTER",
		"
		INSERT INTO `tcash` 
		(`TSourceWallet_OID`, `TDestinationWallet_OID`, `Date`, `CleanValue`, `GrossValue`, `Fee`, `Description`, `TFinancialTransactionStatus_OID`, `tcashtype_OID`, `AsaasID`, `tcashflowtype_OID`) 
		VALUES ('".$sourceOID."', '".$destinationOID."', '".$now ."', '".$value."', '".$value."', '0.00', '".$description."', '1000', '10', 'xxx', '".$cashflowtype."');
		",
		true, array("SelectResult"));
		
		$res = ($sp_result["Parameters"]["result"] >= 0) ? true : false;
		return array("Results"=>$sp_result,"Result"=>$res);
	}

	/**
	 * Apaga uma fluxo de caixa manual.
	 * @param int $CashOID ID do FlowCash.
	 * @return array(array(Results), bool Result)
	 * 
	*/
	function deleteManualCashFlow(int $CashOID)
	{		
		$sp_result = $this->SpCall->SpMySqlExecute_AlterRead("ALTER",
		"
		DELETE FROM `tcash` WHERE `OID`='".$CashOID."' and`tcashtype_OID`!= 0;
		",
		true, array("SelectResult"));
		
		$res = ($sp_result["Parameters"]["result"] == 1) ? true : false;
		return array("Results"=>$sp_result,"Result"=>$res);
	}
	/**
	 * Apaga uma fluxo de caixa manual.
	 * @param int $CashOID ID do FlowCash.
	 * @return array(array(Results), bool Result)
	 * 
	*/
	function updateManualCashFlow(float $value,string $description,int $financialTransactionStatusOID,int $CashOID)
	{		
		$sp_result = $this->SpCall->SpMySqlExecute_AlterRead("ALTER",
		"
		UPDATE `meutimec_mt_db`.`tcash` SET `tcashtype_OID`='10', `CleanValue`='".$value."', `GrossValue`='".$value."', `Description`='".$description."', `TFinancialTransactionStatus_OID`='".$financialTransactionStatusOID."' WHERE `OID`='".$CashOID."' and`tcashtype_OID`!='0';
		",
		true, array("SelectResult"));
		
		$res = ($sp_result["Parameters"]["result"] == 1) ? true : false;
		return array("Results"=>$sp_result,"Result"=>$res);
	}
	function getUserRootWalletOID($userOID)
	{
		$sp_result = $this->SpCall->SpMySqlExecute_AlterRead("READ",
		"
		select tgroup.OID FROM tgroup where Owner_TUser_OID = ".$userOID." and twallettypeenum_OID = 2;
		",
		false, array("UserRootWalletOID"));
		
		$res = ($sp_result["Parameters"]["result"] == 1) ? true : false;
		return array("WalletOID"=>$sp_result["Results"][0]["UserRootWalletOID"],"Result"=>$res);
	}
	
}

class ReplayMySqlQuery { //Classe com codigos/query criadas quase que diretamentes do PHP, com o intermedio da classe 'StoredProcedure'
	
	public $RESULT  = array("OK"=>1,"OK_EMPTY_ARRAY"=>2,"NOK_GENERAL_ERROR"=>-100);
	
	function __construct()
	{
		//Create a Config instance to acess the system configuration data:
		$this->Config = new ServerConfig();
		
		//Create a data base conection:
		$this->SpCall = new StoredProcedure();

	}
	
	function updateButtonConnection($buttonOID,$pwd)//Atualiza a hora da ultima conexão do botão.
	{
		$DateTime = new DT();
		$now = $DateTime->GetNow_MySqlDateTimeFormat();

		if($this->checkButtonPwd($buttonOID,$pwd)){}else{
			return array("Time"=>$now,"Result"=>false);
		}

		$sp_result = $this->SpCall->SpMySqlExecute_AlterRead("ALTER",
		"
		UPDATE `tshotsavebutton` SET `LastConnection`='".$now."' WHERE `OID`='".$buttonOID."';
		",
		true, array("result"));
		
		$res = ($sp_result["Parameters"]["result"] == 1) ? true : false;
		return array("Time"=>$now,"Result"=>$res);
	}
	function addButtonShot($buttonOID,$pwd)//Adiciona um evento de botão precionado na base de dados.
	{
		$DateTime = new DT();
		$now = $DateTime->GetNow_MySqlDateTimeFormat();

		if($this->checkButtonPwd($buttonOID,$pwd)){}else{
			return array("Time"=>$now,"Result"=>false);
		}

		$sp_result = $this->SpCall->SpMySqlExecute_AlterRead("ALTER",
		"
		INSERT INTO `tshotsavebuttonevent` (`tshotsavebutton_OID`, `Time`) VALUES ('".$buttonOID."', '".$now."');
		",
		true, array("result"));
		
		$res = ($sp_result["Parameters"]["result"] == 1) ? true : false;
		return array("Time"=>$now,"Result"=>$res);
	}
	function checkButtonPwd($buttonOID, $pwd)
	{

		$sp_result = $this->SpCall->SpMySqlExecute_AlterRead("READ",
		"
		select Pwd from tshotsavebutton where OID = ".$buttonOID.";
		",
		false, array("result"));
		
		$res = ($sp_result["Results"][0]["result"] == $pwd) ? true : false;
		return $res;
	}
	
	
}

?>