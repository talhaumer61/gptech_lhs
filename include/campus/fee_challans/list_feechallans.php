<style>
.card{
	padding: 20px;
	font-size: 30px;
	border-radius:10px;
	margin-left: 4%;
	margin-right: 4%;
	}
.val{
	font-size: 20px;
	margin-left: 18%;
	}
.span{
	font-size:14px;
	}
</style>
<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))){ 

//------------------------------------------------------
$sqllmspaid	= $dblms->querylms("SELECT f.status, SUM(f.total_amount) as paid
								   FROM ".FEES." f
								   WHERE f.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
								   AND f.status = '1' AND f.is_deleted != '1'");
$value_paid = mysqli_fetch_array($sqllmspaid);
if($value_paid['paid']){$paid = $value_paid['paid'];}else{$paid = 0;}
//------------------------------------------------------
$sqllmspending	= $dblms->querylms("SELECT f.status, SUM(f.total_amount) as pending
								   FROM ".FEES." f
								   WHERE f.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
								   AND f.status = '2' AND f.is_deleted != '1'");
$value_pending = mysqli_fetch_array($sqllmspending);
if($value_pending['pending']){$pending = $value_pending['pending'];}else{$pending = 0;}
//------------------------------------------------------
$sqllmsunpaid	= $dblms->querylms("SELECT f.status, SUM(f.total_amount) as unpaid
								   FROM ".FEES." f
								   WHERE f.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
								   AND f.status = '3' AND f.is_deleted != '1'");
$value_unpaid = mysqli_fetch_array($sqllmsunpaid);
if($value_unpaid['unpaid']){$unpaid = $value_unpaid['unpaid'];}else{$unpaid = 0;}
//------------------------------------------------------

$sql2 		 = "";
$sqlStatus 	 = "";
$sqlClass 	 = "";
$sqlSection  = "";
$status 	 = "";
$search_word = "";
$class 		 = "";
$section 	 = "";
//--------- Filter ---------------
if(isset($_GET['search_word']) && !empty($_GET['search_word']))
{
	$sql2 = "AND (f.challan_no LIKE '%".$_GET['search_word']."%' OR st.std_name LIKE '%".$_GET['search_word']."%' OR c.class_name LIKE '%".$_GET['search_word']."%' OR st.std_rollno LIKE '%".$_GET['search_word']."%')";
	$search_word = $_GET['search_word'];
}
if(isset($_GET['status']) && !empty($_GET['status'])){
	$status = $_GET['status'];
	if($status == 'total'){
		$sqlStatus	=	"";  
	}else{
		$sqlStatus	=	" AND f.status = '".$status."'";
	}
}	
if(isset($_GET['id_class']) && !empty($_GET['id_class'])){
	$class 		= 	$_GET['id_class'];
	$sqlClass	=	" AND f.id_class = '".$class."'";
}
if(isset($_GET['id_section']) && !empty($_GET['id_section'])){
	$section 		= 	$_GET['id_section'];
	$sqlSection	=	"AND f.id_section = '".$section."'";
}
//--------------------------------

echo '
<div class="row mt-none mb-md">
	<div class="col-sm-12 col-md-12 col-lg-3 bg bg-danger card mb-sm">
		<i class="fa fa-ban" aria-hidden="true"></i> Total Receivable
		<p class="val mt-md"><span class="span">Rs:</span> '.number_format($unpaid).'</p>
	</div>
	<div class="col-sm-12 col-md-12 col-lg-3 bg bg-success card mb-sm">
		<i class="fa fa-star" aria-hidden="true"></i> Total Paid
		<p class="val mt-md"><span class="span">Rs:</span> '.number_format($paid).'</p>
	</div>
	<div class="col-sm-12 col-md-12 col-lg-3 bg bg-warning card mb-sm">
		<i class="fa fa-refresh" aria-hidden="true"></i> Total Pending
		<p class="val mt-md"><span class="span">Rs:</span> '.number_format($pending).'</p>
	</div>
</div>
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">';
		if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))){ 
			echo'<a href="#print_report" class="modal-with-move-anim ml-sm mb-xs btn btn-primary btn-xs pull-right"><i class="fa fa-print"></i> Print Report</a>
			<a href="#print_challan" class="modal-with-move-anim ml-sm btn btn-primary btn-xs pull-right"><i class="fa fa-print"></i> Print Challan</a>';
		}
		if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'add' => '1'))){ 
			echo'<a href="fee_challans.php?view=bulk" class="btn btn-primary ml-sm btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Class Challan</a>
			<a href="#make_challan" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Single Challan</a>';
		}
		
		echo'
		<h2 class="panel-title"><i class="fa fa-list"></i>  Challans List</h2>
	</header>
	<div class="panel-body">
		<form method="GET" autocomplete="off">
			<div class="row">
				<div class="form-group mb-sm">
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label">Search</label>
							<input type="search" name="search_word" id="search_word" placeholder="Challan No. or Student Name or Class Name or Student Roll No." class="form-control" value="'.$search_word.'" placeholder="Search">
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label">Class</label>
							<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class" onchange="get_section(this.value)">
								<option value="">Select</option>';
								$sqllmsclass	= $dblms->querylms("SELECT class_id, class_name 
																		FROM ".CLASSES." 
																		WHERE class_status = '1' ORDER BY class_id ASC");
								while($value_class 	= mysqli_fetch_array($sqllmsclass)) {
								echo '<option value="'.$value_class['class_id'].'" '.($value_class['class_id'] == $class ? 'selected' : '').'>'.$value_class['class_name'].'</option>';
								}
								echo '
							</select>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label">Sections</label>
							<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_section" name="id_section">
								<option value="">Select</option>';
								$sqllmsExamType	= $dblms->querylms("SELECT section_id, section_name 
																		FROM ".CLASS_SECTIONS." 
																		WHERE section_status = '1' 
																		AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
																		AND id_class = '".$class."'
																		ORDER BY section_id ASC");
									while($value_class 	= mysqli_fetch_array($sqllmsExamType)) {
										echo '<option value="'.$value_class['section_id'].'" '.($value_class['section_id'] == $section ? 'selected' : '').'>'.$value_class['section_name'].'</option>';
									}
								echo '
							</select>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label class="control-label">Status</label>
							<select data-plugin-selectTwo data-width="100%" name="status" title="Must Be Required" class="form-control populate">
								<option value="">Select</option>
								<option value="total" '.($status == 'total' ? 'selected' : '').'>Total Challan</option>';
								foreach($payments as $payment){
									if($payment['id'] == $status){
										echo'<option value="'.$payment['id'].'" selected>'.$payment['name'].'</option>';
										}else{
											echo'<option value="'.$payment['id'].'">'.$payment['name'].'</option>';
											}
								}
								echo'
							</select>
						</div>
					</div>
					<div class="col-sm-1">
						<div class="form-group text-right">
							<button type="submit" class="btn btn-primary btn-block mt-xl"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</div>
			</div>
		</form>';
		$sql = "SELECT DISTINCT
				f.id, f.status, f.id_month, f.challan_no, f.issue_date, f.due_date, 
				f.scholarship, f.concession, f.fine, f.prev_remaining_amount, 
				f.total_amount,SUM(fd.amount) AS total_before_discount, f.remaining_amount, 
				c.class_name, cs.section_name, s.session_name, 
				st.std_id, st.std_name
				FROM ".FEES." f				   
				INNER JOIN ".CLASSES." c ON c.class_id = f.id_class	 
				INNER JOIN ".STUDENTS." st ON st.std_id = f.id_std
				LEFT JOIN ".CLASS_SECTIONS." cs ON cs.section_id = st.id_section							 
				INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session	
				INNER JOIN ".FEESETUP." fs ON fs.id_class = f.id_class 
					AND fs.id_section = st.id_section 
					AND fs.id_session = f.id_session
					AND fs.id_campus = f.id_campus
				INNER JOIN ".FEESETUPDETAIL." fd ON fd.id_setup = fs.id
				WHERE f.is_deleted != '1' 
				$sqlStatus 
				$sqlClass 
				$sqlSection
				AND s.is_deleted = '0'
				AND st.is_deleted = '0'
				AND f.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'   
				$sql2
				GROUP BY f.id ORDER BY f.id DESC";

		$sqllms	= $dblms->querylms($sql);
										
		$count = mysqli_num_rows($sqllms);
		if($page == 0 || empty($page)) { $page = 1; }			//if no page var is given, default to 1.
		$prev 		    = $page - 1;							//previous page is page - 1
		$next 		    = $page + 1;							//next page is page + 1
		$lastpage  		= ceil($count/$Limit);					//lastpage is = total pages / items per page, rounded up.
		$lpm1 		    = $lastpage - 1;
		
		$filters = "";
		
		$sqllms	= $dblms->querylms("$sql LIMIT ".($page-1)*$Limit .",$Limit");
		
		if(mysqli_num_rows($sqllms) > 0){
			echo'
			<table class="table table-bordered table-striped table-condensed mb-none">
				<thead>
					<tr>
						<th class="center">#</th>
						<th>Challan #</th>
						<th>Month</th>
						<th>Issue Date</th>
						<th>Due Date</th>
						<th>Session</th>
						<th>Class</th>
						<th>Student</th>
						<th>Total</th> 
						<th>Discount</th> 
						<th>Fine</th>
						<th>Pre. Balance</th>
						<th>Payable</th>
						<th>Balance</th>
						<th width="70px;" class="center">Status</th>
						<th width="150" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					if($page == 1){
						$srno = 0;
					}else{
						$srno = ($page - 1) * $Limit;
					}
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$concession = $rowsvalues['scholarship']+$rowsvalues['concession'];
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['challan_no'].'</td>
							<td>'.get_monthtypes($rowsvalues['id_month']).'</td>
							<td>'.$rowsvalues['issue_date'].'</td>
							<td>'.$rowsvalues['due_date'].'</td>
							<td>'.$rowsvalues['session_name'].'</td>
							<td>'.$rowsvalues['class_name'].' ('.$rowsvalues['section_name'].')</td>
							<td>'.$rowsvalues['std_name'].'</td>
							<td>'.$rowsvalues['total_before_discount'].'</td>
							<td>'.number_format(round($concession)).'</td>
							<td>'.number_format(round($rowsvalues['fine'])).'</td>
							<td>'.number_format(round($rowsvalues['prev_remaining_amount'])).'</td>
							<td>'.number_format(round($rowsvalues['total_amount'])).'</td>
							<td>'.number_format(round($rowsvalues['remaining_amount'])).'</td>
							<td class="center">'.get_payments($rowsvalues['status']).'</td>
							<td class="center">';
							echo '
								<a class="btn btn-success btn-xs" class="center" href="feechallanprint.php?id='.$rowsvalues['challan_no'].'" target="_blank"> <i class="fa fa-file"></i></a>';
								if($rowsvalues['status'] != '1'){
									if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'edit' => '1'))){ 
										echo '<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs ml-xs" onclick="showAjaxModalZoom(\'include/modals/fee_challans/modal_feechallan_update.php?id='.$rowsvalues['id'].'\');"><i class="glyphicon glyphicon-edit"></i> </a>';
										if($rowsvalues['remaining_amount'] == 0){
											echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs ml-xs" onclick="showAjaxModalZoom(\'include/modals/fee_challans/modal_feechallan_partialpayment.php?id='.$rowsvalues['id'].'\');"><img src="assets/images/partial_payment.png" height="15" width="auto"></a>';
										}
									}
									if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'delete' => '1'))){ 
										echo '<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'fee_challans.php?deleteid='.$rowsvalues['challan_no'].'\');"><i class="el el-trash"></i></a>';
									}
								}
								echo '
							</td>
						</tr>';
					}
				echo '
				</tbody>
			</table>';
			include("include/pagination.php");
		}
		else{
			echo'<div class="panel-body"><h2 class="text text-center text-danger mt-lg">No Record Found!</h2></div>';
		}
		echo'
	</div>
</section>';
}
else{
	header("Location: dashboard.php");
}
?>