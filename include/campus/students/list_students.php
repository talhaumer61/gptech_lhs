<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'view' => '1'))){
$id_campus = $_SESSION['userlogininfo']['LOGINCAMPUS'];
echo'
<style>
.disabled-link a{
	pointer-events: none !important;
    cursor: not-allowed !important;
	color: #777 !important;
    background-color: #fff !important;
    border-color: #ddd !important;
}
</style>
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">';
	if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'add' => '1'))){
		echo'
		<a href="students.php?view=import_admissions" class="btn btn-primary btn-xs pull-right"><i class="fa fa-upload"></i> Import Student</a>
		<a href="students.php?view=add" class="btn btn-primary btn-xs pull-right mr-sm"><i class="fa fa-plus-square"></i> Make Student</a>';
	}
		
		//------------- Vars --------------
		$sql1 = "";
		$sql2 = "";
		$sql3 = "";
		$sql4 = "";
		$class = "";
		$std_status = "";
		$std_gender = "";
		$search_word = "";
		$filters = "";
		//-----------------------------------------------------
		if(isset($_GET['show_students']))
		{
			//--------- FIlters ----------
			//  Gender
			if($_GET['std_gender']){
				$sql1 = "AND s.std_gender = '".$_GET['std_gender']."'";
				$std_gender = $_GET['std_gender'];
			}
			//  class
			if($_GET['id_class'])
			{
				$sql2 = "AND s.id_class = '".$_GET['id_class']."'";
				$class = $_GET['id_class'];
			}
			// status
			if($_GET['status'])
			{
				$sql3 = "AND s.std_status = '".$_GET['status']."'";
				$std_status = $_GET['status'];
			}
			// search Words
			if($_GET['search_word'])
			{
				$sql4 = "AND (s.std_name LIKE '%".$_GET['search_word']."%' OR s.std_fathername LIKE '%".$_GET['search_word']."%' OR s.std_regno LIKE '%".$_GET['search_word']."%')";
				$search_word = $_GET['search_word'];
			}
			//  id_classlevel
			if($_GET['id_classlevel'])
			{
				$sql5 = "AND c.id_classlevel = '".$_GET['id_classlevel']."'";
				$id_classlevel = $_GET['id_classlevel'];
			}
		}
		
		// Print Card
		if(isset($_GET['id_class'])) {
			echo'<a href="studentcards_print.php?id_class='.$_GET['id_class'].'" target="_blank" class="btn btn-primary btn-xs pull-right mr-sm"><i class="glyphicon glyphicon-print"></i> Print Cards</a>';
		} else {
			echo'<a href="studentcards_print.php" target="_blank" class="btn btn-primary btn-xs pull-right mr-sm"><i class="glyphicon glyphicon-print"></i> Print Cards</a>';
		}
		echo'
		<a href="studentlistprint.php?id_class='.$class.'&id_classlevel='.$id_classlevel.'&std_gender='.$std_gender.'&std_status='.$std_status.'&id_campus='.$id_campus.'" target="_blank" class="ml-sm btn btn-primary btn-xs mr-sm pull-right"><i class="fa fa-print"></i> Print List</a>
                
		<h2 class="panel-title"><i class="fa fa-list"></i>  Students List</h2>
	</header>
	<div class="panel-body">
		<form action="#" method="GET" autocomplete="off">
			<div class="row form-group mb-lg">
				<div class="col-md-3">
					<label class="control-label">Search </label>
					<div class="form-group">
						<input type="search" name="search_word" id="search_word" class="form-control" value="'.$search_word.'" placeholder="student name or registration number or father name" aria-controls="table_export">
					</div>
				</div>
				<div class="col-md-2">
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
						<option value="">Select</option>';
							$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
												FROM ".CLASSES." 
												WHERE class_status = '1'
												AND is_deleted != '1'
												ORDER BY class_id ASC");
							while($valuecls = mysqli_fetch_array($sqllmscls)) {
								echo '<option value="'.$valuecls['class_id'].'"'; if($class == $valuecls['class_id']){ echo'selected';} echo'>'.$valuecls['class_name'].'</option>';
							}
							echo '
					</select>
				</div>
				<div class="col-md-2">
					<label class="control-label">Gender </label>
					<select class="form-control" data-plugin-selectTwo data-width="100%" name="std_gender">
						<option value="">Select</option>';
						foreach($gender as $gndr){
							echo '<option value="'.$gndr.'"'; if($std_gender == $gndr){ echo'selected';} echo'>'.$gndr.'</option>';
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
				<div class="col-md-1">
					<div class="form-group mt-xl">
						<button type="submit" name="show_students" class="btn btn-primary btn-block"><i class="fa fa-search"></i></button>
					</div>
				</div>
			</div>
		</form>';
		//------------- Pagination ---------------------
		$sqlstring	    = "";
		$adjacents = 3;
		if(!($Limit)) 	{ $Limit = 50; } 
		if($page)		{ $start = ($page - 1) * $Limit; } else {	$start = 0;	}
		//------------------------------------------------
		$sql = "SELECT s.std_id, s.std_status, s.std_name, s.std_fathername, s.std_gender, 
						s.std_phone, s.id_class, s.id_session,
						s.std_rollno, s.std_regno, s.std_photo, c.class_name, c.id_classlevel, se.section_name,
						sn.session_name
				FROM ".STUDENTS." 		s
				INNER JOIN ".CLASSES."  c  ON c.class_id = s.id_class
				LEFT JOIN ".CLASS_SECTIONS."  se  ON se.section_id = s.id_section
				LEFT JOIN ".SESSIONS."  sn  ON sn.session_id = s.id_session
				WHERE s.std_id != '' AND s.is_deleted != '1' $sql1 $sql2 $sql3 $sql4 $sql5
				AND s.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
				ORDER BY s.std_id DESC";

		$sqllms	= $dblms->querylms($sql);
		//--------------------------------------------------
		$count = mysqli_num_rows($sqllms);

		if((int)$page == 0) { $page = 1; }					//if no page var is given, default to 1.
		$prev 		    = $page - 1;						//previous page is page - 1
		$next 		    = $page + 1;						//next page is page + 1
		$lastpage  		= ceil($count/$Limit);				//lastpage is = total pages / items per page, rounded up.
		$lpm1 		    = $lastpage - 1;

		//--------------------------------------------------
		$sqllms	= $dblms->querylms("$sql LIMIT ".($page-1)*$Limit .",$Limit");
		//--------------------------------------------------
		if(mysqli_num_rows($sqllms) > 0){
		echo'
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-condensed" style="margin-top: 10px;">
					<thead>
						<tr>
							<th class="center" width="50">Sr No</th>
							<th width= 40>Photo</th>
							<th>Student Name</th>
							<th>Father Name</th>
							<th>Roll no</th>
							<th>Phone</th>
							<th>Class</th>
							<th>Section</th>
							<th>Session</th>
							<th width="70px;" class="center">Status</th>
							<th width="100px;" class="center">Options</th>
						</tr>
					</thead>
					<tbody>';
						//-----------------------------------------------------
						if($page == 1){
							$srno = 0;
						}else{
							$srno = ($page - 1) * $Limit;
						}
						
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
							<td>'.$rowsvalues['session_name'].'</td>
							<td class="center">'.get_status($rowsvalues['std_status']).'</td>
							<td class="center"><a class="btn btn-info btn-xs mr-xs" target="_blank" href="student_print.php?id='.$rowsvalues['std_id'].'"> <i class="fa fa-file"></i></a>';
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'edit' => '1'))){
									echo'<a class="btn btn-success btn-xs mr-xs" href="students.php?id='.$rowsvalues['std_id'].'"> <i class="fa fa-user-circle-o"></i></a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'delete' => '1'))){
									echo'<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'students.php?deleteid='.$rowsvalues['std_id'].'\');"><i class="el el-trash"></i></a>';
								}
								echo'
							</td>
						</tr>';
						}
						echo '
					</tbody>
				</table>
			</div>
			<div class="row"><div class="col-sm-12 col-md-6"><div class="dataTables_info" id="table_export_info" role="status" aria-live="polite">Showing '.((($page - 1) * $Limit) + 1).' to '.$srno.' of '.$count.' entries</div></div>';
			//-------------- Pagination ------------------
			// if($count>$Limit){
				echo '
				<div class="col-sm-12 col-md-6">
				<!--WI_PAGINATION-->
				<ul class="pagination pull-right">';
				//--------------------------------------------------
				$current_page = strstr(basename($_SERVER['REQUEST_URI']), '.php', true);
				$filters = 'id_class='.$class.'&status='.$std_status.'&is_orphan='.$orphan.'&search_word='.$search_word.'&show_students';
				//--------------------------------------------------
				$pagination = "";
				if($lastpage >= 1){
					//previous button
					if($page > 0){
						$pagination.= '<li class="'.($page==1 ? 'disabled-link' : '').'"><a href="'.$current_page.'.php?'.$filters.'&page='.$prev.$sqlstring.'"><span class="fa fa-chevron-left"></span></a></li>';
					}
					//pages 
					if($lastpage < 7 + ($adjacents * 3)){
						//not enough pages to bother breaking it up
						for ($counter = 1; $counter <= $lastpage; $counter++){
							if($counter == $page){
								$pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
							}else{
								$pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';
							}
						}
					}elseif($lastpage > 5 + ($adjacents * 3)){
						//enough pages to hide some
						//close to beginning; only hide later pages
						if($page < 1 + ($adjacents * 3)){
							for ($counter = 1; $counter < 4 + ($adjacents * 3); $counter++) {
								if ($counter == $page){
									$pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
								}else{
									$pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';
								}
							}
							$pagination.= '<li><a href="#"> ... </a></li>';
							$pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$lpm1.$sqlstring.'">'.$lpm1.'</a></li>';
							$pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$lastpage.$sqlstring.'">'.$lastpage.'</a></li>';   
						}elseif($lastpage - ($adjacents * 3) > $page && $page > ($adjacents * 3)){
							//in middle; hide some front and some back
							$pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page=1'.$sqlstring.'">1</a></li>';
							$pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page=2'.$sqlstring.'">2</a></li>';
							$pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page=3'.$sqlstring.'">3</a></li>';
							$pagination.= '<li><a href="#"> ... </a></li>';
							for($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++){
								if($counter == $page){
									$pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
								}else{
									$pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';                 
								}
							}
							$pagination.= '<li><a href="#"> ... </a></li>';
							$pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$lpm1.$sqlstring.'">'.$lpm1.'</a></li>';
							$pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$lastpage.$sqlstring.'">'.$lastpage.'</a></li>';   
						}else{
							//close to end; only hide early pages
							$pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page=1'.$sqlstring.'">1</a></li>';
							$pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page=2'.$sqlstring.'">2</a></li>';
							$pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page=3'.$sqlstring.'">3</a></li>';
							$pagination.= '<li><a href="#"> ... </a></li>';
							for ($counter = $lastpage - (3 + ($adjacents * 3)); $counter <= $lastpage; $counter++) {
								if($counter == $page){
									$pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
								}else{
									$pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';                 
								}
							}
						}
					}
					//next button
					if($page < $counter){
						$pagination.= '<li class="'.($page==$counter-1 ? 'disabled-link' : '').'"><a href="'.$current_page.'.php?'.$filters.'&page='.$next.$sqlstring.'"><span class="fa fa-chevron-right"></span></a></li>';
					}else{
						$pagination.= "";
					}
					echo $pagination;
				}
				echo '
				</ul>
				<!--WI_PAGINATION-->
					<div class="clearfix"></div>
				</div>';
			// }
			//------------------------------------------------
		}
		else{
			echo'<div class="panel-body"><h2 class="text text-center text-danger mt-lg">No Record Found!</h2></div>';
		}
		echo'
		</div>
	</div>
</section>';
}
else{
	header("Location: dashboard.php");
}
?>