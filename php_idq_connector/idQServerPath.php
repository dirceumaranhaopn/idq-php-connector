<?PHP
session_start();

//instance that ask the QR string
$idq_challenge_instance='https://inbay.idquanta.com:8087/idqoauth/api/v1/challenge';

//idQ Oauth Authorization instance
$idq_auth_instance='https://inbay.idquanta.com:8087/idqoauth/api/v1/auth';

//instance that ask if the QR was scanned
$idq_status_instance='https://inbay.idquanta.com:8087/idqoauth/api/v1/status';

//instance that ask the token in exchange of code.
$idq_token_instance='https://inbay.idquanta.com:8087/idqoauth/api/v1/token';

//instance to get exchange token by token
$idq_user_instance='https://inbay.idquanta.com:8087/idqoauth/api/v1/user';

function call_api_inbay($redirect_url, $cliend_id){
    
    global $idq_auth_instance;
    
    $_SESSION["state_oauth"]=generate_state(16);
    
    $post_content_array=array(
        "response_type"=>"code",
        "redirect_uri"=>$redirect_url,
        "state"=>$_SESSION["state_oauth"],
        "client_id"=>$cliend_id,
    );

    //build the post in query string format($post_content);
    $post_content=http_build_query($post_content_array);

    $idq_auth_url=$idq_auth_instance."?".$post_content;
    
    return $idq_auth_url;
}
?>