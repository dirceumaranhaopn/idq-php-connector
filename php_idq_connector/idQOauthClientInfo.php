<?PHP
/**********************/
//VARIABLES/
//Call Back Url, URI that will handle the communication between client and idQ Server
$RedirectURL="YOUR_SITE_URI/php_idq_connector/idQRedirectUrl.php";

//Your Application ID and Secret generetated in myidq.inbaytech.com portal
$ApplicationID="YOUR_APP_ID";
$ApplicationSecret="YOUR_APP_SECRET";

//page to redirect user after session $_SESSION['idq_user_oauth_id'] has been created with idQ User ID inside.
$Page_After_Authentication="URI_AFTER_AUTHENTICATION";
/**********************/

//generate a unique id to be used to recognize what session is being authorized.
function generate_state($l, $c = 'abcdefghijklmnopqrstuvwxyz1234567890') {
    for ($s = '', $cl = strlen($c)-1, $i = 0; $i < $l; $s .= $c[mt_rand(0, $cl)], ++$i);
    return $s;
}
?>