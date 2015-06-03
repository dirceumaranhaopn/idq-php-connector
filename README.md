# idq-php-connector
Introduction
 idQ Trusted Services 
idQ Trusted Services is a product of inBay Technologies, that allows any web application enhance its security, replacing login/password or acting as second factor. To do that we use OAuth 2.0 protocol. 
OAuth 2.0 
OAuth 2.0 is the next evolution of the OAuth protocol which was originally created in late 2006. OAuth 2.0 focuses on client developer simplicity while providing specific authorization flows for web applications, 
desktop applications, mobile phones, and living room devices. (http://oauth.net/2/) 
Steps and Context 
1. About The Connector 
1.1 Connector (API) Goal 
The Goal of this API is reduce the time to connect idQ Trusted Services with your web application or web site. 
1.2 Requirements 
To work as expected the API needs has the minimal requirements: 
Connection with internet.
The application must use PHP as code language. 
The responsible for integrate the application and idQ Trusted Services need register the web application into myidq.inbaytech.com (if you need help to do that, please read the extra content of this file). 
Have a Client Id
Have a Client Secret
Set up redirect URI point to ... 
1.3 Expected Result 
Once the user has been authenticated the end result will be the creation of two sessions: 
$_SESSION['idq_user_oauth_id'] -> contains the OAuth ID of authenticated user. $_SESSION['idq_user_oauth_email'] - > contains the email registered into idQ Trusted Services of OAuth user authenticated. 
2. Connecting 
2.1 Step One 
Copy the PHP connector to your site files. Please observe the folder structure: 
idq_login_button.php 
iqr_php_generation.html 
php_idq_connector/ 
idq_JS_qr_generator_control.js 
idq_php_connector_stylesheet.css 
idQCheckScan.php 
idQConfirmUser.php 
idQOauthClientInfo.php 
idQPHP.php 
idqQRGeneratorPHP.php 
idQRedirectUrl.php 
idQServerPath.inc 
idqServerPath.php 
img/ 
  idq_blue.png 
  idq_grey.png 
qr_library/ 
  qrcode.js 

2.2 Step Two 
Open the file "idQOauthClientInfo.php". There are four fields that you can configure to use that. Please replace the placeholder values: 
$RedirectURL - > Need be filled by the URL address of your site follow by the path to reach "/php_idq_connector/idQRedirectUrl.php". 
 $ApplicationID - > Need be filled with the Application ID related to your web site or web application, if you don't have the Application ID please read the extra content of this file. $ApplicationSecret - > Need be filled with Application Secret related to your web site or web application, if you don't have the Application Secret please read the extra content of this file. 
2.3 Step Three 
For this step you have two options: Use the OAuth 2.0 protocol (Recommended) or Embedding the QR Code in your site. 
Important Note: The connector is an API to make the authentication interface between your project and idQ Trusted Services. The authorization of authenticated user should be decide by your application. Which means 
what resource will be available for the user is a web app or site duty. 

2.3.1 Use the OAuth 2.0 protocol 
To use Oauth 2.0 protocol, you need add an anchor link in your login page (or the page that protect the restricted resource). Insert into your code: 
<?PHP 
include 'php_idq_connector/idQServerPath.php'; include 'php_idq_connector/idQOauthClientInfo.php'; $url_oauth_idq=call_api_inbay($RedirectURL,$ApplicationID); ?> 
This should be your "HREF" attribute in your link. We highly recommend use our "Login With idQ" button-images, available in the package "php_idq_connector/img", Grey and Blue. 
href='<?PHP echo $url_oauth_idq; ?>' For instance: <a href='<?PHP echo $url_oauth_idq; ?>'><div class="btn-login-with-idq idq-button-inbay-blue"></div></a> 

2.3.2 Embedding the QR Code in your site. 
Have in mind that is not a "pure" OAuth 2.0 solution, since OAuth 2.0 protocol expect the user (Resource Owner) be redirect to the page of Identity Provider (idQ Trusted Services). 
You should add this code in the page that you use as login to protect the restricted resources. 
	1.	Add JQuery Library to the page where you want display the QR. Since your site can already have a version o JQuery running, we not included in the package this library.  
	2.	Link (after the Jquery tag-link) the follow javascript files in your page: php_idq_connector/qr_library/qrcode.js, php_idq_connect or/idq_JS_qr_generator_control.js. For instance:  a. <script src="common-files/js/jquery-1.10.2.min.js"> </script> b. <script src="php_idq_connector/qr_library/qrcode.js"> </script> c. <script src="php_idq_connector/idq_JS_qr_generator_control.js"> </script>  
	3.	Insert the follow HTML piece in the container that you want the display of the QR: a. <a class="clickable"><div id="idq_reload_qr_anchor" style="cursor: pointer;">Click Here To Reload</div><div id="qrco  de"></div></a>  
3. Samples 
The below files are samples, after your finish step 1 and 2 you will be ready to use them to test. 
idq_login_button.php 
iqr_php_generation.html 

4. Testing 
Now you are ready to test. 
If You Are Using OAuth 2.0...	Expect
User Scan the QR in the OAuth Server	User be redirect to you site again
If You Are Using OAuth 2.0...	Expect
User Scan the QR in the OAuth Server	User be redirect to you site again

If You Are Using Embedded QR...	Expect
User can open your site	The QR is displayed to user scan.
User scan the QR Code	The user is authenticated (the plugin is not responsible by authorization)

5. Extra Content 
idQ Trusted Service allow any end user register a web application, the result is a Application ID and Application Secret. 
Please, notice currently the service is open to use. However, this documentation can not be use as parameter about prices or any value charged. 
To register you application you can follow theses listed step. If you just want see the Application ID and Secret of an already registered web app or site go to step 6. 
1. Login into myidq.inbaytech.com. 2. Click in the menu in the right-top of the screen. 
 

3. Choose the option "Register a Web Application"; 
4. Fill the displayed form and click submit. 

5. Now you'll see a congratulations page and the Application ID and Application Secret in the middle of screen. The secret is covered, but you can see it clicking in the "Reveal Secret" check box. 
6. You can check the Application ID and Application Secret any time. To do it: 
Click Menu; Choose "My Registered Apps"; Click over the row that contains your application. And see this information in the top of the form, that will be displayed. 
The Arrow shows where click to reveal App Secret. 
 



