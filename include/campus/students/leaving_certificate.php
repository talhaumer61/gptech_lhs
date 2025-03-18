<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'view' => '1'))){
	$id_campus = $_SESSION['userlogininfo']['LOGINCAMPUS'];

	$val2 = (isset($_POST['id_classlevel'])? $_POST['id_classlevel']: '');
	$val1 = (isset($_POST['search_word'])? $_POST['search_word']: '');
	$val3 = (isset($_POST['id_class'])? $_POST['id_class']: '');
	$val4 = (isset($_POST['duringclass'])? $_POST['duringclass']: '');
	$val5 = (isset($_POST['std_gender'])? $_POST['std_gender']: '');
	$val6 = (isset($_POST['status'])? $_POST['status']: '');

	// VARIABLES
	$sql1 = "";
	$sql2 = "";
	$sql3 = "";
	$sql4 = "";
	$sql5 = "";
	$class = "";
	$std_status = "";
	$std_gender = "";
	$search_word = "";
	$filters = "";

	// FILTERS
	if(isset($_POST['show_students'])){
		// GENDER
		if($_POST['std_gender']){
			$std_gender = $_POST['std_gender'];
			$sql1 = "AND s.std_gender = '".$std_gender."'";
		}
		// CLASS
		if($_POST['id_class']){
			$class = $_POST['id_class'];
			$sql2 = "AND s.id_class = '".$class."'";
		}
		// STATUS
		if($_POST['status']){
			$std_status = $_POST['status'];
			$sql3 = "AND s.std_status = '".$std_status."'";
		}
		// SEARCH WORDS
		if($_POST['search_word']){
			$search_word = $_POST['search_word'];
			$sql4 = "AND (s.std_name LIKE '%".$search_word."%' OR s.std_fathername LIKE '%".$search_word."%' OR s.std_regno LIKE '%".$search_word."%')";
		}
		// ID_CLASSLEVEL
		if($_POST['id_classlevel']){
			$id_classlevel = $_POST['id_classlevel'];
			$sql5 = "AND c.id_classlevel = '".$id_classlevel."'";
		}
	}
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i>  Select Filter</h2>
		</header>
		<div class="panel-body">
			<form action="#" method="post" autocomplete="off">
				<div class="row form-group">
					<div class="col-md-4">
						<label class="control-label">Search </label>
						<div class="form-group">
							<input type="search" name="search_word" id="search_word" class="form-control" value="'.$search_word.'" placeholder="student name or registration number or father name" aria-controls="table_export" value="'.(isset($val1) ? $val1: '').'">
						</div>
					</div>
					<div class="col-md-4">
						<label class="control-label">Level </label>
						<select data-plugin-selectTwo data-width="100%" name="id_classlevel" id="id_classlevel" title="Must Be Required" class="form-control populate" onchange="get_classlevel(this.value)">
							<option value="">Select</option>';
							foreach ($classlevel as $level):
								echo'<option value="'.$level['id'].'" '.($val2 == $level['id'] ? 'selected' : '').'>'.$level['name'].'</option>';
							endforeach;
							echo'
						</select>
					</div>
					<div class="col-md-4">
						<label class="control-label">Class </label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class">
							<option value="">Select</option>';
								$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
													FROM ".CLASSES." 
													WHERE class_status = '1'
													AND is_deleted != '1'
													ORDER BY class_id ASC");
								while($valuecls = mysqli_fetch_array($sqllmscls)) {
									echo '<option value="'.$valuecls['class_id'].'" '.($val3 == $valuecls['class_id']? 'selected': '').'>'.$valuecls['class_name'].'</option>';
								}
								echo '
						</select>
					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-4">
						<label class="control-label">During Class <span class="required">*</span></label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" id="duringclass" name="duringclass" required title="Must Be Required">
							<option value="">Select</option>';
								foreach(get_duringclass() as $key => $val):
									echo '<option value="'.$key.'" '.($val4 == $key? 'selected': '').'>'.$val.'</option>';
								endforeach;
								echo '
						</select>
					</div>
					<div class="col-md-4">
						<label class="control-label">Gender </label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="std_gender">
							<option value="">Select</option>';
							foreach($gender as $gndr){
								echo '<option value="'.$gndr.'" '.($val5 == $gndr? 'selected': '').'>'.$gndr.'</option>';
							}
							echo'
						</select>
					</div>
					<div class="col-md-4">
						<label class="control-label">Status </label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="status">
							<option value="">Select</option>';
							foreach($admstatus as $stat){
								echo '<option value="'.$stat['id'].'" '.($val6 == $stat['id']? 'selected': '').'>'.$stat['name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div class="row form-group">
					<div class="col-sm-12 center">
						<button type="submit" name="show_students" class="mr-xs btn btn-primary mt-sm">
							<i class="fa fa-search"></i> Search Result
						</button>
					</div>
				</div>
			</form>
		</div>
	</section>';
	
	// FILTER RESULTS
	if(isset($_POST['show_students'])){
		$sqllms	= $dblms->querylms("SELECT s.*, c.class_name, c.id_classlevel, se.section_name, sn.session_name, cm.campus_name, cm.campus_email, cm.campus_phone, cm.campus_address, a.adm_username,	
									(SELECT class_name FROM ".CLASSES." WHERE class_id > c.class_id AND id_classlevel = c.id_classlevel ORDER BY class_id LIMIT 1) AS next_class_name
									FROM ".STUDENTS." s
									INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
									LEFT JOIN ".CLASS_SECTIONS." se ON se.section_id = s.id_section
									LEFT JOIN ".SESSIONS." sn ON sn.session_id = s.id_session
									LEFT JOIN ".ADMINS." a ON a.adm_id = s.id_loginid
									INNER JOIN ".CAMPUS." cm ON cm.campus_id = s.id_campus
									WHERE s.std_id	   != ''
									AND s.is_deleted	= '0'
									AND s.id_campus		= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
									$sql1 $sql2 $sql3 $sql4 $sql5
									ORDER BY s.std_id DESC");
		if(mysqli_num_rows($sqllms) > 0){
			echo'
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-list"></i>  School Leaving Certificate</h2>
				</header>
				<div class="panel-body">
					<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
						<thead>
							<tr>
								<th width="40" class="center">Sr#</th>
								<th width="150px;">Reg No</th>
								<th width="150px;">Student</th>
								<th >Father</th>
								<th >Class</th>
								<th width="100" class="center">Options</th>
							</tr>
						</thead>
						<tbody>';
							$srno = 0;
							while($row = mysqli_fetch_array($sqllms)):
								echo'
								<tr>
									<td class="center">'.++$srno.'</td>
									<td>'.$row['std_regno'].'</td>
									<td>'.$row['std_name'].'</td>
									<td>'.$row['std_fathername'].'</td>
									<td>'.$row['class_name'].'</td>
									<td class="center">
										<a target="black" href="leaving_certificate_print_page.php?id='.$row['std_id'].'&duringClass='.$val4.'" class="fa fa-print btn btn-primary btn-xs"></a>
									</td>
								</tr>';
							endwhile;
							echo'
						</tbody>
					</table>
				</div>
			</section>';
		}else{
			echo'
			<section class="panel panel-featured panel-featured-primary">
				<div class="panel-body"><h2 class="text text-center text-danger">No Record Found!</h2></div>
			</section>';
		}
	}
}else{
	header("Location: dashboard.php");
}
?>