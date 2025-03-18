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
	require_once("include/dbsetting/lms_vars_config.php");
	require_once("include/dbsetting/classdbconection.php");
	require_once("include/functions/functions.php");
	$dblms = new dblms();
	// require_once("include/functions/login_func.php");
    // checkCpanelLMSALogin();
    
//---------------------------------------
if(isset($_SESSION['userlogininfo']['LOGINIDA'])) {
	header("Location: dashboard.php");	
} else { 
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
if(isset($_GET["key"]) && isset($_GET["email"])){

    $key = cleanvars($_GET['key']);
    $email = cleanvars($_GET['email']);
    $currDate = date("Y-m-d H:i:s");

    $sqllmsReset = $dblms->querylms("SELECT *
                                            FROM ".PASS_RESET." r 
                                            INNER JOIN ".ADMINS." a ON a.adm_id = r.reset_adm_id
                                            WHERE r.reset_id != '' AND r.reset_key = '".$key."'
                                            AND (a.adm_username = '".$email."' OR a.adm_email = '".$email."')
                                            ORDER BY r.reset_id DESC LIMIT 1");
    if(mysqli_num_rows($sqllmsReset) > 0){
        $valueReset = mysqli_fetch_array($sqllmsReset);
        if ($valueReset['reset_expirey'] >= $currDate){
            echo'<form  enctype="multipart/form-data" method="post" accept-charset="utf-8" name="frmLogin" id="frmLogin">
                    <div class="form-group ">
                        <div class="input-group input-group-icon">
                            <span class="input-group-addon">
                                <span class="icon"><i class="icons icon-lock"></i></span>
                            </span>
                            <input type="password" class="form-control" name="reset_pass" id="reset_pass" required autocomplete="off" autofocus placeholder="New Password"/>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" id="btn_submit" class="btn btn-block btn-round"><i class="icons icon-refresh"></i> Update</button>
                    </div>
                </form>';

                if(isset($sqllms)) {
                    echo"Reset Successfully.";
                }

        }
        else{
            echo'<div style="font-weight:600; text-align:center;margin-bottom:10px;">
                    <label style="color: yellow;">Link Expired</label>
                    <br>
                    <a href="forgetpassword.php">Back To Reset</a>
                </div>';
        }
    }
    else{
        echo'<div style="font-weight:600; text-align:center;margin-bottom:10px;">
                <label style="color: yellow;">Invalid Link</label>
                <br>
                <a href="forgetpassword.php">Back To Reset</a>
            </div>';

    }

}
else{
    echo'<div style="font-weight:600; text-align:center;margin-bottom:10px;">
            <label style="color: yellow;">Invalid Link</label>
            <br>
            <a href="forgetpassword.php">Back To Reset</a>
        </div>';

}
//------------- Password Update ----------------
if(isset($_POST['reset_pass'])) { 
    //------------hashing---------------
    $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
    $pass = $_POST['reset_pass'];
    $password = hash('sha256', $pass . $salt);
    for ($round = 0; $round < 65536; $round++) {
        $password = hash('sha256', $password . $salt);
    }
    //------------hashing---------------
    $sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET 
                                                adm_salt		=  '".cleanvars($salt)."' 
                                            ,  adm_userpass		= '".cleanvars($password)."' 
                                         WHERE adm_id			= '".$valueReset['adm_id']."'
                                                ");
    if($sqllms) { 
        //******************* MAKE LOG START ***********************
        $remarks = 'Reset Password From #AdminID:"'.cleanvars($valueReset['adm_id']).'"';
        $sqllmsLog  = $dblms->querylms("INSERT INTO ".PASS_RESET_LOG."(
                                                            log_adm_id          ,
                                                            log_dated			,  
                                                            log_ip  			,
                                                            remarks				
                                                        )
                                                    VALUES(
                                                            '".cleanvars($valueReset['adm_id'])."'  , 
                                                            NOW()                                   ,
                                                            '".$ip."'                  			    ,
                                                            '".cleanvars($remarks)."'	    											
                                                        )"
                                );
            // $_SESSION['msg']['title'] 	= 'Successfully';
            // $_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
            // $_SESSION['msg']['type'] 	= 'success';
            header("Location: login.php", true, 301);
            exit();
    }
}
//---------------------------------------

echo '
<div class="sign-footer">
	<p><a href="https://gptech.pk/" target="_blank">'.COPY1_RIGHTS_ORG.'</a></p>
</div>
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