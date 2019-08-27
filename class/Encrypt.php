<?php
$data = $_GET;

//----------------------------------------- 1° ----------------------------------------->>>
//require_once('PHPConsoleLog.php');
define("ENCRYPTION_KEY", "!@#$%^&555777*");//https://stackoverflow.com/questions/16600708/how-do-you-encrypt-and-decrypt-a-php-string


$string = "This is the original data string!";

//Para Testar:
/*
echo $encrypted = encrypt($string, ENCRYPTION_KEY);
echo "<br />";
echo $decrypted = decrypt($encrypted, ENCRYPTION_KEY);
echo "<br />";
*/

/**
 * Returns an encrypted & utf8-encoded
 */
function encrypt($pure_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
    return $encrypted_string;
}

/**
 * Returns decrypted original string
 */
function decrypt($encrypted_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
    return $decrypted_string;
}
//----------------------------------------- 1° -----------------------------------------<<<



//----------------------------------------- 2° ----------------------------------------->>>
//Segundo exemplo de função para cripto e uncripto:


//https://nazmulahsan.me/simple-two-way-function-encrypt-decrypt-string/

/**
 * Encrypt and decrypt
 * 
 * @author Nazmul Ahsan <n.mukto@gmail.com>
 * @link http://nazmulahsan.me/simple-two-way-function-encrypt-decrypt-string/
 *
 * @param string $string string to be encrypted/decrypted
 * @param string $action what to do with this? e for encrypt, d for decrypt
 */
function my_simple_crypt( $string, $action = 'e' ) {
    // you may change these values to your own
    $secret_key = 'my_simple_secret_key';
    $secret_iv = 'my_simple_secret_iv';
 
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
 
    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }
 
    return $output;
}

//Para Testar:
/*
$encrypted = my_simple_crypt( $data["xx"], 'e' );
$decrypted = my_simple_crypt( $encrypted, 'd' );
var_dump($encrypted, $decrypted);
*/
//----------------------------------------- 2° -----------------------------------------<<<

?>