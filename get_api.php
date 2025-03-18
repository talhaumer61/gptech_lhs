<?php 

	include("include/dbsetting/lms_vars_config.php");
	include("include/dbsetting/classdbconection.php");
	include("include/functions/functions.php");
    // global $dblms;
    $dblms = new dblms();

    $url = "http://cms.laurelhomeschools.edu.pk/";

 	//include("includes/function.php"); 	

 	//include("language/app_language.php");

	 //include("smtp_email.php");
	 
	//Json Decode Function
	function checkSignSalt($data_info){
		
        $data_json = $data_info;
		$data_arr = json_decode(urldecode(base64_decode($data_json)),true);
		
        return $data_arr;
    }

 	date_default_timezone_set("Asia/Karachi");
 	

	$protocol = strtolower( substr( $_SERVER[ 'SERVER_PROTOCOL' ], 0, 5 ) ) == 'https' ? 'https' : 'http'; 



	$file_path = $protocol.'://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/';

	 

	function get_thumb($filename,$thumb_size)

	{	

		$protocol = strtolower( substr( $_SERVER[ 'SERVER_PROTOCOL' ], 0, 5 ) ) == 'https' ? 'https' : 'http'; 



		$file_path = $protocol.'://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/';



		return $thumb_path=$file_path.'thumb.php?src='.$filename.'&size='.$thumb_size;

	}

    // $get_method = checkSignSalt($_POST['data']);
    $get_method = $_GET;
    
    function credential_check($user_name, $password)
    {
        global $dblms; 
        //******* if we found an error save the error message in this variable**********
        $errorMessage = '';
        $admin_user   = cleanvars($user_name);
        $admin_pass1  = cleanvars($password);
        $admin_pass3  = ($admin_pass1);

        // **************Check the admin name and password exist*****************
            $sqllms	= $dblms->querylms("SELECT * FROM ".ADMINS."
                                                WHERE adm_username = '".$admin_user."' 
                                                AND adm_status = '1' AND adm_logintype IN (3,4,5) LIMIT 1");
        //************** if the admin name and password exist then **************** 	
        if (mysqli_num_rows($sqllms) == 1) {
            $row = mysqli_fetch_array($sqllms); 
            $salt = $row['adm_salt'];

            $password = hash('sha256', $admin_pass3 . $salt);
            
            for ($round = 0; $round < 65536; $round++) 
            {
                $password = hash('sha256', $password . $salt);
            }

            if($password == $row['adm_userpass']) {

                if($row['adm_logintype'] == 3)
                {

                    $sqllms_setting	= $dblms->querylms("SELECT acd_session
                                                                FROM ".SETTINGS."
                                                               WHERE status ='1' AND is_deleted != '1'");
                    
                    $values_setting = mysqli_fetch_array($sqllms_setting);

                    $sqllms_emply	= $dblms->querylms("SELECT emply_id, id_class, id_section, id_campus
                                                        FROM ".EMPLOYEES."
                                                        WHERE id_loginid = '".$row['adm_id']."' LIMIT 1");

                    $values_emply = mysqli_fetch_array($sqllms_emply);
                    
                    $details = array(
                                        "login_for"  => $row['adm_logintype']         ,
                                        "adm_id"     => $row['adm_id']                ,
                                        "emply_id"   => $values_emply['emply_id']     ,
                                        "id_class"   => $values_emply['id_class']     ,
                                        "id_section" => $values_emply['id_section']   ,
                                        "id_session" => $values_setting['acd_session'] ,
                                        "id_campus"  => $values_emply['id_campus']
                                        );

                    return $details;
                    
                }
                else if($row['adm_logintype'] == 4)
                {

                }
                else if($row['adm_logintype'] == 5)
                {
                    $sqllms_std	= $dblms->querylms("SELECT std_id, id_class, id_section, id_session, id_campus
                                                        FROM ".STUDENTS."
                                                        WHERE id_loginid = '".$row['adm_id']."' LIMIT 1");

                    $values_std = mysqli_fetch_array($sqllms_std);
                    
                    $details = array(
                                        "login_for"  => $row['adm_logintype']       ,
                                        "adm_id"     => $row['adm_id']              ,
                                        "std_id"     => $values_std['std_id']       ,
                                        "id_class"   => $values_std['id_class']     ,
                                        "id_section" => $values_std['id_section']   ,
                                        "id_session" => $values_std['id_session']   ,
                                        "id_campus"  => $values_std['id_campus']
                                        );

                    return $details;

                }
                else {
                    
                    $data['success'] 	= 'Sorry! App not For Administration..';
                    return $data;
                }
            }
            else {
                
                $data['success'] 	= '-1';
                return $data;
    
            }
        
        } 
        else {
            
            $data['success'] 	= '-1';
            return $data;

        }	
    }
    

    if($get_method['method_name'] == "about") 
    {
        $jsonObj = array();	
        
        //---------------------------------------------
        $about_details  = "Minhaj University Lahore was founded in 1986 by Shaykh-ul-Islam Prof. Dr Muhammad Tahir-ul-Qadri, patron-in-chief of Minhaj ul Quran International. It is located in a significant place which is easily approachable from all the main areas of the city. Its campuses are situated at Township & Model Town. Degree awarding status was granted by Govt. of the Punjab vide Act No: XII of 2005. The Higher Education Commission of Pakistan has also recognized Minhaj University, as ‘W3‘ranking University.
        It comprises of nine faculties that of Computer Science & Information Technology, Basic Sciences & Mathematics, Economics & Management Sciences, Social Sciences & Humanities, Languages, Shariah & Islamic Studies, Engineering, Allied Health Sciences, Applied Sciences. Faculties have numerous Schools and Teaching Departments. University also has two centres including the International Centre of Research in Islamic Economic (ICRIE) and International Center of Excellence (ICE). It has a purpose-built campus with allied administrative and educational facilities like science laboratories, Computer Lab, Research Lab,  libraries, hostels, cafeteria, mosque, parking areas and playground. Programs at BS / MA / M.Sc / MS / M.Phil and P.hD level are offered in all the faculties.
        In nutshell, Minhaj University generates in students concept of building up hallmark of career formation by both academic and empirical wisdom, vision and observation to understand what they are going to be with the concept of maturity with purity is a surety of success and thus paving the way to glorious success.";
        $mission        = "Our vision is to make our students work-ready, life-ready and world-ready, so that they may lead the future generations and brighten the name of Pakistan as well as Islam.";
        $vision         = "Our mission is to raise social awareness, foster ethical values and promote competition in the field of education, so that the nation in general and students in particular may become responsible citizens and progressive Muslims.";
        $website        = "http://laurelhomeschools.edu.pk";
        $contact_no     = "03440177155";
        $email          = "info@lhis.edu.pk";
        $powered_by     = "Green Professional Technologies";
        $copy_right     = "Green Professional Technologies";
        //---------------------------------------------

        $data['about'] 	        = $about_details;
        $data['mission'] 	    = $mission;
        $data['vision'] 	    = $vision;
        $data['website'] 	    = $website;
        $data['contact_no'] 	= $contact_no;
        $data['email'] 	        = $email;
        $data['powered_by'] 	= $powered_by;
        $data['copyright'] 	    = $copy_right;

        array_push($jsonObj,$data);	
       
    
        $set['LHIS'] = $jsonObj;
    
        header( 'Content-Type: application/json; charset=utf-8' );
    
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    
        die();

    }

    else if($get_method['method_name'] == "chairman_message") 
    {
        $jsonObj = array();	
        
        //---------------------------------------------
        $title        = "Chairman's Message";
        $name         = "DR. HUSSAIN MOHI-UD-DIN QADRI";
        $designation  = "Chairman";
        $photo        = $url."about/chairman.jpg";
        $detail       = "In this world, human’ real responsibility is to serve their fellow beings and the best way to do so is to educate them. In human history, nations achieved progress and made their mark on the world by their endless quest for knowledge.
        By dint of knowledge, individuals and nations stand out, and it is the most powerful but peaceful weapon which we can use to transform the world. The platform of the Laurel Home international Schools (LHIS) is making the children of our country faithful Muslims, responsible Pakistani, and civilized individuals so that they might become the pride of this nation.
        Laurel Home International Schools are playing their crucial role in building a productive society. We believe in the vision of development, peace, innovation, objectivity, altruism and service. We take pride in doing our best for preparing our nation equipped with these desirable attributes. So join hands with us and let us become travel companions too.";
        //---------------------------------------------

        $data['title'] 	        = $title;
        $data['name'] 	        = $name;
        $data['designation'] 	= $designation;
        $data['photo'] 	        = $photo;
        $data['detail'] 	    = $detail;

        $jsonObj = $data;	
       
    
        $set['LHIS'] = $jsonObj;
    
        header( 'Content-Type: application/json; charset=utf-8' );
    
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    
        die();

    }

    else if($get_method['method_name'] == "md_message") 
    {
        $jsonObj = array();	
        
        //---------------------------------------------
        $title        = "Managing Director’s Message";
        $name         = "DR. SAJID MAHMOOD SHAHZAD";
        $designation  = "Managing Director";
        $photo        = $url."about/md.jpg";
        $detail       = "Laurel Home International Schools are a pragmatic enterprising and a forward-looking newly established school system in Pakistan. I am confident that this school is the best place for young children because we are passionate to inculcate humanitarian values in them along with imparting knowledge. We stand committed to reaching and polishing the talented knowledge-seeking children for rewarding careers. LHIS aim to cater for the learning and professional needs of diverse students belonging to divergent backgrounds of the country. Thoroughly devoted to motivation, supervision and enhancement of the intellectual and creative abilities of learners, LHIS offer a vast range of holistic education for their students. We stand pledged to produce, among other, forward-thinking highly-competent students for the nation.
        Keeping in view the emerging global trends, we aim to train our youth to meet the challenges of the contemporary world. Therefore, we have introduced the most inspiring syllabus of Oxford University Press along with Islamic education. We ensure that our students use their creative abilities and realize their full potential. We believe success depends both on academic excellence and strength of character. Hence, we endeavor to mobilize and stimulate our students to build their character according to the progressive and dynamic principles of Islam. In addition to the routine academic activates, we have in place a system of thought-provoking lectures including periodic teacher-training workshops. Thus we ensure to impart knowledge, skills, and values of critical thinking along with creativity to our students; we are providing an efficient and motivated, technological –advanced human resource to the nation. In the end, I wholeheartedly welcome your active participation, involvement and interest in the development of your child.";
        //---------------------------------------------

        $data['title'] 	        = $title;
        $data['name'] 	        = $name;
        $data['designation'] 	= $designation;
        $data['photo'] 	        = $photo;
        $data['detail'] 	    = $detail;

        $jsonObj = $data;	
       
    
        $set['LHIS'] = $jsonObj;
    
        header( 'Content-Type: application/json; charset=utf-8' );
    
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    
        die();

    }

    else if($get_method['method_name'] == "director_message") 
    {
        $jsonObj = array();	
        
        //---------------------------------------------
        $title        = "Director’s Message";
        $name         = "ALI WAQAR QADRI";
        $designation  = "Director";
        $photo        = $url."about/director.jpg";
        $detail       = "LHIS have set up a network of quality schools all over the Pakistan. They offer a business format and professional expertise in education to help business partners to venture confidently into this fast-expanding service of the nation.
        At the present time, LHIS have over one hundred franchises in the country, but they are also looking to expand their network. We are confident of providing the would-be franchisees with a good head start and an excellent chance of success.
        We enjoy wide popularity as we follow the syllabus of Cambridge / Oxford University Press and Laurel Publishers. LHIS have an affordable fee structure, promote activity-based learning and nurture efficient learners. Joint this brand.";
        //---------------------------------------------

        $data['title'] 	        = $title;
        $data['name'] 	        = $name;
        $data['designation'] 	= $designation;
        $data['photo'] 	        = $photo;
        $data['detail'] 	    = $detail;

        $jsonObj = $data;	
       
    
        $set['LHIS'] = $jsonObj;
    
        header( 'Content-Type: application/json; charset=utf-8' );
    
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    
        die();

    }

    elseif($get_method['method_name'] == "contacts")
    {	
    
        $jsonObj= array();
        //------------------------------------------
        $contacts_json = file_get_contents("data/contacts.json");
    
        $contacts = json_decode($contacts_json,true);
        //------------------------------------------
        foreach($contacts as $contact)
    
        {    
            $data['name'] 		= $contact['name'];
            $data['desg'] 		= $contact['desig'];
            $data['phone'] 		= $contact['phone'];
            $data['email'] 	    = $contact['email'];

            array_push($jsonObj,$data);
        
        }

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
    
    }

    else if($get_method['method_name'] == "campus") 
    {
        $jsonObj = array();

        //-----------------------------------------------------
        
        $sqllms_campus	= $dblms->querylms("SELECT campus_name, campus_address, campus_phone, campus_logo
                                                   FROM ".CAMPUS." 
                                                   WHERE campus_status = '1' 
                                                   ORDER BY campus_name ASC");

        //-----------------------------------------------------

        while($values_campus = mysqli_fetch_array($sqllms_campus))
        {
            if($values_campus['campus_logo']) { 
                $photo = $url."uploads/images/campus/".$values_campus['campus_logo'];
            } else {
                $photo = $url."uploads/logo.png";
            }

            $data['name']       = $values_campus['campus_name'];
            $data['photo']      = $photo;
            $data['address']    = $values_campus['campus_address'];
            $data['phone']      = str_replace("-", "", $values_campus['campus_phone']);
            $data['lat']        = "31.448487418211773";
            $data['long']       = "74.31551858016167";

            array_push($jsonObj,$data);
        }

        //----------------------------------------------------- 

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
    }

    else if($get_method['method_name'] == "admission_inquiry")
    {
        

        //----------------- INSET RECORD ---------------------

        $jsonObj = array();

        if(isset($get_method['name']) && isset($get_method['mobile']) && isset($get_method['class']) && isset($get_method['campus']))
        {

            $sqllmsInsert  = $dblms->querylms("INSERT INTO ".ADMISSIONS_INQUIRY."(
                                                                        status								, 
                                                                        name								,
                                                                        cell_no								,
                                                                        source								,
                                                                        note								,
                                                                        gender  							,
                                                                        id_campus							,
                                                                        date_added 	
                                                                    )
                                                                VALUES(
                                                                        '1'											,   
                                                                        '".cleanvars($get_method['name'])."'		,
                                                                        '".cleanvars($get_method['mobile'])."'		,
                                                                        '1'		                                    ,
                                                                        '".cleanvars($get_method['note'])."'		,
                                                                        '".cleanvars($get_method['class'])."'		,
                                                                        '".cleanvars($get_method['campus'])."'		,
                                                                        NOW()
                                                                    )"
                                            );
            
                                  
            if($sqllmsInsert) { 

                $data['success'] 	= '1';
                $data['message'] 	= 'Added successfully..';

            } else{

                $data['success'] 	= '0';
                $data['message'] 	= 'Not Added..';

            }

            $jsonObj = $data;

        }
        else
        {
            
            //-------------------- GET CAMPUSES -----------------------

            $sqllms_campus	= $dblms->querylms("SELECT campus_id, campus_name
                                                    FROM ".CAMPUS." 
                                                    WHERE campus_status = '1' 
                                                    ORDER BY campus_name ASC");

            while($values_campus = mysqli_fetch_array($sqllms_campus))
            {
                $data['id']         = $values_campus['campus_id'];
                $data['name']       = $values_campus['campus_name'];
                
                array_push($jsonObj,$data);

            }

                // $data['success'] 	= '0';

        }

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
    }

    elseif($get_method['method_name'] == "news_events")
    {	
    
        $jsonObj= array();
        //------------------------------------------
        $events_json = file_get_contents("data/news_events.json");
    
        $events = json_decode($events_json,true);
        //------------------------------------------
        foreach($events as $event)
    
        {
            
            $date = date('d, M Y', strtotime(cleanvars($event['start_date'])));
    
            $data['img'] 		= $url."news/".$event['img'];
            $data['date'] 		= $date;
            $data['title'] 		= $event['title'];
            $data['detail'] 	= $event['detail'];

            array_push($jsonObj,$data);
        
        }

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
    
    }

    else if($get_method['method_name'] == "academic_calender") 
    {
        $jsonObj = array();
        //-----------------------------------------------------
        
        $sqllmsacademic	= $dblms->querylms("SELECT a.id, s.session_name
                                        FROM ".A_CALENAR." a 
                                        INNER JOIN ".SESSIONS." s ON s.session_id = a.id_session
                                        WHERE a.status = '1' AND a.published = '1'
                                        ORDER BY a.id DESC");
        $value_academic = mysqli_fetch_array($sqllmsacademic);
                        
        //-----------------------------------------------------

        $sqllms	= $dblms->querylms("SELECT d.date_start, d.date_end, d.remarks, p.cat_name
								   FROM ".ACADEMIC_DETAIL." d
								   INNER JOIN ".ACADEMIC_PARTICULARS." p ON p.cat_id = d.id_cat 
								   WHERE d.id_setup = '".$value_academic['id']."'
                                   ORDER BY p.cat_ordering ASC");
        //-----------------------------------------------------

        while($rowsvalues = mysqli_fetch_array($sqllms))
        {
            
            $start_date = date('d, M', strtotime(cleanvars($rowsvalues['date_start'])));
            $start_date_year = date('Y', strtotime(cleanvars($rowsvalues['date_start'])));
            $end_date = date('d, M Y', strtotime(cleanvars($rowsvalues['date_end'])));

            $data['title']           = $rowsvalues['cat_name'];
            $data['start_date']      = $start_date;
            $data['start_date_year'] = $start_date_year ;
            $data['end_date']        = $end_date;

            array_push($jsonObj,$data);
        }

        //----------------------------------------------------- 

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
    }

    else if($get_method['method_name'] == "notifications") 
    {
        $jsonObj = array();
        
        $response = credential_check($get_method['username'], $get_method['password']);

        if($response['login_for'] == 3)
        {
                        
            $sql_not	= $dblms->querylms("SELECT not_title, dated, not_description
                                                    FROM ".NOTIFICATIONS." 
                                                    WHERE not_status = '1' AND is_deleted != '1' AND to_staff = '1'
                                                    AND (id_campus = '".$response['id_campus']."' OR id_campus = '0') 
                                                    ORDER BY not_id DESC");
            //--------------------------------------------------
            While($values_not = mysqli_fetch_array($sql_not))
            {
                
                $date = date('d, M Y', strtotime(cleanvars($values_not['dated'])));

                $data['title']   = $values_not['not_title'];
                $data['date']    = $date;
                $data['detail']  = $values_not['not_description'];

                array_push($jsonObj,$data);

            }

        }
        else if($response['login_for'] == 5)
        {
                        
            $sql_not	= $dblms->querylms("SELECT not_title, dated, not_description
                                                    FROM ".NOTIFICATIONS." 
                                                    WHERE not_status = '1' AND is_deleted != '1' AND to_student = '1'
                                                    AND (id_campus = '".$response['id_campus']."' OR id_campus = '0') 
                                                    ORDER BY not_id DESC");
            //--------------------------------------------------
            While($values_not = mysqli_fetch_array($sql_not))
            {
                
                $date = date('d, M Y', strtotime(cleanvars($values_not['dated'])));

                $data['title']   = $values_not['not_title'];
                $data['date']    = $date;
                $data['detail']  = $values_not['not_description'];

                array_push($jsonObj,$data);

            }
            
        }
        else
        {	
            $jsonObj = $response;
        }

        //----------------------------------------------------- 

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
    }

    else if($get_method['method_name'] == "account_book")
    {
        $jsonObj= array();

        $response = credential_check($get_method['username'], $get_method['password']);
        

        if(isset($response['std_id']))
        {
            //-----------------------------------------------------
            $sqllms	= $dblms->querylms("SELECT f.id, f.status, f.challan_no, f.issue_date, f.due_date, f.total_amount, s.std_id, s.std_name, ss.session_name, c.class_name, se.section_name
                                            FROM ".FEES." f						 
                                            INNER JOIN ".STUDENTS."       s ON s.std_id 	        = f.id_std
                                            INNER JOIN ".SESSIONS."  	 ss	ON 	ss.session_id 	    = f.id_session
                                            INNER JOIN ".CLASSES."        c ON 	c.class_id          = s.id_class
                                            INNER JOIN ".CLASS_SECTIONS." se	ON 	se.section_id 	= s.id_section
                                            WHERE f.id_std = '".$response['std_id']."'
                                            ORDER BY f.id DESC");
            //-----------------------------------------------------
            while($rowsvalues = mysqli_fetch_array($sqllms)) 
            {

                //---------------------------Scholarship--------------------------
                // $sql_scholarship	= $dblms->querylms("SELECT SUM(percent) as scholarship
                //                                 FROM ".SCHOLARSHIP." 
                //                                 WHERE id_type = '1' AND status = '1' AND id_std = '".$rowsvalues['std_id']."' ");
                // //-----------------------------------------------------
                // $values_scholarship = mysqli_fetch_array($sql_scholarship);
                // //----------------------------Fee Concession-------------------------
                // $sql_concess	= $dblms->querylms("SELECT SUM(percent) as concession
                //                                 FROM ".SCHOLARSHIP." 
                //                                 WHERE id_type = '2' AND status = '1' AND id_std = '".$rowsvalues['std_id']."' ");
                // //-----------------------------------------------------
                // $values_concess = mysqli_fetch_array($sql_concess);
                // //----------------------------Fine-------------------------
                // $sql_fine	= $dblms->querylms("SELECT SUM(amount) as fine
                //                                 FROM ".SCHOLARSHIP." 
                //                                 WHERE id_type = '3' AND status = '1' AND id_std = '".$rowsvalues['std_id']."' ");
                // //-----------------------------------------------------
                // $values_fine = mysqli_fetch_array($sql_fine);
                //-----------------------------------------------------
                //-----------payabel amount after Scholarship & Fine----------
                $amount = $rowsvalues['total_amount'];
                // $dis_per = $values_scholarship['scholarship'] + $values_concess['concession'];
                // $dis = ($amount * $dis_per) / 100;
                // $total_amount = $amount - $dis;
                // $payable = $total_amount + $values_fine['fine'];
                //-----------------------------------------------------
                
                $issue_date = date('d, M Y', strtotime(cleanvars($rowsvalues['issue_date'])));

                $due_date = date('d, M Y', strtotime(cleanvars($rowsvalues['due_date'])));

                $data['challan_no'] = $rowsvalues['challan_no'];
                $data['session']    = $rowsvalues['session_name'];
                $data['class']      = $rowsvalues['class_name'];
                $data['section']    = $rowsvalues['section_name'];
                $data['issue_date'] = $issue_date;
                $data['due_date']   = $due_date;
                $data['status']     = $rowsvalues['status'];
                $data['status']     = get_payments1($rowsvalues['status']);
                $data['payable']    = (string)$amount;
                //----------------------------------------------------- 

                array_push($jsonObj, $data);
            }
        }
        else
        {	
            $jsonObj = $response;
        }

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();

    }

    else if($get_method['method_name'] == "timetable")
    {

        $response = credential_check($get_method['username'], $get_method['password']);

        $jsonObj= array();

        if($response['login_for'] == 3 || $response['login_for'] == 5)
        {

            for($i=1; $i<=7; $i++) {
            
                $day =  get_daytypes($i); 

                if($response['login_for'] == 3) {

                    $sqllms_timetable	= $dblms->querylms("SELECT t.id, d.day, p.period_name, p.period_timestart, p.period_timeend, c.class_name, se.section_name, s.subject_name, r.room_no
                                                            FROM ".TIMETABLE." t
                                                            INNER JOIN ".TIMETABEL_DETAIL."  d 	ON 	d.id_setup      = t.id
                                                            INNER JOIN ".PERIODS."           p 	ON 	p.period_id     = d.id_period
                                                            INNER JOIN ".CLASSES."  	 	 c 	ON 	c.class_id 		= t.id_class
                                                            INNER JOIN ".CLASS_SECTIONS." se	ON 	se.section_id 	= t.id_section
                                                            INNER JOIN ".CLASS_SUBJECTS."    s 	ON 	s.subject_id    = d.id_subject
                                                            INNER JOIN ".CLASS_ROOMS."       r 	ON 	r.room_id 		= d.id_room
                                                            WHERE d.id_teacher = '".$response['emply_id']."'
                                                            AND t.id_campus = '".$response['id_campus']."' 
                                                            AND t.status = '1'
                                                            AND d.day = '".$i."'
                                                            ORDER BY p.period_id ASC
                                                        "); 
                } elseif($response['login_for'] == 5) {

                    $sqllms_timetable	= $dblms->querylms("SELECT t.id, d.day, p.period_name, p.period_timestart, p.period_timeend, s.subject_name, e.emply_name, e.emply_photo, r.room_no
                                                            FROM ".TIMETABLE." t
                                                            INNER JOIN ".TIMETABEL_DETAIL."  d 	ON 	d.id_setup      = t.id
                                                            INNER JOIN ".PERIODS."           p 	ON 	p.period_id     = d.id_period
                                                            INNER JOIN ".CLASS_SUBJECTS."    s 	ON 	s.subject_id    = d.id_subject
                                                            INNER JOIN ".EMPLOYEES." 	     e 	ON 	e.emply_id 		= d.id_teacher
                                                            INNER JOIN ".CLASS_ROOMS."       r 	ON 	r.room_id 		= d.id_room
                                                            WHERE t.id_class = '".$response['id_class']."'
                                                            AND t.id_section = '".$response['id_section']."' 
                                                            AND t.id_session = '".$response['id_session']."'
                                                            AND t.id_campus = '".$response['id_campus']."' 
                                                            AND t.status = '1'
                                                            AND d.day = '".$i."'
                                                            ORDER BY p.period_id ASC
                                                        ");

                }
                                            
                if (mysqli_num_rows($sqllms_timetable) > 0) 
                {
                    $jsonObj0 = array();

                    while($values_timetable = mysqli_fetch_array($sqllms_timetable))
                    {

                        $data1['period']     = $values_timetable['period_name'];
                        $data1['start_time'] = substr($values_timetable['period_timestart'], 0, -3);
                        $data1['end_time'] 	 = substr($values_timetable['period_timeend'], 0, -3);

                        if($response['login_for'] == 3) {

                        $data1['class'] 	 = $values_timetable['class_name'];
                        $data1['section'] 	 = $values_timetable['section_name'];

                        } elseif($response['login_for'] == 5) {

                            if($values_timetable['emply_photo']) { 
                                $photo = $url."uploads/images/employees/".$values_timetable['emply_photo'];
                            } else {
                                $photo = $url."uploads/admin_image/default.jpg";
                            }

                        $data1['teacher'] 	        = $values_timetable['emply_name'];
                        $data1['teacher_photo']     = $photo;

                        }

                        $data1['subject'] 	 = htmlspecialchars_decode($values_timetable['subject_name']);
                        $data1['room'] 	     = $values_timetable['room_no'];

                        array_push($jsonObj0,$data1);
                    }

                    $data['day'] = $day;
                    $data['data'] = $jsonObj0;

                    array_push($jsonObj,$data);
                    
                }
                else
                {
                    $data['success'] 	= '0';
                    $data['Message'] 	= 'No Record Found..';
                }
            }

        } else {	

            $jsonObj = $response;

        }

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
        
    }

    else if($get_method['method_name'] == "complaint_suggestion") 
    {

        $response = credential_check($get_method['username'], $get_method['password']);
        
        $jsonObj= array();
        
        if(isset($response['adm_id']))
        {
            //-----------------------------------------------------
            $sqllms_comp	= $dblms->querylms("SELECT id, status, id_type, dated, assign_to, title, detail, remarks
                                                    FROM ".COMPLAINTS." 	 									   
                                                    WHERE is_deleted != '1' AND id_complaint_by = '".$response['adm_id']."'");
            //-----------------------------------------------------
            while($rowsvalues = mysqli_fetch_array($sqllms_comp))
            {
                
                //-----------------------------------------------------
                if($rowsvalues['id_type'] == '1')
                {
                    $type = "Complaint";
                }
                else if($rowsvalues['id_type'] == '2')
                {
                    $type = "Suggestion";
                }
                //-----------------------------------------------------
                if($rowsvalues['assign_to'] == 1)
                {
                    $assign_to = "Head Office";
                }
                else if($rowsvalues['assign_to'] == 2)
                {
                    $assign_to = "Campus";
                }
                //-----------------------------------------------------
                
                $date = date('d, M Y', strtotime(cleanvars($rowsvalues['dated'])));

                $data['title']      	= $rowsvalues['title'];
                $data['date'] 	        = $date;
                $data['type'] 	        = $type;
                $data['assign_to'] 	    = $assign_to;
                $data['status']         = get_complaint1($rowsvalues['status']);
                $data['remarks'] 	    = $rowsvalues['remarks'];
                
                array_push($jsonObj,$data);
            }
        }
        else
        {	
            $jsonObj = $response;
        }        


        //----------------------------------------------------- 

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
        
    }

    else if($get_method['method_name'] == "add_complaint_suggestion") 
    {

        $response = credential_check($get_method['username'], $get_method['password']);
        
        $jsonObj= array();
        
        if($response['adm_id']){

            
            //----------------- TODAY'S DATE --------------------
            $dated = date('Y-m-d');
            //---------------------------------------------------            
            if($response['std_id']){$complainBy = $response['std_id'];} elseif($response['emply_id']){$complainBy = $response['emply_id'];}
            //---------------------------------------------------      
            
            $sqllmsCheck  = $dblms->querylms("SELECT id, status, id_type, dated, assign_to, title, detail, remarks
                                                FROM ".COMPLAINTS." 	 									   
                                                WHERE is_deleted != '1' AND dated = '".$dated."'
                                                AND id_complaint_by = '".$complainBy."' LIMIT 1");
            if(mysqli_num_rows($sqllmsCheck)) {

                $data['success'] 	= '0';
                $data['message'] 	= 'Record Already Exist..';

            } else { 

                $sqllmsInsert  = $dblms->querylms("INSERT INTO ".COMPLAINTS."(
                                                                    status								, 
                                                                    id_type								, 
                                                                    complaint_by                        ,
                                                                    id_complaint_by						, 
                                                                    name                                ,
                                                                    phone                               ,
                                                                    assign_to                           ,
                                                                    id_source                           ,
                                                                    dated                               ,
                                                                    title								,   
                                                                    detail							    ,
                                                                    id_campus							,
                                                                    id_added							, 
                                                                    date_added 	
                                                                )
                                                            VALUES(
                                                                    '1'										,  
                                                                    '".cleanvars($get_method['id_type'])."' ,	
                                                                    '".cleanvars($response['login_for'])."' ,	 
                                                                    '".cleanvars($complainBy)."'            ,	 
                                                                    '".cleanvars($get_method['name'])."'    ,
                                                                    '".cleanvars($get_method['phone'])."'   ,
                                                                    '".cleanvars($get_method['assign_to'])."',
                                                                    '2'                                     ,
                                                                    '".$dated."'                             ,
                                                                    '".cleanvars($get_method['title'])."'   ,
                                                                    '".cleanvars($get_method['detail'])."'	,
                                                                    '".cleanvars($response['id_campus'])."' ,
                                                                    '".cleanvars($response['adm_id'])."'    ,
                                                                    NOW()
                                                                )"
                                        );
                if($sqllmsInsert) { 

                    $data['success'] 	= '1';
                    $data['message'] 	= 'Successfully Added..';

                }

            } // End Record Check

            $jsonObj = $data;

        } else {	

            $jsonObj = $response;

        }        


        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
        
    }

    else if($get_method['method_name'] == "diary") 
    {

        $response = credential_check($get_method['username'], $get_method['password']);

        $jsonObj= array();
        
            if($response['login_for'] == 3)
            {
                if(isset($get_method['date']))
                {
                    $sql2 = " AND dated = '".cleanvars($get_method['date'])."' ";
                }
                else
                {
                    // $sql2 = " AND dated =  '".date('Y-m-d')."' ";
                    $sql2 = "";

                }

                $sqllms_diary = $dblms->querylms("SELECT id, note, dated
                                                        FROM ".DIARY." 
                                                        WHERE status = '1' AND id_class ='".$get_method['id_class']."'
                                                        AND id_section = '".$get_method['id_section']."'
                                                        AND id_subject = '".$get_method['id_subject']."'
                                                        AND id_session = '".$response['id_session']."'
                                                        AND id_teacher = '".$response['emply_id']."'
                                                        AND id_campus = '".$response['id_campus']."'
                                                        $sql2 ORDER BY id ASC");

                //-----------------------------------------------------
                $today = date('Y-m-d');
                //-----------------------------------------------------

                while($value_diary = mysqli_fetch_array($sqllms_diary))
                {
                    
                    if($value_diary['dated'] == $today)
                    {
                        $is_right = "1";
                    }
                    else
                    {
                        $is_right = "0";
                    }
                    
                    $date = date('d, M Y', strtotime(cleanvars($value_diary['dated'])));

                    $data['id']     = cleanvars($value_diary['id']);
                    $data['note']   = cleanvars($value_diary['note']);
                    $data['date']   = $date;
                    $data['right']  = $is_right;

                    array_push($jsonObj,$data);

                }

            }
            else if($response['login_for'] == 5)
            {

                if(isset($get_method['date']))
                {
                    $sql2 = " AND d.dated = '".cleanvars($get_method['date'])."' ";
                }
                else
                {
                    
                    $sql2 = " AND d.dated =  '".date('Y-m-d')."' ";

                }

                $sqllms_diary = $dblms->querylms("SELECT d.note, d.dated , s.subject_name
                                                        FROM ".DIARY." d 
                                                        INNER JOIN ".CLASS_SUBJECTS." s ON s.subject_id = d.id_subject
                                                        WHERE d.status = '1' AND d.id_class ='".$response['id_class']."'
                                                        AND d.id_section = '".$response['id_section']."'
                                                        AND d.id_session = '".$response['id_session']."'
                                                        AND d.id_campus = '".$response['id_campus']."'
                                                        $sql2  ORDER BY d.id ASC");

                //-----------------------------------------------------

                while($value_diary = mysqli_fetch_array($sqllms_diary))
                {
                    
                    $date = date('d, M Y', strtotime(cleanvars($value_diary['dated'])));

                    $data['name'] = cleanvars($value_diary['subject_name']);
                    $data['note'] = cleanvars($value_diary['note']);
                    $data['date'] = $date;

                    array_push($jsonObj,$data);

                }
            }
            else
            {	
                $jsonObj = $response;
            } 
        //----------------------------------------------------- 

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
        
    }

    else if($get_method['method_name'] == "add_diary")
    {
        $response = credential_check($get_method['username'], $get_method['password']);
        
        $jsonObj = array();

        if(isset($get_method['id_class']) && isset($get_method['id_section']) && isset($get_method['id_subject']) && isset($get_method['note']))
        {

            //----------------- TODAY'S DATE --------------------
            $dated = date('Y-m-d');
            //----------------insert record----------------------
            // if(isset($_POST['submit_diary'])) 
            // { 
                $sqllmscheck  = $dblms->querylms("SELECT dated
                                                    FROM ".DIARY." 
                                                    WHERE dated = '".cleanvars($dated)."' 
                                                    AND id_session = '".cleanvars($response['id_session'])."'
                                                    AND id_class   = '".cleanvars($get_method['id_class'])."'
                                                    AND id_section = '".cleanvars($get_method['id_section'])."'
                                                    AND id_subject = '".cleanvars($get_method['id_subject'])."'
                                                    AND id_teacher = '".cleanvars($response['emply_id'])."'
                                                    AND id_campus  = '".cleanvars($response['id_campus'])."' LIMIT 1");
                if(mysqli_num_rows($sqllmscheck)) {
                    //--------------------------------------
                    $data['success'] 	= '0';
                    $data['message'] 	= 'Record Already Exist..';
                    //--------------------------------------
                } 
                else 
                { 

                    $sqllms  = $dblms->querylms("INSERT INTO ".DIARY."(
                                                                        status								, 
                                                                        dated								, 
                                                                        note								,   
                                                                        id_session							,
                                                                        id_class 							,
                                                                        id_section							, 
                                                                        id_subject							,
                                                                        id_teacher							,
                                                                        id_campus							,
                                                                        id_added							, 
                                                                        date_added 	
                                                                    )
                                                                VALUES(
                                                                        '1'																,  
                                                                        '".cleanvars($dated)."'											,	 
                                                                        '".cleanvars($get_method['note'])."'									,
                                                                        '".cleanvars($response['id_session'])."'	                    ,	
                                                                        '".cleanvars($get_method['id_class'])."'								,	
                                                                        '".cleanvars($get_method['id_section'])."'							,	
                                                                        '".cleanvars($get_method['id_subject'])."'						    ,
                                                                        '".cleanvars($response['emply_id'])."'		    				,
                                                                        '".cleanvars($response['id_campus'])."'		                    ,
                                                                        '".cleanvars($response['adm_id'])."'		                	,
                                                                        NOW()
                                                                    )"
                                            );
                                            
                    $diary_id = $dblms->lastestid();

                    //--------------------------------------
                    if($sqllms) { 
                    //--------------------------------------
                        $remarks = 'Add Diary ID: "'.cleanvars($diary_id).'"';
                        $sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
                                                                            id_user										, 
                                                                            filename									, 
                                                                            action										,
                                                                            dated										,
                                                                            ip											,
                                                                            remarks				
                                                                        )
                        
                                                                    VALUES(
                                                                            '".cleanvars($response['adm_id'])."'	    ,
                                                                            'api.php'                                   , 
                                                                            '1'											, 
                                                                            NOW()										,
                                                                            '".cleanvars($ip)."'						,
                                                                            '".cleanvars($remarks)."'			
                                                                        )
                                                    ");
                    //--------------------------------------
                        $data['success'] 	= '1';
                        $data['message'] 	= 'Successfully Added..';
                    //--------------------------------------
                    }
                    //--------------------------------------
                } // end checker
            //--------------------------------------
            // } 
        }
        else
        {

            $data['success'] 	= '0';

        }

        $jsonObj = $data;

        //----------------------------------------------------- 

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
    }

    else if($get_method['method_name'] == "update_diary")
    {
        $response = credential_check($get_method['username'], $get_method['password']);
        
        $jsonObj = array();

        if(isset($get_method['id']) && isset($get_method['note']))
        {
            $sqllms  = $dblms->querylms("UPDATE ".DIARY." SET  
													note			= '".cleanvars($get_method['note'])."'
												  , id_modify		= '".cleanvars($response['adm_id'])."'
												  , date_modify		= NOW()
   											  WHERE id				= '".cleanvars($get_method['id'])."'");

            if($sqllms) { 
                //--------------------------------------
                $remarks = 'Updated Diary ID: "'.cleanvars($get_method['id']).'"';
                $sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
                                                                    id_user										, 
                                                                    filename									, 
                                                                    action										,
                                                                    dated										,
                                                                    ip											,
                                                                    remarks			
                                                                )
                
                                                            VALUES(
                                                                    '".cleanvars($response['adm_id'])."'	    ,
                                                                    'api.php'                                   , 
                                                                    '2'											, 
                                                                    NOW()										,
                                                                    '".cleanvars($ip)."'						,
                                                                    '".cleanvars($remarks)."'		
                                                                )
                                            ");
                //--------------------------------------
                $data['success'] 	= '1';
                $data['message'] 	= 'Successfully Updated..';
                //--------------------------------------
            }
        }
        else
        {
            $data['success'] 	= '0';
        }
        $jsonObj = $data;

        //----------------------------------------------------- 

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
    }

    else if($get_method['method_name'] == "subjects") 
    {

        $response = credential_check($get_method['username'], $get_method['password']);

        $jsonObj= array();

        
        if($response['login_for'] == 3)
        {

            $sqllms_subject	= $dblms->querylms("SELECT t.id_session, t.id_class, t.id_section, s.subject_id, s.subject_code, s.subject_name
                                                        FROM ".TIMETABEL_DETAIL." 	 d 
                                                        INNER JOIN ".TIMETABLE."  	 t 	ON 	t.id = d.id_setup
                                                        INNER JOIN ".CLASS_SUBJECTS." s ON 	s.subject_id = d.id_subject
                                                        WHERE t.id_campus = '".$response['id_campus']."' 
                                                        AND d.id_teacher = '".$response['emply_id']."'
                                                        AND id_session = '".$response['id_session']."'
                                                        AND t.status = '1' 
                                                    ");

            while($value_subject = mysqli_fetch_array($sqllms_subject))
            {

                $data['id'] = $value_subject['subject_id'];
                $data['code'] = $value_subject['subject_code'];
                $data['name'] = $value_subject['subject_name'];
                $data['class'] = $value_subject['id_class'];
                $data['section'] = $value_subject['id_section'];

                array_push($jsonObj,$data);
            }

        }
        else if($response['login_for'] == 5)
        {

            $sqllms_subject	= $dblms->querylms("SELECT subject_id, subject_code, subject_name
                                                    FROM ".CLASS_SUBJECTS."
                                                    WHERE id_class = '".$response['id_class']."' AND subject_status = '1' 
                                                ");

            while($value_subject = mysqli_fetch_array($sqllms_subject))
            {

                $data['id'] = $value_subject['subject_id'];
                $data['code'] = $value_subject['subject_code'];
                $data['name'] = $value_subject['subject_name'];

                array_push($jsonObj,$data);
            }
        }                                   
        else
        {	
            $jsonObj = $response;
        } 
        //----------------------------------------------------- 

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
        
    } 
    
    else if($get_method['method_name'] == "worksheet") 
    {

        $response = credential_check($get_method['username'], $get_method['password']);

        $jsonObj= array();
        
        if($response['login_for'] == 5)
        {
            //-----------------------------------------------------
            
            $sqllms_worksheet = $dblms->querylms("SELECT syllabus_id, syllabus_term, syllabus_file, id_month, id_week, note
                                                        FROM ".SYLLABUS."
                                                        WHERE id_session = '".$response['id_session']."'
                                                        AND id_class = '".$response['id_class']."' AND id_subject = '".$get_method['id']."'
                                                        AND syllabus_status = '1' AND syllabus_type = '3'
                                                        ORDER BY syllabus_id DESC
                                                        ");

            //-----------------------------------------------------

            while($value_worksheet = mysqli_fetch_array($sqllms_worksheet))
            {
                            
                if($value_worksheet['syllabus_term'] == 1){
                    $term = 'First Term';
                }
                elseif($value_worksheet['syllabus_term'] == 2){
                    $term = 'Second Term';
                }

                $link = $url."uploads/worksheet/".$value_worksheet['syllabus_file'];

                $data['term'] = $term;
                $data['month'] = get_monthtypes($value_worksheet['id_month']);
                $data['url'] = $link;

                array_push($jsonObj,$data);
                
            }
        
        }                               
        else
        {	
            $jsonObj = $response;
        }

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
        
    }
    
    else if($get_method['method_name'] == "weekly_learning_resources") 
    {

        $response = credential_check($get_method['username'], $get_method['password']);

        $jsonObj= array();

        if($response['login_for'] == 5)
        {
            //-----------------------------------------------------
            $sqllms_resources = $dblms -> querylms("SELECT res_id, res_status, res_file, id_class, week, id_term, note
                                        FROM ".LEARNING_RESOURCES."
                                        WHERE res_status = '1' AND id_session = '".$response['id_session']."'
                                        AND id_class = '".$response['id_class']."' AND id_subject = '".$get_method['id']."'
                                        ORDER BY res_id DESC");
            //-----------------------------------------------------

            while($value_resources = mysqli_fetch_array($sqllms_resources))
            {

                $link = $url."uploads/learning_resources/".$value_resources['res_file'];

                $data['week'] = "Week : ".$value_resources['week'];
                $data['url'] = $link;
                
                array_push($jsonObj,$data);
            }

        }
        //----------------------------------------------------- 

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
        
    }

    else if($get_method['method_name'] == "daily_lesson_plan") 
    {

        $response = credential_check($get_method['username'], $get_method['password']);

        $jsonObj= array();

        if($response['login_for'] == 3)
        {
            $sqllmsDLP = $dblms -> querylms("SELECT syllabus_id, syllabus_term, id_month, id_week, syllabus_file, note
                                                FROM ".SYLLABUS." 
                                                WHERE syllabus_status = '1' AND syllabus_type = '2'
                                                AND id_session = '".$response['id_session']."'
                                                AND id_class   = '".$response['id_class']."'
                                                AND id_subject = '".$get_method['id']."'
                                                ORDER BY syllabus_id DESC");                                 
            while($valueDLP = mysqli_fetch_array($sqllmsDLP))
            {

                if($valueDLP['syllabus_term'] == 1){
                    $term = 'First Term';
                }
                elseif($valueDLP['syllabus_term'] == 2){
                    $term = 'Second Term';
                }

                $link = $url."uploads/dlp/".$valueDLP['syllabus_file'];

                $data['term'] = $term;
                $data['month'] = get_monthtypes($valueDLP['id_month']);
                $data['week'] = $valueDLP['id_week'];
                $data['url'] = $link;
                $data['note'] = $valueDLP['note'];
                
                array_push($jsonObj,$data);
                
            }
        }
        else
        {
            $jsonObj = $response;

        }
        

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
        
    }

    else if($get_method['method_name'] == "digital_resources") 
    {
        
        $response = credential_check($get_method['username'], $get_method['password']);

        $jsonObj= array();

        if($response['login_for'] == 3)
        {

            //-----------------------------------------------------

            $sqllms_res	= $dblms->querylms("SELECT  title, id_type, url
                                                    FROM ".DIGITAL_RESOURCES."
                                                    WHERE id_session = '".$response['id_session']."'
                                                    AND id_class =  '".$response['id_class']."' 
                                                    AND id_subject = '".$get_method['id']."'
                                                    AND status = '1' ORDER BY id DESC									
                                                ");
                                        
            //-----------------------------------------------------

            while($value_res = mysqli_fetch_array($sqllms_res))
            {

                $data['title']  = $value_res['title'];
                $data['type']   = get_digitalresource($value_res['id_type']);
                $data['url']    = $value_res['url'];
                
                array_push($jsonObj,$data);

            } 

        }
        else
        {
            $jsonObj = $response;
        }
        
        //----------------------------------------------------- 

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
        
    }

    else if($get_method['method_name'] == "scheme_of_study") 
    {

        $response = credential_check($get_method['username'], $get_method['password']);

        $jsonObj= array();
        
        if($response['login_for'] == 3)
        {
            //-----------------------------------------------------
            
            $sqllms_scheme = $dblms->querylms("SELECT title, term, file, note
                                                        FROM ".SCHEME_OF_STUDY."
                                                        WHERE id_session = '".$response['id_session']."'
                                                        AND id_class = '".$response['id_class']."' 
                                                        AND id_subject = '".$get_method['id']."'
                                                        AND status = '1'
                                                        ORDER BY id DESC
                                                    ");

            //-----------------------------------------------------

            while($value_scheme = mysqli_fetch_array($sqllms_scheme))
            {
                            
                if($value_scheme['term'] == 1){
                    $term = 'First Term';
                }
                elseif($value_scheme['term'] == 2){
                    $term = 'Second Term';
                }

                $link = $url."uploads/scheme_of_study/".$value_scheme['file'];

                $data['title'] = $value_scheme['title'];
                $data['term'] = $term;
                $data['url'] = $link;

                array_push($jsonObj,$data);
                
            }
        
        }                               
        else
        {	
            $jsonObj = $response;
        }

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
        
    }
    
    else if($get_method['method_name'] == "teaching_guides") 
    {

        $response = credential_check($get_method['username'], $get_method['password']);

        $jsonObj= array();

        if($response['login_for'] == 3)
        {
            //-----------------------------------------------------

            $sqllms_guide = $dblms->querylms("SELECT  guide_title, guide_file, guide_term, id_class, id_session
                                                FROM ".TEACHING_GUIDES."
                                                WHERE guide_status = '1' 
                                                AND id_subject = '".$get_method['id']."'
                                                AND  id_session = '".$response['id_session']."'
                                                AND id_class = '".$response['id_class']."' 
                                                ORDER BY guide_id DESC
                                                ");
                                        
            //-----------------------------------------------------

            while($value_guide = mysqli_fetch_array($sqllms_guide))
            {
                         
                if($value_guide['guide_term'] == 1){
                    $term = 'First Term';
                }
                elseif($value_guide['guide_term'] == 2){
                    $term = 'Second Term';
                }
                
                $link = $url."uploads/teaching_guides/".$value_guide['guide_file'];

                $data['title'] = $value_guide['guide_title'];
                $data['term']  = $term;
                $data['url']   = $link;
                

                array_push($jsonObj,$data);
            }

        }
        else
        {
            $jsonObj = $response;   
        }
        //----------------------------------------------------- 

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
        
    }
    
    else if($get_method['method_name'] == "videos") 
    {
        
        $response = credential_check($get_method['username'], $get_method['password']);

        $jsonObj= array();

        if($response['login_for'] == 5)
        {

            //-----------------------------------------------------

            $sqllms_video	= $dblms->querylms("SELECT  id, title, thumbnail, youtube_code
                                        FROM ".VIDEO_LECTURE."
                                        WHERE id_session = '".$response['id_session']."'
                                        AND id_class =  '".$response['id_class']."' 
                                        AND id_subject = '".$get_method['id']."'
                                        AND status = '1' ORDER BY id DESC									
                                        ");
                                        
            //-----------------------------------------------------

            while($value_video = mysqli_fetch_array($sqllms_video))
            {

                $data['title'] = $value_video['title'];
                $data['thumb'] = $value_video['thumbnail'];
                $data['url']   = $value_video['youtube_code'];
                
                array_push($jsonObj,$data);

            } 

        }
        else
        {
            $jsonObj = $response;
        }
        
        //----------------------------------------------------- 

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
        
    }

    else if($get_method['method_name'] == "training_videos") 
    {
        
        $response = credential_check($get_method['username'], $get_method['password']);

        $jsonObj= array();

        if($response['login_for'] == 3)
        {

            //-----------------------------------------------------

            $sqllms_training = $dblms->querylms("SELECT id, title, thumbnail, youtube_url, details
                                                        FROM ".TRAINING_VIDEOS."
                                                        WHERE status = '1' ORDER BY id DESC									
                                                ");
                                        
            //-----------------------------------------------------

            while($value_training = mysqli_fetch_array($sqllms_training))
            {

                $data['title']  = $value_training['title'];
                $data['thumb']  = $value_training['thumbnail'];
                $data['url']    = $value_training['youtube_url'];
                $data['detail'] = $value_training['details'];
                
                array_push($jsonObj,$data);

            } 

        }
        else
        {
            $jsonObj = $response;
        }
        
        //----------------------------------------------------- 

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
        
    }
    
    else if($get_method['method_name'] == "vacation_tasks") 
    {

        $response = credential_check($get_method['username'], $get_method['password']);

        $jsonObj= array();

        if($response['login_for'] == 5)
        {
            //-----------------------------------------------------

            $sqllms_task = $dblms->querylms("SELECT summer_id, summer_status, id_type, summer_file, id_month, id_class, id_session
                                                FROM ".SUMMER_WORK."
                                                WHERE summer_status = '1' AND  id_session = '".$response['id_session']."'
                                                AND id_class = '".$response['id_class']."' 
                                                ORDER BY summer_id DESC
                                                ");
                                        
            //-----------------------------------------------------

            while($value_task = mysqli_fetch_array($sqllms_task))
            {
                //-----------------------------------------------------
                if($value_task['id_type'] == '1'){
                    $type = "Summer";
                }elseif($value_task['id_type'] == '2'){
                    $type = "Winter";
                }else{
                    $type="";
                }
                //-----------------------------------------------------
                
                $link = $url."uploads/summer-work/".$value_task['summer_file'];

                $data['type'] = $type;
                $data['month'] = get_monthtypes($value_task['id_month']);
                $data['url'] = $link;
                

                array_push($jsonObj,$data);
            }

        }
        else
        {
            $jsonObj = $response;   
        }
        //----------------------------------------------------- 

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
        
    }
    
    else if($get_method['method_name'] == "student_attendance") 
    {
        $response = credential_check($get_method['username'], $get_method['password']);

        $jsonObj= array();

        if($response['login_for'] == 3)
        {
            if(isset($get_method['id']))
            {
                 //-----------------------------------------------------

                $sql_atten = $dblms->querylms("SELECT a.dated, d.status, s.std_name, s.std_rollno, s.std_photo
                                                    FROM ".STUDENT_ATTENDANCE." a
                                                    INNER JOIN ".STUDENT_ATTENDANCE_DETAIL." d ON d.id_setup = a.id
                                                    INNER JOIN ".STUDENTS." s ON s.std_id = d.id_std
                                                    WHERE a.id_campus = '".cleanvars($response['id_campus'])."'
                                                    AND a.id_session = '".cleanvars($response['id_session'])."'
                                                    AND s.std_status = '1' AND a.id = '".$get_method['id']."'
                                                ");
                                            
                //-----------------------------------------------------

                while($value_att = mysqli_fetch_array($sql_atten))
                {

                    $date = date('d, M Y', strtotime(cleanvars($value_att['dated'])));

                    if($value_att['std_photo'])
                    {
                        $photo = $url."uploads/images/students/".$value_att['std_photo'];
                    }
                    else
                    {
                        $photo = $url."uploads/admin_image/default.jpg";
                    }

                    $data['status']  = get_attendtype1($value_att['status']);
                    $data['name']    = $value_att['std_name'];
                    $data['rollno']  = $value_att['std_rollno'];
                    $data['photo']   = $photo;
                    

                    array_push($jsonObj,$data);
                }
            }
            else 
            {
                //-----------------------------------------------------

                $sql_atten = $dblms->querylms("SELECT id, dated, id_class, id_section
                                                        FROM ".STUDENT_ATTENDANCE."
                                                        WHERE id_campus = '".cleanvars($response['id_campus'])."'
                                                        AND id_session = '".cleanvars($response['id_session'])."'
                                                        AND id_teacher = '".cleanvars($response['emply_id'])."'
                                                        ORDER BY dated DESC
                                                    ");
                                            
                //-----------------------------------------------------

                while($value_att = mysqli_fetch_array($sql_atten))
                {

                    //------------------------------------------------
                    $sqllmstotal  = $dblms->querylms("SELECT COUNT(std_id) AS totalstudent     
                                                            FROM  ".STUDENTS." 
                                                            WHERE id_class = '".$value_att['id_class']."' AND id_section = '".$value_att['id_section']."'
                                                            AND std_status = '1'");
                    $valuetotal = mysqli_fetch_array($sqllmstotal);
                    //------------------------------------------------
                    $sqllmsprsent  = $dblms->querylms("SELECT COUNT(dt.id) AS totalpresent     
                                                            FROM ".STUDENT_ATTENDANCE_DETAIL." dt 
                                                            INNER JOIN ".STUDENTS." std ON std.std_id = dt.id_std  
                                                            WHERE dt.status = '1' AND dt.id_setup = '".cleanvars($value_att['id'])."' 
                                                            AND std.std_status = '1'");
                    $valuepresent = mysqli_fetch_array($sqllmsprsent);
                    //------------------------------------------------
                    $sqllmsabsent  = $dblms->querylms("SELECT COUNT(dt.id) AS totalabsent     
                                                            FROM ".STUDENT_ATTENDANCE_DETAIL." dt 
                                                            INNER JOIN ".STUDENTS." std ON std.std_id = dt.id_std  
                                                            WHERE dt.status = '2' AND dt.id_setup = '".cleanvars($value_att['id'])."' 
                                                            AND std.std_status = '1'");
                    $valueabsent = mysqli_fetch_array($sqllmsabsent);
                    //------------------------------------------------
                    $sqllmsleave  = $dblms->querylms("SELECT COUNT(dt.id) AS totalleave     
                                                            FROM ".STUDENT_ATTENDANCE_DETAIL." dt 
                                                            INNER JOIN ".STUDENTS." std ON std.std_id = dt.id_std  
                                                            WHERE dt.status = '3' AND dt.id_setup = '".cleanvars($value_att['id'])."' 
                                                            AND std.std_status = '1'");
                    $valueleave = mysqli_fetch_array($sqllmsleave);
                    //------------------------------------------------

                    $date = date('d, M Y', strtotime(cleanvars($value_att['dated'])));

                    if($value_att['dated'] == date('Y-m-d'))
                    {
                        $right = "1";
                    }
                    else
                    {
                        $right = "0";
                    }

                    $data['id']         = $value_att['id'];
                    $data['date']       = $date;
                    $data['edit_right'] = $right;
                    $data['total']      = $valuetotal['totalstudent'];
                    $data['present']    = $valuepresent['totalpresent'];
                    $data['absent']     = $valueabsent['totalabsent'];
                    $data['leave']      = $valueleave['totalleave'];
                    

                    array_push($jsonObj,$data);
                }
            }

        }

        else if($response['login_for'] == 5)
        {

            $response = credential_check($get_method['username'], $get_method['password']);

            $jsonObj= array();
            
            //-----------------------------------------------------
            
            if(isset($get_method['month']))
            {
                $sql2 = " AND MONTH(a.dated) = '".$get_method['month']."' ";
            }
            else
            {
                $month = date('m');

                $sql2 = " AND MONTH(a.dated) = '".$month."' ";
            }

            //-----------------------------------------------------

            $sql_atten = $dblms->querylms("SELECT a.dated, d.status
                                                    FROM ".STUDENT_ATTENDANCE." a
                                                    INNER JOIN ".STUDENT_ATTENDANCE_DETAIL." d ON d.id_setup = a.id
                                                    WHERE a.id_campus = '".cleanvars($response['id_campus'])."'
                                                    AND a.id_session = '".cleanvars($response['id_session'])."'
                                                    AND d.id_std = '".$response['std_id']."' 
                                                    $sql2 ORDER BY a.dated DESC
                                                ");
                                        
            //-----------------------------------------------------

            while($value_att = mysqli_fetch_array($sql_atten))
            {

                $date = date('d, M Y', strtotime(cleanvars($value_att['dated'])));
                $day = date('d', strtotime(cleanvars($value_att['dated'])));
                $month = date('m', strtotime(cleanvars($value_att['dated'])));
                $year = date('Y', strtotime(cleanvars($value_att['dated'])));


                $data['date']   =  $date;
                $data['day']    =  $day;
                $data['month']  =  $month;
                $data['year']   =  $year;
                $data['status'] = $value_att['status'];
                

                array_push($jsonObj,$data);
            }

        }
        else
        {
            $jsonObj = $response;
        }
        
        //----------------------------------------------------- 

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
        
    }

    else if($get_method['method_name'] == "add_student_attendance")
    {
        $response = credential_check($get_method['username'], $get_method['password']);
        
        $jsonObj = array();

        if(isset($get_method['id_class']) && isset($get_method['id_section']) && isset($get_method['date']) )
        {

            //----------------- TODAY'S DATE --------------------
            $dated = cleanvars($get_method['date']);
            //----------------insert record----------------------
                $sqllmscheck  = $dblms->querylms("SELECT dated
                                                    FROM ".STUDENT_ATTENDANCE." 
                                                    WHERE dated = '".cleanvars($dated)."' 
                                                    AND id_session = '".cleanvars($response['id_session'])."'
                                                    AND id_class   = '".cleanvars($get_method['id_class'])."'
                                                    AND id_section = '".cleanvars($get_method['id_section'])."'
                                                    AND id_teacher = '".cleanvars($response['emply_id'])."'
                                                    AND id_campus  = '".cleanvars($response['id_campus'])."' LIMIT 1");
                if(mysqli_num_rows($sqllmscheck)) {
                    //--------------------------------------
                    $jsonObj =  $data['success'] 	= '0';
                    //--------------------------------------
                    array_push($jsonObj,$data);
                } 
                else 
                { 

                    $sqllms  = $dblms->querylms("INSERT INTO ".STUDENT_ATTENDANCE."
																(						 
																	status							,
																	dated							,
																	id_class						,
																	id_section						,								 
																	id_teacher						,								 
																	id_session						,
																	id_campus 						,	
																	id_added						,		
																	date_added
																)
															VALUES(	
																	'1'											,	
																	'".cleanvars($dated)."'						, 
																	'".cleanvars($get_method['id_class'])."'		    ,
																	'".cleanvars($get_method['id_section'])."'	    ,							
																	'".cleanvars($response['emply_id'])."'	    ,							
																	'".cleanvars($response['id_session'])."'	,						
																	'".cleanvars($response['id_campus'])."'	    ,		
																	'".cleanvars($response['adm_id'])."'  	    ,
																	NOW()	
																)
					  				");
                    //----------------------------------------------
                    $idsetup = $dblms->lastestid();
                    //----------------------------------------------
                    foreach($get_method['students'] as $student)
                    {
                        $sqllms_detail = $dblms->querylms("INSERT INTO ".STUDENT_ATTENDANCE_DETAIL."
                                                                    (						
                                                                        id_setup			,
                                                                        id_std				,
                                                                        status		
                                                                    )
                                                                VALUES(	
                                                                        '".cleanvars($idsetup)."'	    ,
                                                                        '".cleanvars($student[0])."'	,
                                                                        '".cleanvars($student[1])."'
                                                                    )
                                                    ");
                    }

                    //--------------------------------------
                    if($sqllms) { 
                        //--------------------------------------
                        $remarks = 'Added Student Attendance ID: "'.cleanvars($idsetup).'"';

                        $sqllmslog  = $dblms->querylms ("INSERT INTO ".LOGS." (
                                                                            id_user										, 
                                                                            filename									, 
                                                                            action										,
                                                                            dated										,
                                                                            ip											,
                                                                            remarks				
                                                                        )
                        
                                                                    VALUES(
                                                                            '".cleanvars($response['adm_id'])."'	    ,
                                                                            'api.php'                                   , 
                                                                            '1'											, 
                                                                            NOW()										,
                                                                            '".cleanvars($ip)."'						,
                                                                            '".cleanvars($remarks)."'			
                                                                        )
                                                        ");
                        //--------------------------------------
                        $data['success'] 	= '1';
                        $data['message'] 	= 'Added Successfully..';
                        //--------------------------------------
                    }
                    //--------------------------------------
                    $jsonObj = $data;

                } // end checker
        }
        else if(isset($get_method['id_class']) && isset($get_method['id_section']) )
        {

            $sql_atten = $dblms->querylms("SELECT std_id, std_name, std_rollno, std_regno, std_photo
                                                    FROM ".STUDENTS."
                                                    WHERE id_campus = '".cleanvars($response['id_campus'])."' 
                                                    AND id_session = '".cleanvars($response['id_session'])."'
                                                    AND id_class = '".cleanvars($get_method['id_class'])."' 
                                                    AND id_section = '".cleanvars($get_method['id_section'])."'
                                                    AND std_status = '1'
                                                ");
                                        
            //-----------------------------------------------------
            while($value_att = mysqli_fetch_array($sql_atten))
            {

                if($value_att['std_photo'])
                {
                    $photo = $url."uploads/images/students/".$value_att['std_photo'];
                }
                else
                {
                    $photo = $url."uploads/admin_image/default.jpg";
                }
                $data['id']      = $value_att['std_id'];
                $data['name']    = $value_att['std_name'];
                $data['regno']   = $value_att['std_regno'];
                $data['rollno']  = $value_att['std_rollno'];
                $data['photo']   = $photo;

                array_push($jsonObj,$data);
            }
                
        }
        else
        {

            $jsonObj =  $data['success'] 	= '0';

            array_push($jsonObj,$data);

        }

        //----------------------------------------------------- 

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
    }

    else if($get_method['method_name'] == "update_student_attendance")
    {
        $response = credential_check($get_method['username'], $get_method['password']);
        
        $jsonObj = array();

        if(isset($get_method['id_edit']) && isset($get_method['date']) )
        {

            //----------------- DATE --------------------
            $dated = cleanvars($get_method['date']);

            $sqllms  = $dblms->querylms("UPDATE ".STUDENT_ATTENDANCE." SET  
										    id_modify	= '".cleanvars($response['adm_id'])."' 
										  , date_modify	= NOW() 
										  , id_campus	= '".cleanvars($response['id_campus'])."' 
                                        WHERE  id	    = '".cleanvars($get_method['id_edit'])."'");
            //----------------------------------------------
            foreach($get_method['students'] as $student)
            {
                 $sql_atten = $dblms->querylms("SELECT a.dated, d.status, s.std_name, s.std_regno, s.std_photo
                                                    FROM ".STUDENT_ATTENDANCE." a
                                                    INNER JOIN ".STUDENT_ATTENDANCE_DETAIL." d ON d.id_setup = a.id
                                                    INNER JOIN ".STUDENTS." s ON s.std_id = d.id_std
                                                    WHERE d.id_setup = '".cleanvars($get_method['id_edit'])."' 
                                                    AND d.id_std = '".cleanvars($student[0])."' LIMIT 1");
                if(mysqli_num_rows($sql_atten) == 0)
                {

                    $sqllms_detail = $dblms->querylms("INSERT INTO ".STUDENT_ATTENDANCE_DETAIL."
                                                            (						
                                                                id_setup			,
                                                                id_std				,
                                                                status		
                                                            )
                                                        VALUES(	
                                                                '".cleanvars($get_method['id_edit'])."'   ,
                                                                '".cleanvars($student[0])."'	    ,
                                                                '".cleanvars($student[1])."'
                                                            )
                                            ");

                }
                else{

                    $sqllms_detail  = $dblms->querylms("UPDATE ".STUDENT_ATTENDANCE_DETAIL." SET  
                                                                status		    = '".cleanvars($student[1])."' 
                                                                WHERE id_std 	= '".cleanvars($student[0])."'
                                                                AND id_setup 	= '".cleanvars($get_method['id_edit'])."'
                                                            ");
                }
            
            }

            //--------------------------------------
            if($sqllms) { 
            //--------------------------------------
                $remarks = 'Update Student Attendance: "'.cleanvars($get_method['id_edit']).'"';
                $sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
                                                                    id_user										, 
                                                                    filename									, 
                                                                    action										,
                                                                    dated										,
                                                                    ip											,
                                                                    remarks				
                                                                )
                
                                                            VALUES(
                                                                    '".cleanvars($response['adm_id'])."'	    ,
                                                                    'api.php'                                   , 
                                                                    '2'											, 
                                                                    NOW()										,
                                                                    '".cleanvars($ip)."'						,
                                                                    '".cleanvars($remarks)."'			
                                                                )
                                            ");
                //--------------------------------------
                $data['success'] 	= '1';
                $data['message'] 	= 'Updated Successfully..';
                //--------------------------------------

                $jsonObj = $data;
            }

        } else{
        
            $sqlStudent  = $dblms->querylms("SELECT s.std_id, s.std_name, s.std_rollno, s.std_regno, s.std_photo 
                                                FROM ".STUDENTS." s
                                                WHERE s.std_status = '1' AND s.id_class = '".cleanvars($get_method['id_class'])."'
                                                AND s.id_section = '".cleanvars($get_method['id_section'])."' ");

            while($valueStudent = mysqli_fetch_array($sqlStudent))
            {

                $sql_atten = $dblms->querylms("SELECT a.dated, d.status
                                                    FROM ".STUDENT_ATTENDANCE." a
                                                    INNER JOIN ".STUDENT_ATTENDANCE_DETAIL." d ON d.id_setup = a.id
                                                    INNER JOIN ".STUDENTS." s ON s.std_id = d.id_std
                                                    WHERE id_setup = '".cleanvars($get_method['id_edit'])."' 
                                                    AND id_std = '".cleanvars($valueStudent['std_id'])."' LIMIT 1");
                $value_att = mysqli_fetch_array($sql_atten);

                $photo = $url."uploads/images/students/".$valueStudent['std_photo'];

                $data['id']      = $valueStudent['std_id'];
                $data['name']    = $valueStudent['std_name'];
                $data['regno']   = $valueStudent['std_regno'];
                $data['rollno']  = $valueStudent['std_rollno'];
                $data['status']  = $value_att['status'];
                $data['photo']   = $photo;
                

                array_push($jsonObj,$data);
            }
        }

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
    }    

    else if($get_method['method_name'] == "syllabus_breakdown") 
    {

        $response = credential_check($get_method['username'], $get_method['password']);

        $jsonObj= array();

        if($response['login_for'] == 3)
        {
            //-----------------------------------------------------
            $sqllms_resources = $dblms -> querylms("SELECT syllabus_id, syllabus_term, syllabus_file
                                        FROM ".SYLLABUS."
                                        WHERE syllabus_status = '1' AND id_subject = '".$get_method['id']."' 
                                        AND id_session = '".$response['id_session']."'
                                        AND id_class = '".$response['id_class']."' 
                                        AND syllabus_type = '1' ORDER BY syllabus_id DESC");
            //-----------------------------------------------------

            while($value_resources = mysqli_fetch_array($sqllms_resources))
            {
                        
                if($value_resources['syllabus_term'] == 1){
                    $term = 'First Term';
                }
                elseif($value_resources['syllabus_term'] == 2){
                    $term = 'Second Term';
                }

                $link = $url."uploads/learning_resources/".$value_resources['syllabus_file'];

                $data['term'] = $term;
                $data['url'] = $link;
                
                array_push($jsonObj,$data);
            }

        }
        else
        {	
            $jsonObj = $response;
        }   
        //----------------------------------------------------- 

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
        
    }

    else if($get_method['method_name'] == "user_profile") 
    {

        $response = credential_check($get_method['username'], $get_method['password']);

        $jsonObj= array();

        if($response['login_for'] == 3)
        {
            $name =  "Academic"; 
            //-----------------------------------------------------
            $sqllms_profile	= $dblms->querylms("SELECT  e.emply_regno, e.emply_name, e.id_type, e.emply_gender, e.emply_dob, e.emply_joindate,
                                                e.emply_education, e.emply_experence, e.emply_phone, e.emply_email, e.emply_photo, e.emply_address,
                                                e.id_class, e.id_section, d.dept_name, dp.designation_name 
                                                FROM ".EMPLOYEES." e      
                                                INNER JOIN ".DEPARTMENTS." d ON d.dept_id = e.id_dept
                                                INNER JOIN ".DESIGNATIONS." dp ON dp.designation_id = e.id_designation
                                                WHERE emply_id = '".$response['emply_id']."' LIMIT 1 ");
            //-----------------------------------------------------

            $value_profile = mysqli_fetch_array($sqllms_profile);

                if($value_profile['id_class'] && $value_profile['id_section'])
                {
                    $head = "1";
                }
                else
                {
                    $head = "0";
                }

                if($value_profile['emply_photo']) { 
                    $photo = $url."uploads/images/employees/".$value_profile['emply_photo'];
                } else {
                    $photo = $url."uploads/admin_image/default.jpg";
                }

                $dob = date('d, M Y', strtotime(cleanvars($value_profile['emply_dob'])));

                $join_date = date('d, M Y', strtotime(cleanvars($value_profile['emply_joindate'])));

                $data['name']       = $value_profile['emply_name'];
                $data['regno']      = $value_profile['emply_regno'];
                $data['type']       = $value_profile['id_type'];
                $data['dob']        = $dob;
                $data['gender']     = $value_profile['emply_gender'];
                $data['phone']      = $value_profile['emply_phone'];
                $data['email']      = $value_profile['emply_email'];
                $data['address']    = $value_profile['emply_address'];
                $data['designation']= $value_profile['designation_name'];
                $data['join_date']  = $join_date;
                $data['head']       = $head;
                $data['photo']      = $photo;


                $jsonObj = $data;

        }
        else if($response['login_for'] == 5)
        {
            $name =  "Academic"; 
            //-----------------------------------------------------
            $sqllms_profile	= $dblms->querylms("SELECT s.std_name, s.std_fathername, s.std_gender, s.std_dob, s.std_nic, s.std_phone, s.std_whatsapp, s.std_rollno, s.std_regno, s.std_admissiondate, s.std_photo, c.class_name, cs.section_name
                                                    FROM ".STUDENTS." s
                                                    INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
                                                    INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id = s.id_section
                                                    WHERE std_id = '".$response['std_id']."' LIMIT 1");
            //-----------------------------------------------------

            $value_profile = mysqli_fetch_array($sqllms_profile);

                if($value_profile['std_photo']) { 
                    $photo = $url."uploads/images/students/".$value_profile['std_photo'];
                } else {
                    $photo = $url."uploads/admin_image/default.jpg";
                }

                $dob = date('d, M Y', strtotime(cleanvars($value_profile['std_dob'])));

                $adm_date = date('d, M Y', strtotime(cleanvars($value_profile['std_admissiondate'])));

                $data['name']           = $value_profile['std_name'];
                $data['father_name']    = $value_profile['std_fathername'];
                $data['nic']            = $value_profile['std_nic'];
                $data['form_no']        = "";
                $data['gender']         = $value_profile['std_gender'];
                $data['birthday']       = $dob;
                $data['email']          = "";
                $data['contact_no']     = $value_profile['std_phone'];
                $data['whatsapp']       = $value_profile['std_whatsapp'];
                $data['roll_no']        = $value_profile['std_rollno']; 
                $data['reg_no']         = $value_profile['std_regno'];
                $data['admission_date'] = $adm_date;
                $data['class']          = $value_profile['class_name'];
                $data['section']        = $value_profile['section_name'];
                $data['photo']          = $photo;
                
                $jsonObj = $data;

                    $jsonObj0 = array();
                    
                    // $data1    = "";
                    //$data1['success']    = "";

                    // array_push($jsonObj0,$data1);
                    array_push($jsonObj0);

                    // $sqllms_task = $dblms->querylms("SELECT summer_id, summer_status, id_type, summer_file, id_month, id_class, id_session
                    //                                         FROM ".SUMMER_WORK." ");

                    // while($value_task = mysqli_fetch_array($sqllms_task))
                    // {
                    //     $data1['id'] = $value_task['summer_id'];                            

                    //     array_push($jsonObj0,$data1);
                    // }

                    $jsonObj['academic_history'] = $jsonObj0;

                    // array_push($jsonObj,$data0);

        }
        else
        {	
            $jsonObj = $response;
        }   
        //----------------------------------------------------- 

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();
        
    }

    else if($get_method['method_name'] == "user_login") 
    {	

        $jsonObj= array();

        // stripslashes
        //******* if we found an error save the error message in this variable**********
        $errorMessage = '';
        $admin_user   = cleanvars($get_method['username']);
        $admin_pass1  = cleanvars($get_method['password']);
        $admin_pass3  = ($admin_pass1);

        // **************Check the admin name and password exist*****************
            $sqllms	= $dblms->querylms("SELECT * FROM ".ADMINS."
                                                WHERE adm_username = '".$admin_user."' 
                                                AND adm_status = '1' 
                                                AND adm_logintype IN (3,4,5)  LIMIT 1");

        //************** if the admin name and password exist then **************** 	
        if (mysqli_num_rows($sqllms) == 1) {

            $row = mysqli_fetch_array($sqllms); 
            $salt = $row['adm_salt'];

            $password = hash('sha256', $admin_pass3 . $salt);
            for ($round = 0; $round < 65536; $round++) {
                $password = hash('sha256', $password . $salt);
            }
            
            
                if($password == $row['adm_userpass']) {

                    //******************* MAKE LOGIN HISTORY START ***********************
                        $sqllms  = $dblms->querylms("INSERT INTO ".LOGIN_HISTORY."(
                                                                            login_type			, 
                                                                            user_name			,  
                                                                            user_pass			,
                                                                            email				,
                                                                            id_campus			,
                                                                            dated			
                                                                        )
                                                                    VALUES(
                                                                            '".cleanvars($row['adm_logintype'])."'	    , 
                                                                            '".cleanvars($get_method['username'])."'	,
                                                                            '".cleanvars($get_method['password'])."'	,
                                                                            '".cleanvars($row['adm_email'])."'		    ,
                                                                            '".cleanvars($row['id_campus'])."'		    ,
                                                                            NOW()												
                                                                        )"
                                                );
                    //******************* MAKE LOGIN HISTORY END ***********************	

                    //******************* SELECT ACTIVE SESSION START *********************

                    $sqllms_setting	= $dblms->querylms("SELECT s.acd_session, se.session_name, se.session_startdate 
                                                                FROM ".SETTINGS." s  
                                                                INNER JOIN ".SESSIONS." se ON se.session_id = s.acd_session 
                                                                WHERE s.status ='1' AND s.is_deleted != '1' LIMIT 1");
                    //-----------------------------------------------------
                    $values_setting = mysqli_fetch_array($sqllms_setting);

                    //******************* SELECT ACTIVE SESSION END ***********************
                        
                    //--------- LOGIN USER PHOTO -------------

                    

                    // ***************Login time when the admin login **************
                    if($row['adm_logintype'] == 3)
                    {

                        $sqllms_emply	= $dblms->querylms("SELECT emply_id, emply_photo, id_class, id_section, id_campus
                                                                    FROM ".EMPLOYEES."
                                                                    WHERE id_loginid = '".$row['adm_id']."' LIMIT 1");

                        $values_emply = mysqli_fetch_array($sqllms_emply);

                        if($values_emply['emply_photo'])
                        {
                            $photo = $url."uploads/images/employees/".$values_emply['emply_photo'];
                        }
                        else
                        {
                            $photo = $url."uploads/admin_image/default.jpg";
                        }

                        $data['class'] 		    = $values_emply['id_class'];
                        $data['section'] 		= $values_emply['id_section'];
                        $data['login_for'] 		= $row['adm_logintype'];
                        $data['username'] 		= $row['adm_username'];
                        $data['fullname'] 		= $row['adm_fullname'];
                        $data['photo'] 		    = $photo;
                        $data['session'] 	    = $values_setting['session_name'];
                        $data['password'] 	    = $admin_pass1;	

                    }
                    if($row['adm_logintype'] == 4)
                    {

                    }
                    elseif($row['adm_logintype'] == 5)
                    {
                        $sqllms_std	= $dblms->querylms("SELECT std_photo
                                                                FROM ".STUDENTS."
                                                                WHERE id_loginid = '".$row['adm_id']."' LIMIT 1");

                        $values_std = mysqli_fetch_array($sqllms_std);

                        if($values_std['std_photo'])
                        {
                            $photo = $url."uploads/images/students/".$values_std['std_photo'];
                        }
                        else
                        {
                            $photo = $url."uploads/admin_image/default.jpg";
                        }

                        $data['login_for'] 		= $row['adm_logintype'];
                        $data['username'] 		= $row['adm_username'];
                        $data['fullname'] 		= $row['adm_fullname'];
                        $data['photo'] 		    = $photo;
                        $data['session'] 	    = $values_setting['session_name'];
                        $data['password'] 	    = $admin_pass1;	

                    }
                    else
                    {

                    }
                    //-------------------------------------

                        
                    
                    // $data['success'] 	= '1';
                    array_push($jsonObj,$data);

                } 
                else {
                
                    //********** admin password dosn't much *******************
                    $data['success'] 	= '-1';
                    $data['message'] 	= 'Wrong Username or Password..';
                    $jsonObj = $data;
                }
        
        } 
        else {

            //********** admin name dosn't much *******************
            $data['success'] 	= '-1';
            $data['message'] 	= 'Wrong Username or Password..';

            $jsonObj = $data;
        }	

        $set['LHIS'] = $jsonObj;

        header( 'Content-Type: application/json; charset=utf-8' );

        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        die();	
    }
    
	else
	{

		$get_method = checkSignSalt($get_method['data']);

	}
?>