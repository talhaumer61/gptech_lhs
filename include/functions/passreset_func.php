<?php
// session_start();
//**********Admin Area Login checking ***********************/
function checkCpanelLMSALogin() {
    // if the session id is not set, redirect to login page
	if(!isset($_SESSION['userlogininfo']['LOGINIDA'])) {
		header("Location: login.php");
		exit;
	}
	// For admin logout
	if(isset($_GET['logout'])) {
		panelLMSALogout();
	}
}

//***************Function for admin login*********************
function cpanelLMSApassrest() {

	require_once ("include/dbsetting/lms_vars_config.php");
	require_once ("include/dbsetting/classdbconection.php");
	require_once ("include/functions/functions.php");
	$dblms = new dblms();
    //******* if we found an error save the error message in this variable**********
	$errorMessage = '';
	$resetEmail   = cleanvars($_POST['reset_email']);

    //*************** first, make sure the email not empty******
	if($resetEmail == '') {
		$errorMessage = 'You must enter Your Email';
	} else {
        // **************Check the admin name and password exist*****************
        $sqllmsAdmin	= $dblms->querylms("SELECT adm_id, adm_fullname FROM ".ADMINS."
                                                WHERE adm_username = '".$resetEmail."' OR adm_email = '".$resetEmail."'
                                                AND adm_status = '1' LIMIT 1");

        //************** if the admin name then **************** 	
        if(mysqli_num_rows($sqllmsAdmin) == 1) {
            $valAdm = mysqli_fetch_array($sqllmsAdmin); 
            // $salt = $row['adm_salt'];
            // $password = hash('sha256', $admin_pass3 . $salt);
            // for ($round = 0; $round < 65536; $round++) {
            // 	$password = hash('sha256', $password . $salt);
            // }
            // if($password == $row['adm_userpass']) {


            // $tokenid = md5(uniqid());
            // $startTime = date("Y-m-d H:i:s");
            // $expireTime = date('Y-m-d H:i:s',strtotime('+2 hour',strtotime($startTime)));

            $expFormat = mktime(
                date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y")
            );
            $startTime  = date("Y-m-d H:i:s");
            $expireTime = date('Y-m-d H:i:s',strtotime('+2 hour',strtotime($startTime)));
            $key = md5(uniqid());
            $addKey = substr(md5(uniqid(rand(),1)),3,10);
            $key = $key . $addKey;

            //******************* ADD INTO PASSRESET ***********************
            $sqllmsReset = $dblms->querylms("INSERT INTO ".PASS_RESET."(
                                                                reset_adm_id	, 
                                                                reset_key       ,
                                                                reset_expirey	,  
                                                                reset_date  	,
                                                                reset_ip				
                                                            )
                                                        VALUES( 
                                                                '".cleanvars($valAdm['adm_id'])."'  	, 
                                                                '".cleanvars($key)."'               	,
                                                                '".cleanvars($expireTime)."'           	,
                                                                NOW()                                   ,
                                                                '".$ip."'                        	    											
                                                            )"
                                    );
            //------------ EMAIL -------------
            require 'PHPMailer/PHPMailerAutoload.php';

            $mail = new PHPMailer;

            // $name = $_POST['name'];
            // $phone = $_POST['phone'];
            // $email = $_POST['email'];

            $name = 'Laurel Home International Schools';
            // $phone = '03**********';
            $email = 'cms@laurelhomeschools.edu.pk';
            // $file = $_FILES['attachment']['tmp_name'];

            // $message = $_POST['message'];
            $message ='<p>Dear, '.$valAdm['adm_fullname'].' <br> This Mail For Pass Resetting LHS Portal</p>
                        <a href="http://cms.laurelhomeschools.edu.pk/resetpassword.php?key='.$key.'&email='.$resetEmail.'" target="_blank" style="background: #33adff; color: white; border-radius: 5px; padding: 8px 15px;";>Reset Your Password</a>
                        <p>The link will expire after 2 hours due to security reason.<br>
                        If you did not request this forgotten password email, no action 
                        is needed, your password will not be reset.
                        <br>However, you may want to log into your account and change your security password as someone may have guessed it.</p>
                        <p>Thanks, <br> '.$name.' <br> '.$email.'</p>
                        <p>Powered By: <a href="https://gptech.pk/" target="_blank">Green Professional Technologies</p></p>';

            //$mail->SMTPDebug = 3;                               // Enable verbose debug output

            $mail->isMail();                                      // Set mailer to use SMTP
            $mail->Host = 'localhost';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'Laurel Home International Schools';                 // SMTP username
            $mail->Password = 'password';                           // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            $mail->From = 'cms@laurelhomeschools.edu.pk';
            $mail->FromName = $name;
            $mail->addAddress(''.$resetEmail.'', ''.$valAdm['adm_fullname'].''); // Name is optional

            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments

            $mail->isHTML(true);                                  // Set email format to HTML

            $mail->Subject = 'LHIS Portal Password Reset';
            $mail->Body = $message;
            // $mail->Body .= "<br /><br />Below are our contact details: <br /> ";
            // $mail->Body .= $name;
            // $mail->Body .= "<br />My P/hone number: ";
            // $mail->Body .= $phone;
            // $mail->Body .= "<br /> Email Address: ";
            // $mail->Body .= $email;


            $mail->AltBody = 'You are using basic web browser ';
            // if(is_array($_FILES)) {
            //     $mail->AddAttachment($_FILES['attachment']['tmp_name'],$_FILES['attachment']['name']); 
            // }

            if(!$mail->send()) {
                $errorMessage = '<span style="color: yellow;"><p>Email Not Sent. <br> Error: '.$mail->ErrorInfo.'</p></span>';

            } else {
                $errorMessage = '<span style="color: yellow;"><p>An email has been sent to you with instructions on how to reset your password.</p></span>';

            }


            //******************* MAKE LOG START ***********************
            $remarks = 'Requested For Pass Reset';
            $sqllmsLog  = $dblms->querylms("INSERT INTO ".PASS_RESET_LOG."(
                                                                log_adm_id          ,
                                                                log_dated			,  
                                                                log_ip  			,
                                                                remarks				
                                                            )
                                                        VALUES(
                                                                '".cleanvars($valAdm['adm_id'])."'  	, 
                                                                NOW()                                   ,
                                                                '".$ip."'                  			    ,
                                                                '".cleanvars($remarks)."'	    											
                                                            )"
                                    );

            // header("Location: login.php");
            // exit();
        } 
        else {
            //********** admin email dosn't much *******************
            $errorMessage = '<span style="color: yellow;"><p> Email Not Found.</p></span>';
        }	
    }	

    return $errorMessage;
}
?>