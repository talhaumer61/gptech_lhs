<?php 
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();

// CAMPUS DETAILS
$sqllmscampus  = $dblms->querylms("SELECT campus_name
									FROM ".CAMPUS." 
									WHERE campus_status = '1' AND campus_id = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1");
$value_campus = mysqli_fetch_array($sqllmscampus);

if(isset($_GET['id'])) {

    //SPECIFIC CLASS TIMETABLE
    $sqllms	= $dblms->querylms("SELECT t.id, t.status, t.id_session, t.id_class, t.id_section, c.class_name, se.section_name
                                    FROM ".TIMETABLE." t
                                    INNER JOIN ".CLASSES."  	 	 c 	ON 	c.class_id 		= t.id_class
                                    INNER JOIN ".CLASS_SECTIONS." se	ON 	se.section_id 	= t.id_section
                                    WHERE t.id = '".$_GET['id']."' AND t.is_deleted != '1'
                                    AND t.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
                                    AND t.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                    LIMIT 1");
    $rowsvalues = mysqli_fetch_array($sqllms);
    $timetableFor = " ".$rowsvalues['class_name']." (".$rowsvalues['section_name'].")";
    $checks = "AND td.id_setup = ".$rowsvalues['id']."";

} else if(isset($_GET['id_teacher'])) {

    // TIMETABLE OF TEACHER
    $sqllms	= $dblms->querylms("SELECT emply_id, emply_name
                                    FROM ".EMPLOYEES." 
                                    WHERE emply_id = '".$_GET['id_teacher']."' AND is_deleted != '1' 
                                    AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                    LIMIT 1");
    $rowsvalues = mysqli_fetch_array($sqllms);
    $timetableFor = "Timetable of ".$rowsvalues['emply_name']." ";
    $checks = "AND td.id_teacher = '".$_GET['id_teacher']."'";

} else if(isset($_GET['teachers'])) {

    // ALL TEACHER TIMETABLE
    $sqllmsEmply = $dblms->querylms("SELECT emply_id, emply_name
                                            FROM ".EMPLOYEES." 
                                            WHERE emply_status = '1' AND id_type = '1' AND is_deleted != '1' 
                                            AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                            ORDER BY emply_name");

    // Count Total Lectures Of Login Campus                                            
    $sqllmsTotalPeriods = $dblms->querylms("SELECT COUNT(period_id) as totlaperiods
                                            FROM ".PERIODS." 
                                            WHERE period_status = '1' AND is_deleted != '1' 
                                            AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' ");
    $valPeriods = mysqli_fetch_array($sqllmsTotalPeriods);

    $timetableFor = "Teacher's Free Timetable";
    $checks = "";

} else if(isset($_GET['subjectcat'])) {

    $timetableFor = "Allocation Of Lectures Subject Wise";
    $checks = "";

} else if(isset($_GET['teacher_subjectcat'])) {

    $timetableFor = "Allocation Of Lectures Subject Wise";
    $checks = "";

} else if(isset($_GET['id_day'])) {

    // TIMETABLE OF DAY OR WEEK
    if($_GET['id_day'] == 0) {
        $timetableFor = "Timetable of Week";
        $day = 'for($i=0; $i<7; $i++;)';
    } else {
        $timetableFor = "Timetable of  ".get_daytypes($_GET['id_day'])." ";
        $checks = "";
        $day = "if(1 = 1)";
    }

} else {

    $timetableFor = " ".$value_campus['campus_name']."";
    $checks = "";

}

echo'
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>'.$timetableFor.'</title>
        <style type="text/css">
            body {overflow: -moz-scrollbars-vertical; margin:0; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light";  }
            body .btn-primary {
                color: #ffffff;
                text-shadow: 0 -1px 0 rgb(0 0 0 / 25%);
                background-color: #cb3f44;
                border-color: #cb3f44;
            }
            body .btn {
                white-space: normal;
            }
            .ml-sm {
                margin-left: 10px !important;
            }
            .mb-xs {
                margin-bottom: 5px !important;
            }
            .pull-right {
                float: right !important;
            }
            .btn {
                margin-right:20px;
                margin-top:20px;
                display: inline-block;
                padding: 6px 12px;
                font-size: 14px;
                font-weight: normal;
                line-height: 1.42857143;
                text-align: center;
                vertical-align: middle;
                touch-action: manipulation;
                cursor: pointer;
                user-select: none;
                background-image: none;
                border: 1px solid transparent;
                border-radius: 4px;
            }
            @media all {
                .page-break	{ display: none; }
            }

            @media print {
                .page-break	{ display: block; page-break-before: always; }
                @page { 
                    
                margin: 4mm 4mm 4mm 4mm; 
                }
                #printPageButton {
                    display: none;
                }
            }
            h1 { text-align:left; margin:0; margin-top:0; margin-bottom:0px; font-size:26px; font-weight:700; text-transform:uppercase; }
            .spanh1 { font-size:14px; font-weight:normal; text-transform:none; text-align:right; float:right; margin-top:10px; }
            h2 { text-align:left; margin:0; margin-top:0; margin-bottom:1px; font-size:24px; font-weight:700; text-transform:uppercase; }
            .spanh2 { font-size:20px; font-weight:700; text-transform:none; }
            h3 { text-align:center; margin:0; margin-top:0; margin-bottom:1px; font-size:18px; font-weight:700; text-transform:uppercase; }
            h4 { 
                text-align:center; margin:0; margin-bottom:1px; font-weight:normal; font-size:15px; font-weight:700; word-spacing:0.1em;  
            }
            td { padding-bottom:4px; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light"; }
            .line1 { border:1px solid #333; width:100%; margin-top:2px; margin-bottom:5px; }
            .payable { border:2px solid #000; padding:2px; text-align:center; font-size:14px; }

            .paid:after
            {
                content:"PAID";
                
                position:absolute;
                top:30%;
                left:20%;
                z-index:1;
                font-family:Arial,sans-serif;
                -webkit-transform: rotate(-5deg); /* Safari */
                -moz-transform: rotate(-5deg); /* Firefox */
                -ms-transform: rotate(-5deg); /* IE */
                -o-transform: rotate(-5deg); /* Opera */
                transform: rotate(-5deg);
                font-size:250px;
                color:green;
                background:#fff;
                border:solid 4px yellow;
                padding:5px;
                border-radius:5px;
                zoom:1;
                filter:alpha(opacity=50);
                opacity:0.1;
                -webkit-text-shadow: 0 0 2px #c00;
                text-shadow: 0 0 2px #c00;
                box-shadow: 0 0 2px #c00;
            }
        </style>
        <link rel="shortcut icon" href="images/favicon/favicon.ico">
    </head>
    <body>
        <table width="99%" border="0" class="page " cellpadding="10" cellspacing="15" align="center" style="border-collapse:collapse; margin-top:0px;">
            <tr>
                <td width="341" valign="top">
                    <div class="row">
                        <h2 style="text-align:center;">
                            <img src="uploads/logo.png" class="img-fluid" style="width: 50px; height: 50px; vertical-align: middle;">
                            '.SCHOOL_NAME.'<span style="font-size: 18px;">('.$value_campus['campus_name'].')</span>
                        </h2>
                        <h4>'.$timetableFor.'</h4>
                    </div>';
                    if(isset($_GET['id']) || isset($_GET['id_teacher'])) {

                        // Timetable For Teacher

                        echo'<div class="line1"></div>
                            <div style="font-size:12px; margin-top:5px;">
                                <table style="width:100%; border-collapse:collapse; margin-top: 10px;" border="1">
                                <tbody>				
                                    <tr>
                                        <th style="text-align: center; padding: 5px;"> Days | Lectures </th>';
                                        //-----------------------------------------
                                        $sqllmssub	= $dblms->querylms("SELECT p.period_id, p.period_name
                                                                        FROM ".PERIODS." p 
                                                                        INNER JOIN ".TIMETABEL_DETAIL." td ON td.id_period	= p.period_id
                                                                        WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' $checks
                                                                        GROUP BY td.id_period
                                                                        ORDER BY p.period_ordering");
                                        //-----------------------------------------------------
                                        $periods = array();
                                        while($rowsub = mysqli_fetch_array($sqllmssub)){ 
                                        $periods[] = $rowsub;
                                        echo'
                                        <th style="text-align: center; padding: 6px;">'.$rowsub['period_name'].'</th>';}
                                        echo'
                                    </tr>
                                    <tr>';
                                    for($i=1; $i<=6; $i++){
                                        echo'<th class="center">'. get_daytypes($i).'</th>';
                                        $sqlTimetable = $dblms->querylms("SELECT p.period_id, p.period_name, td.*,
                                                                            t.id, t.status, t.id_session, t.id_class, t.id_section, t.id_campus,
                                                                            c.class_id, c.class_status, c.class_name,
                                                                            se.section_id, se.section_status, se.section_name, 
                                                                            s.subject_id, s.subject_status, s.subject_name,
                                                                            r.room_id, r.room_status, r.room_no, r.room_capacity,
                                                                            e.emply_id, e.emply_status, e.emply_name, e.id_type
                                                                            FROM ".PERIODS." p 
                                                                            INNER JOIN ".TIMETABEL_DETAIL." td ON td.id_period	= p.period_id																
                                                                            INNER JOIN ".TIMETABLE."  	 t 	ON 	t.id 			= td.id_setup AND t.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' AND t.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."' AND t.is_deleted = '0'  
                                                                            LEFT JOIN ".CLASSES."  	 	 c 	ON 	c.class_id 		= t.id_class
                                                                            LEFT JOIN ".CLASS_SECTIONS." se	ON 	se.section_id 	= t.id_section
                                                                            LEFT JOIN ".CLASS_SUBJECTS." s 	ON 	s.subject_id 	= td.id_subject
                                                                            LEFT JOIN ".CLASS_ROOMS."    r 	ON 	r.room_id 		= td.id_room
                                                                            LEFT JOIN ".EMPLOYEES." 	 e 	ON 	e.emply_id 		= td.id_teacher
                                                                            WHERE p.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                                                                            AND td.day = '".$i."'
                                                                            $checks
                                                                            GROUP BY td.id_period, td.day
                                                                            ORDER BY p.period_ordering
                                                                        ");
                                        while($valTimetable = mysqli_fetch_array($sqlTimetable)){
                                            if(isset($_GET['id'])){
                                                $details = "<br>Teacher : ".$valTimetable['emply_name']."";
                                            }
                                            elseif(isset($_GET['id_teacher'])){
                                                $details = '<br>Class : '.$valTimetable['class_name'].' ('.$valTimetable['section_name'].')';
                                            }
                                            else{
                                                $details = "<br>Teacher : ".$valTimetable['emply_name']."";
                                                $details = '<br>Class : '.$valTimetable['class_name'].' ('.$valTimetable['section_name'].')'; 
                                            }
                                            echo'					
                                            <td style="text-align:center;">
                                                <div class="btn-group">
                                                    <div style="float: center; padding:5px;">
                                                        '.($valTimetable['id_subject'] == '99999' ? 'Assembly' : ($valTimetable['id_subject'] == '99998' ? 'Break' : $valTimetable['subject_name'])).'
                                                        <br>('.$valTimetable['start_time'].' - '.$valTimetable['end_time'].')
                                                        <br>'.($valTimetable['id_subject'] == '99999' || $valTimetable['id_subject'] == '99998' ? '' : 'Room : '.$valTimetable['room_no'].'').'
                                                        '.($valTimetable['id_subject'] == '99999' || $valTimetable['id_subject'] == '99998' ? '' : $details).'
                                                    </div>
                                                </div>
                                            </td>';
                                        }
                                        echo'</tr>';
                                    }
                                    echo'
                                    </tbody>
                                </table>
                            </div>
                        </div>';
                    } else if(isset($_GET['teachers'])) {

                        // Teacher Free Timetable

                        echo'
                        <div class="line1"></div>
                            <div style="font-size:12px; margin-top:5px;">
                                <table style="width:100%; border-collapse:collapse; margin-top: 10px;" border="1">
                                    <tbody>				
                                        <tr>
                                            <th>Sr No.</th>
                                            <th><b>Teachers | Days </b></th>';
                                            for($i=1; $i<=6; $i++) {
                                                echo'<th style="text-align: center; padding: 6px;">'.get_daytypes($i).'</th>';
                                            }
                                            echo'
                                            <th>Total</th>
                                        </tr>';

                                        $srno = 0;

                                        while($valEmply = mysqli_fetch_array($sqllmsEmply)) {
                                            echo'
                                            <tr>';
                                                $srno++;
                                                $totalPeriod = 0;
                                                echo'
                                                <th>'.$srno.'</th>
                                                <td>'.$valEmply['emply_name'].'</td>';

                                                for($i=1; $i<=6; $i++) {
                                                    // Check The Free Periods
                                                    $sqllmsCount = $dblms->querylms("SELECT COUNT(d.id) as periods
                                                                                            FROM ".TIMETABLE."  t
                                                                                            INNER JOIN ".TIMETABEL_DETAIL." d ON d.id_setup	= t.id
                                                                                            WHERE t.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
                                                                                            AND t.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
                                                                                            AND d.day = '".$i."' AND d.id_teacher = '".$valEmply['emply_id']."'");
                                                    $rowsCount   = mysqli_fetch_array($sqllmsCount);
                                                    $freePeriods = $valPeriods['totlaperiods'] - $rowsCount['periods'];
                                                    $totalPeriod = $totalPeriod + $freePeriods;
                                                    echo '					
                                                    <td style="text-align:center;">
                                                        <div class="btn-group">'.$freePeriods.'</div>
                                                    </td>';
                                                }
                                                echo'
                                                <td style="text-align:center;">'.$totalPeriod.'</td>
                                            </tr>';
                                        }
                                        echo'
                                    </tbody>
                                </table>
                            </div>
                        </div>';
                    } else if(isset($_GET['id_day'])) {

                        // Timetable According To Day
                        for($i=1; $i<7; $i++) {

                            if($_GET['id_day'] == 0) {
                                $day = $i;
                                $title = '<h4 style="text-align: center; padding: 5px;">'.get_daytypes($day).'</h4>';
                            } else {
                                $day = cleanvars($_GET['id_day']);
                                $title = '';
                            }

                            echo'
                                <div style="font-size:12px; margin-top:5px;">
                                    <table style="width:100%; border-collapse:collapse; margin-top: 10px;" border="1">
                                    <tbody>	
                                        '.$title.'		
                                        <div class="line1"></div>	
                                        <tr>
                                            <th style="text-align: center; padding: 5px;"> Classes | Lectures </th>';
                                            //-----------------------------------------
                                            $sqllmssub	= $dblms->querylms("SELECT 
                                                                                period_id, period_name, period_timestart, period_timeend
                                                                                FROM ".PERIODS."
                                                                                WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                                                                ORDER BY period_ordering");
                                            //-----------------------------------------------------
                                            $periods = array();
                                            while($rowsub = mysqli_fetch_array($sqllmssub)){ 
                                            $periods[] = $rowsub;
                                            echo'
                                            <th style="text-align: center; padding: 6px;">'.$rowsub['period_name'].'</th>';}
                                            echo'
                                        </tr>
                                        <tr>';
                                        
                                        $sqllmsClasses = $dblms->querylms("SELECT c.class_id, c.class_name, c.class_code, se.section_id, se.section_name 
                                                                                FROM ".CLASSES." c  
                                                                                INNER JOIN ".CLASS_SECTIONS." se ON se.id_class = c.class_id
                                                                                WHERE c.class_id != '' AND c.is_deleted != '1' AND se.is_deleted != '1'
                                                                                AND se.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                                                                                ORDER BY c.class_id ASC");
                                        while($valueClasses = mysqli_fetch_array($sqllmsClasses)) {
                                            echo'<th>'.$valueClasses['class_name'].'('.$valueClasses['section_name'].')</th>';
                                            //-----------------------------------------------------
                                            foreach($periods as $itemperiod) { 
                                                $sqllmsdetail = $dblms->querylms("SELECT p.period_id, p.period_name, td.*,
                                                                                    t.id, t.status, t.id_session, t.id_class, t.id_section, t.id_campus,
                                                                                    c.class_id, c.class_status, c.class_name,
                                                                                    se.section_id, se.section_status, se.section_name, 
                                                                                    s.subject_id, s.subject_status, s.subject_name,
                                                                                    r.room_id, r.room_status, r.room_no, r.room_capacity,
                                                                                    e.emply_id, e.emply_status, e.emply_name, e.id_type
                                                                                    FROM ".PERIODS." p 
                                                                                    INNER JOIN ".TIMETABEL_DETAIL." td ON td.id_period	= p.period_id																
                                                                                    INNER JOIN ".TIMETABLE."  	 t 	ON 	t.id 			= td.id_setup AND t.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' AND t.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'  
                                                                                    LEFT JOIN ".CLASSES."  	 	 c 	ON 	c.class_id 		= t.id_class
                                                                                    LEFT JOIN ".CLASS_SECTIONS." se	ON 	se.section_id 	= t.id_section
                                                                                    LEFT JOIN ".CLASS_SUBJECTS." s 	ON 	s.subject_id 	= td.id_subject
                                                                                    LEFT JOIN ".CLASS_ROOMS."    r 	ON 	r.room_id 		= td.id_room
                                                                                    LEFT JOIN ".EMPLOYEES." 	 e 	ON 	e.emply_id 		= td.id_teacher
                                                                                    WHERE p.id_campus   = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                                                                                    AND t.id_class      = '".$valueClasses['class_id']."'
                                                                                    AND t.id_section    = '".$valueClasses['section_id']."'
                                                                                    AND td.id_period    = '".$itemperiod['period_id']."'
                                                                                    AND td.day          = '".$day."'
                                                                                    AND t.is_deleted    = '0'
                                                                                    GROUP BY td.id_period, td.day
                                                                                    ORDER BY p.period_ordering LIMIT 1
                                                                                ");
                                                //----------------------------------------------------- 
                                                // $sqllmsdetail	= $dblms->querylms("SELECT d.id, d.day, d.start_time, d.end_time, c.class_name, se.section_name, s.subject_name, r.room_no,  e.emply_name
                                                //                                         FROM ".TIMETABEL_DETAIL." 	 d 
                                                //                                         INNER JOIN ".TIMETABLE."  	 t 	ON 	t.id 			= d.id_setup
                                                //                                         INNER JOIN ".CLASSES."  	 	 c 	ON 	c.class_id 		= t.id_class
                                                //                                         INNER JOIN ".CLASS_SECTIONS." se	ON 	se.section_id 	= t.id_section
                                                //                                         INNER JOIN ".CLASS_SUBJECTS." s 	ON 	s.subject_id 	= d.id_subject
                                                //                                         INNER JOIN ".CLASS_ROOMS."    r 	ON 	r.room_id 		= d.id_room
                                                //                                         INNER JOIN ".EMPLOYEES." 	 e 	ON 	e.emply_id 		= d.id_teacher
                                                //                                         WHERE t.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
                                                //                                         AND t.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'  
                                                //                                         AND t.id_class = '".$valueClasses['class_id']."' AND t.id_section = '".$valueClasses['section_id']."'
                                                //                                         AND d.id_period = '".$itemperiod['period_id']."' AND d.day = '".$day."'
                                                //                                         AND t.is_deleted = '0'
                                                //                                         LIMIT 1");
                                                $rowsdetail = mysqli_fetch_array($sqllmsdetail);
                                                //----------------------------------------------------- 
                                                if(isset($_GET['id'])){
                                                    $details = "<br>Teacher : ".$rowsdetail['emply_name']."";
                                                }
                                                elseif(isset($_GET['id_teacher'])){
                                                    $details = '<br>Class : '.$rowsdetail['class_name'].' ('.$rowsdetail['section_name'].')';
                                                }
                                                else{
                                                    $details = "<br>Teacher : ".$rowsdetail['emply_name']."";
                                                    $details = '<br>Class : '.$rowsdetail['class_name'].' ('.$rowsdetail['section_name'].')'; 
                                                }
                                                echo '					
                                                <td style="text-align:center;">
                                                    <div class="btn-group">';
                                                        if(mysqli_num_rows($sqllmsdetail) > 0){
                                                            echo'
                                                            <div style="float: center; padding:5px;">
                                                                '.$rowsdetail['subject_name'].'
                                                                <br>('.$rowsdetail['start_time'].' - '.$rowsdetail['end_time'].')
                                                                <br>Room : '.$rowsdetail['room_no'].'
                                                                <br>Teacher : '.$rowsdetail['emply_name'].'
                                                            </div>';
                                                        }
                                                        echo '
                                                    </div>
                                                </td>';
                                            }
                                            echo'
                                        </tr>';
                                        }
                                        echo'
                                        </tbody>
                                    </table>
                                </div>
                            </div>';

                            if($_GET['id_day'] == 0) {
                                continue;
                            } else {
                                exit();
                            }
                        }
                    } else if(isset($_GET['subjectcat'])) {

                        // Timetable According To Subject Category
                        echo'
                        <div style="font-size:12px; margin-top:5px;">
                            <table style="width:100%; border-collapse:collapse; margin-top: 10px;" border="1">
                            <tbody>	
                                '.$title.'		
                                <div class="line1"></div>	
                                <tr>
                                    <th>Sr No.</th>
                                    <th style="text-align: center; padding: 5px;"> Classes | Lectures </th>';
                                    // All Classes
                                    $sqllmsClasses = $dblms->querylms("SELECT c.class_id, c.class_name, se.section_id, se.section_name 
                                                                        FROM ".CLASSES." c  
                                                                        INNER JOIN ".CLASS_SECTIONS." se ON se.id_class = c.class_id
                                                                        WHERE c.class_id != '' AND c.is_deleted != '1' AND se.is_deleted != '1'
                                                                        AND se.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                                                                        ORDER BY c.class_id ASC");
                                    $classes = array();
                                    while($valClass = mysqli_fetch_array($sqllmsClasses)){ 
                                        $classes[] = $valClass;
                                        echo'<th style="text-align: center; padding: 6px;">'.$valClass['class_name'].'('.$valClass['section_name'].')</th>';
                                    }
                                    echo'
                                    <th>Total</th>
                                </tr>
                                <tr>';
                                $srno = 0;
                                foreach($subjectcat as $cat){
                                    $totalPeriods = 0;
                                    $srno++;
                                    echo'
                                    <th>'.$srno.'</th>
                                    <th>'.$cat['name'].'</th>';

                                    foreach($classes as $class) { 
                                        $sqllmsdetail	= $dblms->querylms("SELECT COUNT(d.id) as total_periods
                                                                                FROM ".TIMETABEL_DETAIL." 	  d 
                                                                                INNER JOIN ".TIMETABLE."  	  t 	ON 	t.id 			= d.id_setup
                                                                                INNER JOIN ".CLASS_SUBJECTS." s 	ON 	s.subject_id 	= d.id_subject
                                                                                WHERE t.id_class = '".$class['class_id']."' AND t.id_section = '".$class['section_id']."'
                                                                                AND s.id_cat = '".$cat['id']."' 
                                                                                AND t.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
                                                                                AND t.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' ");
                                        $rowsdetail = mysqli_fetch_array($sqllmsdetail);

                                        echo'<td style="text-align:center;">'.$rowsdetail['total_periods'].'</td>';
                                        
                                        $totalPeriods = $totalPeriods + $rowsdetail['total_periods'];
                                    }
                                    echo'
                                    <td style="text-align:center; font-weight: bold;">'.$totalPeriods.'</td>
                                </tr>';
                                }
                                echo'
                                </tbody>
                            </table>
                        </div>';

                    } else if(isset($_GET['teacher_subjectcat'])) {

                        // Teacher Timetable According To Subject Category
                        echo'
                        <div style="font-size:12px; margin-top:5px;">
                            <table style="width:100%; border-collapse:collapse; margin-top: 10px;" border="1">
                            <tbody>	
                                '.$title.'		
                                <div class="line1"></div>	
                                <tr>
                                    <th>Sr No.</th>
                                    <th style="text-align: center; padding: 5px;"> Classes | Lectures </th>';
                                    // All Classes
                                    $sqllmsClasses = $dblms->querylms("SELECT c.class_id, c.class_name, se.section_id, se.section_name 
                                                                        FROM ".CLASSES." c  
                                                                        INNER JOIN ".CLASS_SECTIONS." se ON se.id_class = c.class_id
                                                                        WHERE c.class_id != '' AND c.is_deleted != '1' AND se.is_deleted != '1'
                                                                        AND se.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                                                                        ORDER BY c.class_id ASC");
                                    $classes = array();
                                    while($valClass = mysqli_fetch_array($sqllmsClasses)){ 
                                        $classes[] = $valClass;
                                        echo'<th style="text-align: center; padding: 6px;">'.$valClass['class_name'].'('.$valClass['section_name'].')</th>';
                                    }
                                    echo'
                                </tr>
                                <tr>';
                                $srno = 0;
                                foreach($subjectcat as $cat){
                                    $srno++;
                                    echo'
                                    <th>'.$srno.'</th>
                                    <th>'.$cat['name'].'</th>';

                                    foreach($classes as $class) { 
                                        $sqllmsdetail	= $dblms->querylms("SELECT COUNT(d.id) as total_periods
                                                                                FROM ".TIMETABEL_DETAIL." 	  d 
                                                                                INNER JOIN ".TIMETABLE."  	  t 	ON 	t.id 			= d.id_setup
                                                                                INNER JOIN ".CLASS_SUBJECTS." s 	ON 	s.subject_id 	= d.id_subject
                                                                                WHERE t.id_class = '".$class['class_id']."' AND t.id_section = '".$class['section_id']."'
                                                                                AND s.id_cat = '".$cat['id']."' 
                                                                                AND t.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
                                                                                AND t.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' ");
                                        $rowsdetail = mysqli_fetch_array($sqllmsdetail);

                                        echo'<td style="text-align:center;">'.$rowsdetail['total_periods'].'</td>';
                                    }
                                    echo'
                                </tr>';
                                }
                                echo'
                                </tbody>
                            </table>
                        </div>';
                        
                    } else {
                        echo'<h3 style="color: red; text-align: center; margin-top: 20px;">No Record Found</h3>';
                    }
                    echo'
                </td>
            </tr>
        </table>
        <button type="button" id="printPageButton" onClick="window.print();" class="modal-with-move-anim ml-sm mb-xs btn btn-primary btn-xs pull-right">Print</button>
    </body>
</html>';
?>