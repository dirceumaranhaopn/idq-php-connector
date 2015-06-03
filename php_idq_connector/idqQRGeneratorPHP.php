<?PHP
include_once 'idQOauthClientInfo.php';
include 'idQPHP.php';

echo getQRString($RedirectURL, $ApplicationID, $ApplicationSecret);


?>