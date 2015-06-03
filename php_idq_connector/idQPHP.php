<?PHP
//I opted to a 'verbose' style to make easy to understand the plugin. You can use curl_setopt_array in the curl calls, and separete each function as method of class.

include 'idQServerPath.php';

//transform the instances of idQ into Globals variables to future use.
global $idq_challenge_instance_url, $idq_auth_instance_url, $idq_challenge_instance_url, $idq_token_instance_url, $idq_user_instance_url;
$idq_challenge_instance_url=$idq_challenge_instance;
$idq_auth_instance_url=$idq_auth_instance;
$idq_status_instance_url=$idq_status_instance;
$idq_token_instance_url=$idq_token_instance;
$idq_user_instance_url=$idq_user_instance;


function getQRString($redirect_url, $cliend_id, $client_secret){

    
    //fetch responsewith response of authorization url
    //the authorization url is responsible to generate the header response about the authorization of end user.
    $_SESSION["state_oauth"]=generate_state(16);
    $response=call_authorization_url($redirect_url, $cliend_id, 0, $_SESSION["state_oauth"]);
    $cookie_info=explode('Set-Cookie: ',$response);
    $cookie_oauth=$cookie_info[1];
    
    //instance that ask the QR string
    global $idq_challenge_instance_url;
    $idq_challenge_instance=$idq_challenge_instance_url;
    
    //new curl resource to access the new instance
    $challenge_idq=curl_init();

    curl_setopt($challenge_idq, CURLOPT_URL, $idq_challenge_instance);

    //content-type of challenge_idq
    curl_setopt($challenge_idq, CURLOPT_HTTPHEADER, array('Content-Type: text/plain', 'Cookie: '.$cookie_oauth));
    
    //save the auth cookie into OAuth class.
    
    $_SESSION["cookie_auth"]=$cookie_oauth;
    
    //define redirect false
    curl_setopt($challenge_idq, CURLOPT_FOLLOWLOCATION, false);

    //get the response of the page inside code instead of output directly to browse.
    curl_setopt($challenge_idq, CURLOPT_RETURNTRANSFER, 1);

    //define method as GET
    curl_setopt($challenge_idq, CURLOPT_CUSTOMREQUEST, "GET");
    
    //get result of curl call
    $qr_string=curl_exec($challenge_idq);
    
    //closing resource
    curl_close($challenge_idq);
    
    //return string that will be used as content of QR code.
    return $qr_string;
}


function call_authorization_url($redirect_url, $cliend_id, $typeTransaction, $state_id){
    
//idQ Oauth Authorization instance
global $idq_auth_instance_url;
$idq_auth_instance=$idq_auth_instance_url;


    //create curl instance
    $curl_initial_idq=curl_init();

    //define post query content
    //Response type is "code", to transactions and authorizations.
    //redirect uir is the URL that will handle the Oauth token post.
    //state is a identification of the client.
    //cliend id is the client identification, generated in myidq.inbaytech.com portal.
    $post_content_array=array(
        "response_type"=>"code",
        "redirect_uri"=>$redirect_url,
        "state"=>$state_id,
        "client_id"=>$cliend_id,
    );

    //build the post in query string format($post_content);
    $post_content=http_build_query($post_content_array);

    $idq_auth_url=$idq_auth_instance."?".$post_content;

    //define the URL base
    curl_setopt($curl_initial_idq, CURLOPT_URL, $idq_auth_url);

    //define method as GET
    curl_setopt($curl_initial_idq, CURLOPT_CUSTOMREQUEST, "GET");

    //define redirect false
    curl_setopt($curl_initial_idq, CURLOPT_FOLLOWLOCATION, false);


    //get the response of the page inside code instead of output directly to browse.
    curl_setopt($curl_initial_idq, CURLOPT_RETURNTRANSFER, 1);

    //get and post the header of response
    curl_setopt($curl_initial_idq, CURLINFO_HEADER_OUT, 1);
    curl_setopt($curl_initial_idq, CURLOPT_HEADER, 1);

    //content-type
    if($typeTransaction==0){
        curl_setopt($curl_initial_idq, CURLOPT_HTTPHEADER, array('Content-Type: text/plain', 'Connection: Keep-Alive'));
    }else{
        curl_setopt($curl_initial_idq, CURLOPT_HTTPHEADER, array('Content-Type: text/plain', 'Cookie: '.$_SESSION["cookie_auth"]));
    }

    //executing curl, return header cookie string
    $response=curl_exec($curl_initial_idq);
    
    //close resource
    curl_close($curl_initial_idq);
    
    return $response;
}

