<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '73', 'view' => '1'))){
//-----------------------------------------------
$sql1 = "";
$sql2 = "";
$sql3 = "";
$id_campus = "";
$class = "";
$std_gender = "";

if(isset($_POST['view_students']))
{
	//--------- FIlters ----------
	// CAMPUS
	if($_POST['id_campus']){
		$sql1 = "AND s.id_campus = '".$_POST['id_campus']."'";
		$id_campus = $_POST['id_campus'];
	}
	// CLASS
	if($_POST['id_class']){
		$sql2 = "AND s.id_class = '".$_POST['id_class']."'";
		$class = $_POST['id_class'];
	}
	// GENDER
	if($_POST['std_gender']){
		$sql3 = "AND s.std_gender = '".$_POST['std_gender']."'";
		$std_gender = $_POST['std_gender'];
	}
	// CLASS
	if($_POST['id_classlevel']){
		$sql4 = "AND c.id_classlevel = '".$_POST['id_classlevel']."'";
		$id_classlevel = $_POST['id_classlevel'];
	}
	// STATUS
	if($_POST['status'])
	{
		$sql5 = "AND s.std_status = '".$_POST['status']."'";
		$std_status = $_POST['status'];
	}
}
echo'
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-list"></i>  Select Campus</h2>
	</header>
	<form action="#" id="form" enctype="multipart/form-data" method="POST" accept-charset="utf-8">
		<div class="panel-body">
			<div class="row mb-lg">
				<div class="col-md-3">
					<label class="control-label">Campus <span class="required">*</span></label>
					<select data-plugin-selectTwo data-width="100%" name="id_campus" id="id_campus" required title="Must Be Required" class="form-control populate">
						<option value="">Select</option>';
						$sqllmscampus	= $dblms->querylms("SELECT c.campus_id, c.campus_name
															FROM ".CAMPUS." c  
															WHERE c.campus_id != '' AND campus_status = '1'
															ORDER BY c.campus_name ASC");
						while($value_campus = mysqli_fetch_array($sqllmscampus)){
							echo'<option value="'.$value_campus['campus_id'].'" '.($value_campus['campus_id']==$id_campus ? 'selected' : '').'>'.$value_campus['campus_name'].'</option>';
						}
						echo'
					</select>
				</div>
				<div class="col-md-3">
					<label class="control-label">Level </label>
					<select data-plugin-selectTwo data-width="100%" name="id_classlevel" id="id_classlevel" title="Must Be Required" class="form-control populate" onchange="get_classlevel(this.value)">
						<option value="">Select</option>';
						foreach ($classlevel as $level):
							echo'<option value="'.$level['id'].'" '.($id_classlevel==$level['id'] ? 'selected' : '').'>'.$level['name'].'</option>';
						endforeach;
						echo'
					</select>
				</div>
				<div class="col-md-2">
					<label class="control-label">Class </label>
					<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class">
						<option value="">Select</option>
						<option value="" selected>All</option>';
							$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
															FROM ".CLASSES." 
															WHERE class_status = '1'
															AND is_deleted != '1'
															ORDER BY class_id ASC");
							while($valuecls = mysqli_fetch_array($sqllmscls)) {
								echo '<option value="'.$valuecls['class_id'].'" '.($valuecls['class_id']==$class ? 'selected' : '').'>'.$valuecls['class_name'].'</option>';
							}
							echo '
					</select>
				</div>
				<div class="col-md-2">
					<label class="control-label">Gender </label>
					<select class="form-control" data-plugin-selectTwo data-width="100%" name="std_gender">
						<option value="">Select</option>';
						foreach($gender as $gndr){
							echo '<option value="'.$gndr.'" '.($std_gender==$gndr ? 'selected' : '').'>'.$gndr.'</option>';
						}
						echo'
					</select>
				</div>
				<div class="col-md-2">
					<label class="control-label">Status </label>
					<select class="form-control" data-plugin-selectTwo data-width="100%" name="status">
						<option value="">Select</option>';
						foreach($admstatus as $stat){
							echo '<option value="'.$stat['id'].'"'; if($std_status == $stat['id']){ echo'selected';} echo'>'.$stat['name'].'</option>';
						}
						echo'
					</select>
				</div>
			</div>
			<center>
				<button type="submit" name="view_students" id="view_students" class="btn btn-primary"><i class="fa fa-search"></i> Show Result</button>
			</center>
		</div>
	</form>
</section>';
//-----------------------------------------------
if(isset($_POST['view_students'])){
	echo '
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<a href="studentlistprint.php?id_class='.$class.'&id_classlevel='.$id_classlevel.'&std_gender='.$std_gender.'&std_status='.$std_status.'&id_campus='.$id_campus.'" target="_blank" class="ml-sm btn btn-primary btn-xs mr-sm pull-right"><i class="fa fa-print"></i> Print List</a>
		<h2 class="panel-title"><i class="fa fa-list"></i>  Students List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
			<thead>
				<tr>
					<th class="center" width="50">Sr No</th>
					<th width= 40>Photo</th>
					<th>Student Name</th>
					<th>Father Name</th>
					<th>Roll no</th>
					<th>Class</th>
					<th>Level</th>
					<th>Phone</th>
					<th>NIC</th>
					<th width="70px;" style="text-align:center;">Status</th>
					<th width="100px;" style="text-align:center;">Options</th>
				</tr>
			</thead>
			<tbody>';
			$sqllms	= $dblms->querylms("SELECT s.std_id, s.std_status, s.std_name, s.std_fathername, s.std_gender, s.std_nic, s.std_phone, s.id_session, s.std_rollno, s.std_regno, s.std_photo, c.class_name, c.id_classlevel
										FROM ".STUDENTS." s
										INNER JOIN ".CLASSES."  c  ON c.class_id	= s.id_class
										WHERE s.std_id != '' AND s.is_deleted != '1'
										$sql1 $sql2 $sql3 $sql4 $sql5
										ORDER BY c.id_classlevel, c.class_id ASC");
			$srno = 0;
			while($rowsvalues = mysqli_fetch_array($sqllms)){
				$srno++;
				if($rowsvalues['std_photo']){
					$photo = "uploads/images/students/".$rowsvalues['std_photo']."";
				}else{
					$photo = "uploads/default-student.jpg";
				}
				echo '
				<tr>
					<td style="text-align:center;">'.$srno.'</td>
					<td><img src="'.$photo.'" style="width:40px; height:40px;"></td>
					<td>'.$rowsvalues['std_name'].'</td>
					<td>'.$rowsvalues['std_fathername'].'</td>
					<td>'.$rowsvalues['std_rollno'].'</td>
					<td>'.$rowsvalues['class_name'].'</td>
					<td>'.get_classlevel($rowsvalues['id_classlevel']).'</td>
					<td>'.$rowsvalues['std_phone'].'</td>
					<td>'.$rowsvalues['std_nic'].'</td>
					<td style="text-align:center;">'.get_status($rowsvalues['std_status']).'</td>
					<td style="text-align:center;">
						<a class="btn btn-success btn-xs" href="students.php?id='.$rowsvalues['std_id'].'"> <i class="fa fa-user-circle-o"></i></a>
					</td>
				</tr>';
			}
			echo'
			</tbody>
		</table>
	</div>
</section>';
}
//-----------------------------------------------
}
else{
	header("Location: dashboard.php");
}
?>