<?php 
$sqllms	= $dblms->querylms("SELECT a.adm_photo, s.std_id, s.std_status, s.std_name, s.std_fathername, s.std_gender, s.id_guardian,
                                s.std_nic, s.std_phone, s.std_whatsapp, s.id_class, s.id_section, s.id_group, s.id_session,  s.std_rollno,
                                s.std_regno, s.std_photo, s.std_gender, s.std_dob, s.std_bloodgroup, s.id_country,
                                s.std_city, s.std_religion, s.std_c_address, s.std_admissiondate,
                                g.guardian_id, g.guardian_status, g.guardian_name,
                                c.class_id, c.class_status, c.class_name,
                                se.section_id, se.section_status, se.section_name, 
                                gr.group_id, gr.group_status, gr.group_name ,
                                sn.session_name
                            FROM ".ADMINS." a
                            INNER JOIN ".STUDENTS."         s  ON s.id_loginid  = a.adm_id
                            INNER JOIN ".CLASSES."			c  ON c.class_id    = s.id_class
                            LEFT JOIN ".CLASS_SECTIONS."	se ON se.section_id = s.id_section
                            LEFT JOIN ".SESSIONS."  		sn ON sn.session_id = s.id_session
                            LEFT JOIN ".GUARDIANS."			g  ON g.guardian_id = s.id_guardian
                            LEFT JOIN ".GROUPS."			gr ON gr.group_id    = s.id_group
                            WHERE s.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                            AND a.adm_id = '".$_SESSION['userlogininfo']['LOGINIDA']."' LIMIT 1");
$rowsvalues = mysqli_fetch_array($sqllms);
echo '
<div class="col-md-4">
	<section class="panel">
		<div class="panel-body">
			<div class="thumb-info mb-md">';
                if($rowsvalues['adm_photo']) { 
                    echo'<img src="uploads/images/admins/'.$rowsvalues['adm_photo'].'" class="rounded img-responsive">' ;
                }else{
                    echo'<img src="uploads/default-student.jpg" class="rounded img-responsive">';
                }
                echo'
				<div class="thumb-info-title">
					<span class="thumb-info-inner">'.$rowsvalues['std_name'].'</span>
					<span class="">'.get_status($rowsvalues['std_status']).'</span>
				</div>
			</div>	
			<div class="widget-toggle-expand mb-xs">
				<div class="widget-content-expanded">
                    <table class="table table-striped table-condensed mb-none">
                        <tr>
                            <td>Student Name</td>
                            <td align="right">'.$rowsvalues['std_name'].'</td>
                        </tr>
                        <tr>
                            <td>Father Name</td>
                            <td align="right">'.$rowsvalues['std_fathername'].'</td>
                        </tr>
                        <tr>
                            <td>Roll No</td>
                            <td align="right">'.$rowsvalues['std_rollno'].'</td>
                        </tr>
                        <tr>
                            <td>Registration Number</td>
                            <td align="right">'.$rowsvalues['std_regno'].'</td>
                        </tr>
                        <tr>
                            <td>Class</td>
                            <td align="right">'.$rowsvalues['class_name'].'</td>
                        </tr>
                        <tr>
                            <td>Section</td>
                            <td align="right">'.$rowsvalues['section_name'].'</td>
                        </tr>
                        <tr>
                            <td>Session</td>
                            <td align="right">'.$rowsvalues['session_name'].'</td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td align="right">'.$rowsvalues['std_phone'].'</td>
                        </tr>
                        <tr>
                            <td>Whatsapp</td>
                            <td align="right">'.$rowsvalues['std_whatsapp'].'</td>
                        </tr>
                        <tr>
                            <td>Gender</td>
                            <td align="right">'.$rowsvalues['std_gender'].'</td>
                        </tr>
                        <tr>
                            <td>Blood Group</td>
                            <td align="right">'.$rowsvalues['std_bloodgroup'].'</td>
                        </tr>
                        <tr>
                            <td>Birthday</td>
                            <td align="right">'.$rowsvalues['std_dob'].'</td>
                        </tr>
                        <tr>
                            <td>NIC</td>
                            <td align="right">'.$rowsvalues['std_nic'].'</td>
                        </tr>
                        <tr>
                            <td>Religion</td>
                            <td align="right">'.$rowsvalues['std_religion'].'</td>
                        </tr>
                        <tr>
                            <td>Admission Date</td>
                            <td align="right">'.$rowsvalues['std_admissiondate'].'</td>
                        </tr>
                        <tr>
                            <td>Guardian</td>
                            <td align="right">'.$rowsvalues['guardian_name'].'</td>
                        </tr>
                        <tr>
                            <td>City</td>
                            <td align="right">'.$rowsvalues['std_city'].'</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td align="right">'.$rowsvalues['std_c_address'].'</td>
                        </tr>
                    </table>
				</div>
			</div>
		</div>
	</section>
</div>';
?>
