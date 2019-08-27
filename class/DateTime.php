<?php
//require_once('Config.php');

class DT { //Date Time Class
	
	function __construct()
	{
		//this.$date = new DateTime();
	}

	/**
	 * Função responssável em ajustar a hora local:
	 */
	private function getTime(){
		$time = new DateTime();
		$time->sub(new DateInterval('PT04H00S'));
		return $time;
	}
	function GetNow_MySqlDateTimeFormat()
	{
		$time = $this->getTime();
		return $time->format("Y-m-d H:i:s");
		//return date("Y-m-d H:i:s");
	}
	function GetNow_AsaasFormat()
	{
		$time = $this->getTime();
		return $time->format("Y-m-d");
	}
	
	function GetNow_Year()
	{
		$time = $this->getTime();
		return $time->format("Y");
	}
	function GetNow_Month()
	{
		$time = $this->getTime();
		return $time->format("m");
	}
	function GetNow_Day()
	{
		$time = $this->getTime();
		return $time->format("d");
	}
}
?>