<?php
echo'
<title> Subjects | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Subjects</h2>
	</header>
    <style>
        a:link {text-decoration: none;}
    </style>
    <div class="row">';
        $sqllmsemp  = $dblms->querylms("SELECT emply_id  
                                                FROM ".EMPLOYEES." 
                                                WHERE id_campus	= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                                AND id_loginid = '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' LIMIT 1");
        $value_emp = mysqli_fetch_array($sqllmsemp);
        
        $sqllmsdetail	= $dblms->querylms("SELECT t.id_section, c.class_id, c.class_name, s.subject_id, s.subject_code, s.subject_name
                                                FROM ".TIMETABEL_DETAIL." d 
                                                INNER JOIN ".TIMETABLE." t 	ON t.id = d.id_setup
                                                INNER JOIN ".CLASSES." c ON c.class_id = t.id_class
                                                INNER JOIN ".CLASS_SUBJECTS." s ON s.subject_id = d.id_subject
                                                WHERE t.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' AND t.status = '1' 
                                                AND t.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."' 
                                                AND d.id_teacher = '".$value_emp['emply_id']."' 
                                                GROUP BY s.subject_id
                                                ORDER BY t.id ASC");
        if(mysqli_num_rows($sqllmsdetail) > 0) {
            while($value_detail = mysqli_fetch_array($sqllmsdetail)){
                echo ' 
                <div class="col-md-4 col-lg-4 col-xl-3">
                    <section class="panel panel-featured panel-featured-primary" >
                        <header class="panel-heading bg-primary">
                            <a href="subject.php?id='.$value_detail['subject_id'].'">
                                <p class="text-weight-semibold mt-none text-center" style="font-size: 24px; color:#ffffff;" >'.$value_detail['class_name'].' - '.$value_detail['subject_name'].'</p>
                            </a>
                        </header>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-condensed mb-none">
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'"> Subject Info</a></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&section='.$value_detail['id_section'].'&class='.$value_detail['class_id'].'&view=announcement"> Announcement</a></td>
                                    </tr>
                                    <!--
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&section='.$value_detail['id_section'].'&class='.$value_detail['class_id'].'&view=diary"> Diary</a></td>
                                    </tr>
                                    -->
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&section='.$value_detail['id_section'].'&class='.$value_detail['class_id'].'&view=online_classes"> Online Classes</a></td>
                                    </tr>  
                                    <!--                                  
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&section='.$value_detail['id_section'].'&class='.$value_detail['class_id'].'&view=attendance"> Attendance</a></td>
                                    </tr> 
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&section='.$value_detail['id_section'].'&class='.$value_detail['class_id'].'&view=assignment"> Assignment</a></td>
                                    </tr>-->
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&class='.$value_detail['class_id'].'&view=dlp"> Syllabus DLP\'s</a></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&class='.$value_detail['class_id'].'&view=syllabus_breakdown"> Syllabus Breakdown</a></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&class='.$value_detail['class_id'].'&view=worksheet"> Worksheets</a></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&class='.$value_detail['class_id'].'&view=summer_work"> Summer Vacation Work</a></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&class='.$value_detail['class_id'].'&view=video_lctr"> Video Lectures</a></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&section='.$value_detail['id_section'].'&class='.$value_detail['class_id'].'&view=enroll"> Enrolled Students</a></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>';
            }
        }else{
			echo'
			<section class="panel panel-featured panel-featured-primary appear-animation mt-sm" data-appear-animation="fadeInRight" data-appear-animation-delay="30">
				<h2 class="panel-body text-center font-bold text text-primary mt-none">No Subject Assigned</h2>
			</div>';
        }
        echo'
    </div>
</section>';
?>