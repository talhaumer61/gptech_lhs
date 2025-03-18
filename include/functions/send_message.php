<?php

function sendMessage($phone, $message) { 

    $phoneNo = str_replace("-","",$phone);

    // Set Credentials, Cell and MSG in Data Objects
    $data['username'] = 'lhs';
    $data['password'] = '123456';

    $data['mask'] = 'LHS';
    
    $data['mobile'] = $phoneNo;
    $data['message'] = $message;
        
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://brandyourtext.com/sms/api/send",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $data,
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
}
?>