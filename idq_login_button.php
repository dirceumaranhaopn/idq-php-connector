<?PHP
include 'php_idq_connector/idQServerPath.php';
include 'php_idq_connector/idQOauthClientInfo.php'; 
$url_oauth_idq=call_api_inbay($RedirectURL,$ApplicationID);
?>
<html>
<head>
<link type="text/css" rel="stylesheet" href="php_idq_connector/idq_php_connector_stylesheet.css">
</head>
<body>
    <a href='<?PHP echo $url_oauth_idq; ?>'><div class="btn-login-with-idq idq-button-inbay-blue"></div></a>
    <br />
    <a href="<?PHP echo $url_oauth_idq; ?>"><div class="btn-login-with-idq idq-button-inbay-light-grey"></div></a>
</body>
</html>