//check if the qr was scanned and by who.
function check_status($redirect_url, $cliend_id){
    
//instance that ask if the QR was scanned
global $idq_status_instance_url;
$idq_status_instance=$idq_status_instance_url;


    //new curl resource to access the new instance
    $status_idq=curl_init();
    curl_setopt($status_idq, CURLOPT_URL, $idq_status_instance);

    //content-type of challenge_idq
    curl_setopt($status_idq, CURLOPT_HTTPHEADER, array('Content-Type: text/plain', 'Cookie: '.$_SESSION["cookie_auth"]));
    
    //define redirect false
    curl_setopt($status_idq, CURLOPT_FOLLOWLOCATION, false);

    //get the response of the page inside code instead of output directly to browse.
    curl_setopt($status_idq, CURLOPT_RETURNTRANSFER, 1);

    //define method as GET
    curl_setopt($status_idq, CURLOPT_CUSTOMREQUEST, "GET");

    //define if the qr was scanned. 0=no , 1=yes.
    $result_status=curl_exec($status_idq);
    
    curl_close($status_idq);

    //if qr scanned, verify by who.
    if($result_status==1){
        //ask the the the new response of authorization after scan (expect oauth user)
        $auth_response=call_authorization_url($redirect_url, $cliend_id, 1, $_SESSION["state_oauth"]);
        
        //parse the new response, that have a HTML format to get the query strings code and state 
        $array_response_auth=parse_url($auth_response,PHP_URL_QUERY);
        parse_str($array_response_auth);
        
        //verify if the state of response is the same of the current session.
        $final_code=substr($code, 0,32);
            if($state==$_SESSION["state_oauth"]){
                $_SESSION["auth_code_confirmed"]=$final_code;
                return true;
            }
    }
    
    
}

//function to exchange 
function getToken($redirect_url, $client_id, $client_secret){

//instance that ask the token in exchange of code.
global $idq_token_instance_url;
$idq_token_instance=$idq_token_instance_url;
//$idq_token_instance='https://inbay.idquanta.com:8087/idqoauth/api/v1/token';
    
    //initiate curl of token instance
    $token_idq=curl_init();
    curl_setopt($token_idq, CURLOPT_URL, $idq_token_instance);
    
    //setting regular http post
    curl_setopt($token_idq, CURLOPT_POST, true);

    $grant_type="authorization_code";
    $query  = 'client_id=' . $client_id . '&';
    $query .= 'client_secret=' . $client_secret . '&';
    $query .= 'grant_type='. $grant_type . '&';
    $query .= 'code='. $_SESSION["auth_code_confirmed"]. '&';
    $query .= 'redirect_uri='. urlencode($redirect_url);
    //set the post fields.
    curl_setopt($token_idq, CURLOPT_POSTFIELDS, $query);
    
    //get the response of the page inside code instead of output directly to browse.
    curl_setopt($token_idq, CURLOPT_RETURNTRANSFER, 1);
    
    //receive token
    $token_oauth=curl_exec($token_idq);
    curl_close($token_idq);

    
    //reading JSON reponse
    $json_object_token=json_decode($token_oauth, true);
    
    return $json_object_token['access_token'];
}

//function to get user information
function getUser($token_exchange){

    //instance to get exchange token by token
    global $idq_user_instance_url;
    $idq_user_instance=$idq_user_instance_url;
    //$idq_user_instance='https://inbay.idquanta.com:8087/idqoauth/api/v1/user';
    
    $post_content_array=array(
        "access_token"=>$token_exchange,
    );

    //build the post in query string format($post_content);
    $post_content=http_build_query($post_content_array);

    $idq_user_instance_final=$idq_user_instance."?".$post_content;
    
    //initiate curl of token instance
    $user_instance=curl_init();
    curl_setopt($user_instance, CURLOPT_URL, $idq_user_instance_final);
    
    //define redirect false
    curl_setopt($user_instance, CURLOPT_FOLLOWLOCATION, false);

    //get the response of the page inside code instead of output directly to browse.
    curl_setopt($user_instance, CURLOPT_RETURNTRANSFER, 1);

    //define method as GET
    curl_setopt($user_instance, CURLOPT_CUSTOMREQUEST, "GET");
    
    //executing curl to get json
    $response=curl_exec($user_instance);
    
    //close resource
    curl_close($user_instance);
    
    //reading JSON reponse
    $jspn_object_user=json_decode($response, true);
    
    //save the user inside a session. That is the end result of the connector.
    $_SESSION['idq_user_oauth_id']=$jspn_object_user['username'];
    $_SESSION['idq_user_oauth_email']=$jspn_object_user['email'];
    
    //return true, indicating the user was saving into the session.
    return true;
    
    
}

?>