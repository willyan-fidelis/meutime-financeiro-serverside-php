<?php
require_once('Config.php');

//https://www.w3schools.com/php/func_mail_mail.asp
//https://stackoverflow.com/questions/5335273/how-to-send-an-email-using-php

//$Config = new ServerConfig();

class EmailManager {
	
	//Public & Private properties:
	private $LocalServer  = array("Address"=>"localhost", "UserName"=>"root", "Pwd"=>"myroot", "DBName"=>"meutimec_mt_db");
	private $RemoteServer = array("Address"=>"meutime.co", "UserName"=>"meutimec_meutime", "Pwd"=>"meutime123456", "DBName"=>"meutimec_mt_db");
	
	
	function __construct()
	{
		$this->Config = new ServerConfig();
	}
	function SendNewUserEmail($_to,$_toCC,$_from,$_link)
	{
		$to = $_to;
		$subject = "Cadastro MeuTime";
		
		//https://www.w3schools.com/html/tryit.asp?filename=tryhtml_layout_float
		$message = '
		<!DOCTYPE html>
		<html lang="en">
		<head>
		<title>CSS Template</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		* {
		  box-sizing: border-box;
		}

		body {
		  font-family: Arial, Helvetica, sans-serif;
		}

		/* Style the header */
		header {
		  background-color: #666;
		  padding: 30px;
		  text-align: center;
		  font-size: 35px;
		  color: white;
		}

		/* Container for flexboxes */
		section {
		  display: -webkit-flex;
		  display: flex;
		}

		/* Style the navigation menu */
		nav {
		  -webkit-flex: 1;
		  -ms-flex: 1;
		  flex: 1;
		  background: #ccc;
		  padding: 20px;
		}

		/* Style the list inside the menu */
		nav ul {
		  list-style-type: none;
		  padding: 0;
		}

		/* Style the content */
		article {
		  -webkit-flex: 3;
		  -ms-flex: 3;
		  flex: 3;
		  background-color: #f1f1f1;
		  padding: 10px;
		}

		/* Style the footer */
		footer {
		  background-color: #777;
		  padding: 10px;
		  text-align: center;
		  color: white;
		}

		/* Responsive layout - makes the menu and the content (inside the section) sit on top of each other instead of next to each other */
		@media (max-width: 600px) {
		  section {
			-webkit-flex-direction: column;
			flex-direction: column;
		  }
		}
		</style>
		</head>
		<body>

		<h2>Cadastro MeuTime</h2>
		<p>Parabéns <b>
		'
		. $_to .
		'
		</b> por fazer parte do meutime.</p>
		<p>Seu cadastro esta quase pronto. Clique no link abaixo para ativar sua conta:</p>
		<a href="
		'
		. $_link .
		'
		">Confirme seu cadastro clicando aqui!</a>

		<header>
		  <h2>MeuTime</h2>
		</header>

		<section>
		  
		  <article>
			<h1>Gerencie as finanças e muito mais</h1>
			<p>Crie grupos. Adicione seus amigos. Gerencie cobranças automaticas.</p>
			<p>Tudo isso e muito mais! Comece agora mesmo.</p>
		  </article>
		</section>

		<footer>
		  <p>Equipe MeuTime</p>
		  <a href="http://meutime.co/app">MeuTime App</a>
		</footer>

		</body>
		</html>

		';

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: <' . $_from .'>' . "\r\n";
		$headers .= 'Cc: '. $_toCC . "\r\n";
		
		if ($this->Config->GetServerInfo()->Server == "local"){return;}
		
		mail($to,$subject,$message,$headers);
	}
	function SendUserAccountReactivationEmail($_to,$_toCC,$_from,$_link)
	{
		$to = $_to;
		$subject = "Reativação de conta - MeuTime";
		
		//https://www.w3schools.com/html/tryit.asp?filename=tryhtml_layout_float
		$message = '
		<!DOCTYPE html>
		<html lang="en">
		<head>
		<title>CSS Template</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		* {
		  box-sizing: border-box;
		}

		body {
		  font-family: Arial, Helvetica, sans-serif;
		}

		/* Style the header */
		header {
		  background-color: #666;
		  padding: 30px;
		  text-align: center;
		  font-size: 35px;
		  color: white;
		}

		/* Container for flexboxes */
		section {
		  display: -webkit-flex;
		  display: flex;
		}

		/* Style the navigation menu */
		nav {
		  -webkit-flex: 1;
		  -ms-flex: 1;
		  flex: 1;
		  background: #ccc;
		  padding: 20px;
		}

		/* Style the list inside the menu */
		nav ul {
		  list-style-type: none;
		  padding: 0;
		}

		/* Style the content */
		article {
		  -webkit-flex: 3;
		  -ms-flex: 3;
		  flex: 3;
		  background-color: #f1f1f1;
		  padding: 10px;
		}

		/* Style the footer */
		footer {
		  background-color: #777;
		  padding: 10px;
		  text-align: center;
		  color: white;
		}

		/* Responsive layout - makes the menu and the content (inside the section) sit on top of each other instead of next to each other */
		@media (max-width: 600px) {
		  section {
			-webkit-flex-direction: column;
			flex-direction: column;
		  }
		}
		</style>
		</head>
		<body>

		<h2>Reativação de conta</h2>
		<p>Bem vindo de volta <b>
		'
		. $_to .
		'
		</b>.</p>
		<p>Clique no link abaixo para reativar sua conta:</p>
		<a href="
		'
		. $_link .
		'
		">Confirme seu cadastro clicando aqui!</a>

		<header>
		  <h2>MeuTime</h2>
		</header>

		<section>
		  
		  <article>
			<h1>Gerencie as finanças e muito mais</h1>
			<p>Crie grupos. Adicione seus amigos. Gerencie cobranças automaticas.</p>
			<p>Tudo isso e muito mais! Comece agora mesmo.</p>
		  </article>
		</section>

		<footer>
		  <p>Equipe MeuTime</p>
		  <a href="http://meutime.co/app">MeuTime App</a>
		</footer>

		</body>
		</html>

		';

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: <' . $_from .'>' . "\r\n";
		$headers .= 'Cc: '. $_toCC . "\r\n";
		
		if ($this->Config->GetServerInfo()->Server == "local"){return;}
		
		mail($to,$subject,$message,$headers);
	}
	function SendGeneralPurposeEmail($_to,$_toCC,$_from,$_subject,$_content)
	{
		$to = $_to;
		$subject = $_subject;
		
		//https://www.w3schools.com/html/tryit.asp?filename=tryhtml_layout_float
		$message = '
		<!DOCTYPE html>
		<html lang="en">
		<head>
		<title>MeuTime</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		* {
		  box-sizing: border-box;
		}

		body {
		  font-family: Arial, Helvetica, sans-serif;
		}

		/* Style the header */
		header {
		  background-color: #666;
		  padding: 30px;
		  text-align: center;
		  font-size: 35px;
		  color: white;
		}

		/* Container for flexboxes */
		section {
		  display: -webkit-flex;
		  display: flex;
		}

		/* Style the navigation menu */
		nav {
		  -webkit-flex: 1;
		  -ms-flex: 1;
		  flex: 1;
		  background: #ccc;
		  padding: 20px;
		}

		/* Style the list inside the menu */
		nav ul {
		  list-style-type: none;
		  padding: 0;
		}

		/* Style the content */
		article {
		  -webkit-flex: 3;
		  -ms-flex: 3;
		  flex: 3;
		  background-color: #f1f1f1;
		  padding: 10px;
		}

		/* Style the footer */
		footer {
		  background-color: #777;
		  padding: 10px;
		  text-align: center;
		  color: white;
		}

		/* Responsive layout - makes the menu and the content (inside the section) sit on top of each other instead of next to each other */
		@media (max-width: 600px) {
		  section {
			-webkit-flex-direction: column;
			flex-direction: column;
		  }
		}
		</style>
		</head>
		<body>

		<h2>
		'
		. $_subject .
		'
		</h2>
		<p>Olá <b>
		'
		. $_to .
		'
		</b>.</p>
		'
		. $_content .
		'

		<header>
		  <h2>MeuTime</h2>
		</header>

		<section>
		  
		  <article>
			<h1>Gerencie as finanças e muito mais</h1>
			<p>Crie grupos. Adicione seus amigos. Gerencie cobranças automaticas.</p>
			<p>Tudo isso e muito mais! Comece agora mesmo.</p>
		  </article>
		</section>

		<footer>
		  <p>Equipe MeuTime</p>
		  <a href="http://meutime.co/app">MeuTime App</a>
		</footer>

		</body>
		</html>

		';

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: <' . $_from .'>' . "\r\n";
		$headers .= 'Cc: '. $_toCC . "\r\n";
		
		
		if ($this->Config->GetServerInfo()->Server == "local"){return;}
		
		mail($to,$subject,$message,$headers);
	}
	function SendFreeEmail($_to,$_toCC,$_from,$_subject,$_content)
	{
		$to = $_to;
		$subject = $_subject;
		
		//https://www.w3schools.com/html/tryit.asp?filename=tryhtml_layout_float
		$message = '
		<!DOCTYPE html>
		<html lang="en">
		<head>
		<title>MeuTime</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
		* {
		  box-sizing: border-box;
		}

		body {
		  font-family: Arial, Helvetica, sans-serif;
		}

		/* Style the header */
		header {
		  background-color: #666;
		  padding: 30px;
		  text-align: center;
		  font-size: 35px;
		  color: white;
		}

		/* Container for flexboxes */
		section {
		  display: -webkit-flex;
		  display: flex;
		}

		/* Style the navigation menu */
		nav {
		  -webkit-flex: 1;
		  -ms-flex: 1;
		  flex: 1;
		  background: #ccc;
		  padding: 20px;
		}

		/* Style the list inside the menu */
		nav ul {
		  list-style-type: none;
		  padding: 0;
		}

		/* Style the content */
		article {
		  -webkit-flex: 3;
		  -ms-flex: 3;
		  flex: 3;
		  background-color: #f1f1f1;
		  padding: 10px;
		}

		/* Style the footer */
		footer {
		  background-color: #777;
		  padding: 10px;
		  text-align: center;
		  color: white;
		}

		/* Responsive layout - makes the menu and the content (inside the section) sit on top of each other instead of next to each other */
		@media (max-width: 600px) {
		  section {
			-webkit-flex-direction: column;
			flex-direction: column;
		  }
		}
		</style>
		</head>
		<body>

		<h2>
		'
		. $_subject .
		'
		</h2>
		<p>Olá <b>
		'
		. 'Administradores MeuTime' .
		'
		</b>.</p>
		'
		. $_content .
		'

		<header>
		  <h2>MeuTime</h2>
		</header>

		<section>
		  
		  <article>
			<h1>Gerencie as finanças e muito mais</h1>
			<p>Crie grupos. Adicione seus amigos. Gerencie cobranças automaticas.</p>
			<p>Tudo isso e muito mais! Comece agora mesmo.</p>
		  </article>
		</section>

		<footer>
		  <p>Equipe MeuTime</p>
		  <a href="http://meutime.co/app">MeuTime App</a>
		</footer>

		</body>
		</html>

		';

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: <' . $_from .'>' . "\r\n";
		$headers .= 'Cc: '. $_toCC . "\r\n";
		
		
		if ($this->Config->GetServerInfo()->Server == "local"){return;}
		
		mail($to,$subject,$message,$headers);
	}
	function SendSimpleEmail()
	{
		$to      = 'edu@hotmail.com';
		$subject = 'The subject - test';
		$message = 'Hello!';
		$headers = 'From: edu@hotmail.com' . "\r\n" .
			'Reply-To: edu@hotmail.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
		
		if ($this->Config->GetServerInfo()->Server == "local"){return;}
		
		mail($to, $subject, $message, $headers);
	}
	
}

//-----------------------------------------------
//require_once('../class/Email.php');
//Para testar:
//$Email = new EmailManager();
//$Email->SendGeneralPurposeEmail('edu@gmail.com','cadastro@meutime.co','cadastro@meutime.co', 'Erro na confirmação de cadastro','Ocorreu um erro ao executar a operação. Entre em contato com <b>contato@meutime.co</b> para maiores detalhes');
//$Email->SendGeneralPurposeEmail('edu@gmail.com','cadastro@meutime.co','cadastro@meutime.co', 'Usuário não encontrado','Ocorreu um erro ao executar a operação. Entre em contato com <b>contato@meutime.co</b> para maiores detalhes');
?>