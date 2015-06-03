<?PHP
include_once 'idQOauthClientInfo.php';
include 'idQPHP.php';

$processed_token= getToken($RedirectURL, $ApplicationID, $ApplicationSecret);
$oauth_user_information=getUser($processed_token);
if($oauth_user_information){
    header("location:". $Page_After_Authentication);
}

?>