<?PHP
include_once 'idQOauthClientInfo.php';
include 'idQPHP.php';
if(isset($_GET['state'])){
    if($_GET['state']==$_SESSION["state_oauth"]){
        $_SESSION["auth_code_confirmed"]=$_GET['code'];
    }
};
$processed_token= getToken($RedirectURL, $ApplicationID, $ApplicationSecret);
$oauth_user_information=getUser($processed_token);

if($oauth_user_information){
    header("location:". $Page_After_Authentication);
}

?>