<?php 
//---------------------------------------
	define('TITLE1_HEADER'			, 'Campus Management System');
	define("SITE1_NAME"				, "Login - Campus Management System");
	define("COMPANY1_NAME"			, "Campus Management System");
	define("SITE1_ADDRESS"			, "");
	define("COPY1_RIGHTS"			, "Green Professional Technologies");
	define("COPY1_RIGHTS_ORG"		, "&copy; ".date("Y")." - Green Professional Technologies.");
	define("COPY1_RIGHTS_URL"		, "http://greenprofessionals.net/");
//---------------------------------------
include_once "include/functions/passreset_func.php";
//---------------------------------------
if(isset($_SESSION['userlogininfo']['LOGINIDA'])) {
	header("Location: dashboard.php");	
} else { 
//---------------------------------------
$reset_email = (isset($_POST['reset_email']) && $_POST['reset_email'] != '') ? $_POST['reset_email'] : '';	
	$errorMessage = '';
	if (isset($_POST['reset_email'])) {
		$result = cpanelLMSApassrest();
		if ($result != '') {
			$errorMessage = $result;
		}
	}
//---------------------------------------	
echo '
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width,initial-scale=1" name="viewport">
<meta name="keywords" content="">
<meta name="description" content="'.TITLE1_HEADER.'">
<meta name="author" content="'.COPY1_RIGHTS.'">
<title>Forget Password Panel - '.TITLE1_HEADER.'</title>
<link rel="shortcut icon" href="login/assets/images/favicon.png">
<!-- Web Fonts  -->
<link href="https://fonts.googleapis.com/css?family=Signika:300,400,600,700" rel="stylesheet"> 
<link rel="stylesheet" href="login/assets/vendor/bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="login/assets/vendor/font-awesome/css/fontawesome-all.min.css">
<link rel="stylesheet" href="login/assets/vendor/simple-line-icons/css/simple-line-icons.css">
<script src="login/assets/vendor/jquery/jquery.js"></script>
<!-- sweetalert js/css -->
<link rel="stylesheet" href="login/assets/vendor/sweetalert/sweetalert-custom.css">
<script src="login/assets/vendor/sweetalert/sweetalert.min.js"></script>
<!-- login page style css -->
<link rel="stylesheet" href="login/assets/login_page/css/style.css">
</head>
<body>

<div class="auth-main">
<div class="container">
<div class="slideIn">
<!-- image and information -->
<div class="col-lg-4 col-lg-offset-1 col-md-4 col-md-offset-1 col-sm-12 col-xs-12 no-padding" style="z-index:1; text-align: center;">
	<div class="image-area">
		<div class="content">
			<div class="image-hader">
				<h2>Welcome To</h2>
			</div>
			<div class="center" style="padding-bottom: 22px;">
				<img src="login/assets/images/app_image/logo.png" height="60" alt="'.TITLE1_HEADER.'">
			</div>
			<div class="address">
				<p></p>
			</div>
			<div class="f-social-links center">
				<a href="https://web.facebook.com/lhischools/" target="_blank"><span class="fab fa-facebook-f"></span></a>
				<a href="https://www.twitter.com/" target="_blank"><span class="fab fa-twitter"></span></a>
				<a href="https://www.linkedin.com/" target="_blank"><span class="fab fa-linkedin-in"></span></a>
				<a href="https://laurelhomeschools.edu.pk/" target="_blank"><span class="fas fa-globe"></span></a>
			</div>
		</div>
	</div>
</div>

<!-- Login -->
<div class="col-lg-6 col-lg-offset-right-1 col-md-6 col-md-offset-right-1 col-sm-12 col-xs-12 no-padding">
<div class="sign-area">

<div class="sign-hader">
	<img src="login/assets/images/app_image/logo.png" height="54" alt="'.TITLE1_HEADER.'">
	<h2>'.TITLE1_HEADER.'</h2>
</div>';
//---------------------------------------
if($errorMessage) {
	echo '<div style="font-weight:600; text-align:center;margin-bottom:10px;">
			<label style="color: yellow;">'.$errorMessage.'</label>
		 </div>';
}
//---------------------------------------
echo '
<form  enctype="multipart/form-data" method="post" accept-charset="utf-8" name="frmLogin" id="frmLogin">
<div class="form-group ">
	<div class="input-group input-group-icon">
		<span class="input-group-addon">
			<span class="icon"><i class="icons icon-user"></i></span>
		</span>
		<input type="email" class="form-control" name="reset_email" id="reset_email" value="'.$reset_email.'" required autocomplete="off" autofocus placeholder="Your Email"/>
	</div>
</div>

<div class="forgot-text">
	<div class="">
        <p>Forgotten your password? <br> Enter your email address to begin the reset process.</p>
	</div>
</div>

<div class="form-group">
	<button type="submit" id="btn_submit" class="btn btn-block btn-round"><i class="icons icon-refresh"></i> Reset</button>
</div>

<div class="sign-footer">
	<p><a href="https://gptech.pk/" target="_blank">'.COPY1_RIGHTS_ORG.'</a></p>
</div>

</form>
</div>
</div>

</div>
</div>
</div>

<script src="login/assets/vendor/bootstrap/js/bootstrap.js"></script>
<script src="login/assets/vendor/jquery-placeholder/jquery-placeholder.js"></script>
<!-- backstretch js -->
<script src="login/assets/login_page/js/jquery.backstretch.min.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$.backstretch([
			"login/assets/images/login_image/login-bg.jpg"
		],{duration: 3000, fade: 750});
	});
</script>

</body>
</html>';
}
?>