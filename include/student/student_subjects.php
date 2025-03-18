<?php
echo'
<title> Subjects | '.TITLE_HEADER.'</title>
<style>
    a:link {text-decoration: none;}
</style>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Subjects</h2>
	</header>
    <div class="row">';
        $sqllms_std	= $dblms->querylms("SELECT id_class, id_section
                                        FROM ".STUDENTS." 
                                        WHERE id_loginid = '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
                                        AND   id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                        LIMIT 1");
        $values_std = mysqli_fetch_array($sqllms_std);

        $sqllmsdetail	= $dblms->querylms("SELECT *
                                                FROM ".CLASSES." c 
                                                INNER JOIN ".CLASS_SUBJECTS." s ON s.id_class = c.class_id
                                                WHERE c.class_id = '".$values_std['id_class']."' AND s.subject_status = '1' 
                                                ");
        if(mysqli_num_rows($sqllmsdetail) > 0){
            while($value_detail = mysqli_fetch_array($sqllmsdetail)){
                echo'
                <div class="col-md-4 col-lg-4 col-xl-3">
                    <section class="panel panel-featured panel-featured-primary" >
                        <header class="panel-heading bg-primary">
                            <a href="subject.php?id='.$value_detail['subject_id'].'">
                                <p class="text-weight-semibold mt-none text-center" style="font-size: 24px; color:#ffffff;" >'.$value_detail['subject_code'].' - '.$value_detail['subject_name'].'</p>
                            </a>
                        </header>
                        <div class="panel-body">   
                            <div class="table-responsive">
                                <table class="table table-striped table-condensed mb-none">
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'"> Subject Info</a></td>
                                    </tr>
                                    
                                    <tr>
                                    <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&section='.$values_std['id_section'].'&class='.$value_detail['class_id'].'&view=announcement"> Announcement</a></td>
                                    </tr>
                                    <!-- <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&section='.$values_std['id_section'].'&class='.$value_detail['class_id'].'&view=attendance"> Attendance</a></td>
                                    </tr>-->
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&section='.$values_std['id_section'].'&class='.$value_detail['class_id'].'&view=online_classes"> Online Classes</a></td>
                                    </tr>
                                    
                                    <!-- 
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&section='.$values_std['id_section'].'&class='.$values_std['id_class'].'&view=assignment"> Assignment</a></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&class='.$values_std['id_class'].'&view=dlp"> Syllabus DLP\'s</a></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&class='.$values_std['id_class'].'&view=syllabus_breakdown"> Syllabus Breakdown</a></td>
                                    </tr> --->
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&class='.$values_std['id_class'].'&view=worksheet"> Worksheets</a></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&class='.$values_std['id_class'].'&view=video_lctr"> Video Lectures</a></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-dot-circle-o"></i><a href="subject.php?id='.$value_detail['subject_id'].'&class='.$values_std['id_class'].'&view=summer_work"> Summer Vacation Work</a></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>';
            }
        }else{
            echo'<h2>Not Enrolled In Any Course</h2>';
        }
        echo'
    </div>
</section>';
?>