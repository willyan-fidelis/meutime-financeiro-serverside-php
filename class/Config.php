<?php

//require_once('PHPConsoleLog.php');

class ServerConfig {
	
	//Public & Private properties:
	private $LocalServer  = array("Server"=>"local", "Address"=>"localhost", "UserName"=>"root", "Pwd"=>"myroot", "DBName"=>"meutimec_mt_db");
	private $RemoteServer = array("Server"=>"remote", "Address"=>"meutime.co", "UserName"=>"meutimec_meutime", "Pwd"=>"meutime123456", "DBName"=>"meutimec_mt_db");
	
	function __construct()
	{
		
	}
	function GetServerInfo()
	{
		return (object)$this->RemoteServer; //RemoteServer//LocalServer
	}
	function DebugModeOn()
	{
		return true;
	}
	
	//Regras do negocio:
	private $CurrentRates = array("Fee"=>15.20);
	function BusinessRule_GetCurrentRates()
	{
		return (object)$this->CurrentRates;
	}
	
}
?>