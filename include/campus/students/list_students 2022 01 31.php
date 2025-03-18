<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'view' => '1'))){
echo '

<section class="panel panel-featured panel-featured-primary">
	<form action="students.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i>  Select Class</h2>
		</header>
		<div class="panel-body">
			<div class="form-group mb-md">
				<div class="col-md-4 col-md-offset-4">
					<label class="control-label">Class <span class="required">*</span></label>
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_class" required title="Must Be Required">
						<option value="">Select</option>';
							$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
												FROM ".CLASSES." 
												WHERE class_status = '1'
												ORDER BY class_id ASC");
							while($valuecls = mysqli_fetch_array($sqllmscls)) {
						echo '<option value="'.$valuecls['class_id'].'"'; if($_POST['id_class'] == $valuecls['class_id']){ echo'selected';} echo'>'.$valuecls['class_name'].'</option>';
						}
					echo '
					</select>
				</div>
			</div>
			<div class="col-md-12 text-center">
				<button type="submit" id="show_students" name="show_students" class="mr-xs btn btn-primary">Show Students</button>
			</div>
		</div>
	</form>
</section>

<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">';
	if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'add' => '1'))){
		echo '
			<a href="students.php?view=add" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Student</a>';
		}
		
		//-----------------------------------------------------
		if(isset( $_POST['show_students']))
		{
			$class_id = $_POST['id_class'];
		}
		

		if(isset($class_id))
		{
			$sql2 = "AND s.id_class = '".$class_id."' ORDER BY s.std_rollno ASC";
			//------------ PRINT CARDS ------------------------
			echo'<a href="studentcards_print.php?id_class='.$_POST['id_class'].'" target="_blank" class="btn btn-primary btn-xs pull-right mr-sm"><i class="glyphicon glyphicon-print"></i> Print Cards</a>';
		}
		else
		{
			$sql2 = "ORDER BY s.id_class ASC";
			//------------ PRINT CARDS ------------------------
			echo'<a href="studentcards_print.php" target="_blank" class="btn btn-primary btn-xs pull-right mr-sm"><i class="glyphicon glyphicon-print"></i> Print Cards</a>';
		}
		//-----------------------------------------------------
		
		echo'
		
		<h2 class="panel-title"><i class="fa fa-list"></i>  Students List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
			<thead>
				<tr>
					<th>#</th>
					<th width= 40>Photo</th>
					<th>Student Name</th>
					<th>Father Name</th>
					<th>Roll no</th>
					<th>Phone</th>
					<th>Class</th>
					<th>Section</th>
					<th width="70px;" class="center">Status</th>
					<th width="100px;" class="center">Options</th>
				</tr>
			</thead>
			<tbody>';
				//-----------------------------------------------------
				$sqllms	= $dblms->querylms("SELECT  s.std_id, s.std_status, s.std_name, s.std_fathername, s.std_gender, 
												s.std_phone, s.id_class, s.id_session,
												s.std_rollno, s.std_regno, s.std_photo, c.class_name, se.section_name
												FROM ".STUDENTS." 		s
												INNER JOIN ".CLASSES."  c  ON c.class_id = s.id_class
												LEFT JOIN ".CLASS_SECTIONS."  se  ON se.section_id = s.id_section
												WHERE s.std_id != '' AND s.is_deleted != '1'
												AND s.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' $sql2
											");
				$srno = 0;
				//-----------------------------------------------------
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
				//-----------------------------------------------------
				$srno++;
				//-----------------------------------------------------
				if($rowsvalues['std_photo']) { 
					$photo = "uploads/images/students/".$rowsvalues['std_photo']."";
				}
				else{
					$photo = "uploads/default-student.jpg";
				}
				echo '
				<tr>
					<td class="center">'.$srno.'</td>
					<td><img src="'.$photo.'" style="width:40px; height:40px;"></td>
					<td>'.$rowsvalues['std_name'].'</td>
					<td>'.$rowsvalues['std_fathername'].'</td>
					<td>'.$rowsvalues['std_rollno'].'</td>
					<td>'.$rowsvalues['std_phone'].'</td>
					<td>'.$rowsvalues['class_name'].'</td>
					<td>'.$rowsvalues['section_name'].'</td>
					<td class="center">'.get_status($rowsvalues['std_status']).'</td>
					<td class="center">
						<a class="btn btn-info btn-xs" href="student_print.php?id='.$rowsvalues['std_id'].'" target="_blank"> <i class="fa fa-print"></i></a>';
						if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'edit' => '1'))){
						echo'<a class="btn btn-success btn-xs ml-xs" href="students.php?id='.$rowsvalues['std_id'].'"> <i class="fa fa-user-circle-o"></i></a>';
						}
						if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'delete' => '1'))){
						echo'<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'students.php?deleteid='.$rowsvalues['std_id'].'\');"><i class="el el-trash"></i></a>';
						}
						echo'
					</td>
				</tr>';
				//-----------------------------------------------------
				}
				//-----------------------------------------------------
				echo '

			</tbody>
		</table>
	</div>
</section>';
}
else{
	header("Location: dashboard.php");
}
?>