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
								   WHERE f.status = '1' AND f.id_type = '3' AND f.is_deleted != '1'");
$value_paid = mysqli_fetch_array($sqllmspaid);
if($value_paid['paid']){$paid = $value_paid['paid'];}else{$paid = 0;}
//------------------------------------------------------
$sqllmspending	= $dblms->querylms("SELECT f.status, SUM(f.total_amount) as pending
								   FROM ".FEES." f
								   WHERE f.status = '2' AND f.id_type = '3' AND f.is_deleted != '1'");
$value_pending = mysqli_fetch_array($sqllmspending);
if($value_pending['pending']){$pending = $value_pending['pending'];}else{$pending = 0;}
//------------------------------------------------------
$sqllmsunpaid	= $dblms->querylms("SELECT f.status, SUM(f.total_amount) as unpaid
								   FROM ".FEES." f
								   WHERE f.status = '3' AND f.id_type = '3' AND f.is_deleted != '1'");
$value_unpaid = mysqli_fetch_array($sqllmsunpaid);
if($value_unpaid['unpaid']){$unpaid = $value_unpaid['unpaid'];}else{$unpaid = 0;}
//------------------------------------------------------

$sql2 = "";
$search_word = "";
//--------- Filter ---------------
if(isset($_GET['search_word']))
{
	$sql2 = "AND (f.challan_no LIKE '%".$_GET['search_word']."%' OR c.campus_name LIKE '%".$_GET['search_word']."%')";
	$search_word = $_GET['search_word'];
}
//--------------------------------

echo '
<div class="row mt-none mb-md">
	<div class="col-sm-12 col-md-12 col-lg-3 bg bg-success card mb-sm">
		<i class="fa fa-star" aria-hidden="true"></i> Total Paid
		<p class="val mt-md"><span class="span">Rs:</span> '.number_format($paid).'</p>
	</div>
	<div class="col-sm-12 col-md-12 col-lg-3 bg bg-warning card mb-sm">
		<i class="fa fa-refresh" aria-hidden="true"></i> Total Pending
		<p class="val mt-md"><span class="span">Rs:</span> '.number_format($pending).'</p>
	</div>
	<div class="col-sm-12 col-md-12 col-lg-3 bg bg-danger card mb-sm">
		<i class="fa fa-ban" aria-hidden="true"></i> Total Unpaid
		<p class="val mt-md"><span class="span">Rs:</span> '.number_format($unpaid).'</p>
	</div>
</div>
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">';
		// if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))){ 
		// 	echo'<a href="#print_challan" class="modal-with-move-anim ml-sm btn btn-primary btn-xs pull-right"><i class="fa fa-print"></i> Print Challan</a>';
		// }
		if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'add' => '1'))){ 
			echo'<a href="royaltyChallans.php?view=add" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Challan</a>';
		}
		
		echo'
		<h2 class="panel-title"><i class="fa fa-list"></i>  Challans List</h2>
	</header>
	<div class="panel-body">
		<form action="#" method="GET" autocomplete="off">
			<div class="form-group mb-sm">
				<div class="col-sm-3 col-sm-offset-8">
					<div class="form-group">
						<input type="search" name="search_word" id="search_word" class="form-control" value="'.$search_word.'" placeholder="Search">
					</div>
				</div>
				<div class="col-sm-1">
					<div class="form-group">
						<button type="submit" class="btn btn-primary" style="width: 90px;;"><i class="fa fa-search"></i> Search</button>
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
		$sqllms	= $dblms->querylms("SELECT f.id
										FROM ".FEES." f				   
										INNER JOIN ".CAMPUS." c ON c.campus_id = f.id_campus
										INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session							 
										WHERE f.is_deleted != '1' AND f.id_type = '3' $sql2
										ORDER BY f.id DESC");
		//--------------------------------------------------
		$count = mysqli_num_rows($sqllms);
		if($page == 0) { $page = 1; }						//if no page var is given, default to 1.
		$prev 		    = $page - 1;							//previous page is page - 1
		$next 		    = $page + 1;							//next page is page + 1
		$lastpage  		= ceil($count/$Limit);					//lastpage is = total pages / items per page, rounded up.
		$lpm1 		    = $lastpage - 1;
		//--------------------------------------------------  
		
		//-----------------------------------------------------
		$sqllms	= $dblms->querylms("SELECT f.id, f.status, f.id_month, f.to_month, f.challan_no, f.issue_date, f.due_date, f.total_amount, f.remaining_amount,
                                        c.campus_name, s.session_name
										FROM ".FEES." f				   
										INNER JOIN ".CAMPUS." c ON c.campus_id = f.id_campus
										INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session							 
										WHERE f.is_deleted != '1' AND f.id_type = '3' $sql2
										ORDER BY f.id DESC  LIMIT ".($page-1)*$Limit .",$Limit");
		$srno = 0;
		//-----------------------------------------------------
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
						<th>Campus</th>
						<th>Payable</th>
						<th>Balance</th>
						<th width="70px;" class="center">Status</th>
						<th width="150" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
	
						// Months
						if($rowsvalues['id_month'] == $rowsvalues['to_month'] || $rowsvalues['to_month'] == '0') {
							$month = get_monthtypes($rowsvalues['id_month']);
						} else {
							$month = get_monthtypes($rowsvalues['id_month']).' To '. get_monthtypes($rowsvalues['to_month']);
						}

						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['challan_no'].'</td>
							<td>'.$month.'</td>
							<td>'.$rowsvalues['issue_date'].'</td>
							<td>'.$rowsvalues['due_date'].'</td>
							<td>'.$rowsvalues['session_name'].'</td>
							<td>'.$rowsvalues['campus_name'].'</td>
							<td>'.number_format(round($rowsvalues['total_amount'])).'</td>
							<td>'.number_format(round($rowsvalues['remaining_amount'])).'</td>
							<td class="center">'.get_payments($rowsvalues['status']).'</td>
							<td class="center">';
							echo '
								<a class="btn btn-success btn-xs" class="center" href="royaltyChallanPrint.php?id='.$rowsvalues['challan_no'].'" target="_blank"> <i class="fa fa-file"></i></a>';
								if($rowsvalues['status'] != '1'){
									if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'edit' => '1'))){ 
										echo '<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs ml-xs" onclick="showAjaxModalZoom(\'include/modals/royalty_challans/modal_royaltychallan_update.php?id='.$rowsvalues['id'].'\');"><i class="glyphicon glyphicon-edit"></i> </a>';
										// if($rowsvalues['remaining_amount'] == 0){
										// 	echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs ml-xs" onclick="showAjaxModalZoom(\'include/modals/fee_challans/modal_feechallan_partialpayment.php?id='.$rowsvalues['id'].'\');"><img src="assets/images/partial_payment.png" height="15" width="auto"></a>';
										// }
									}
									if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'delete' => '1'))){ 
										echo '<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'royaltyChallans.php?deleteid='.$rowsvalues['challan_no'].'\');"><i class="el el-trash"></i></a>';
									}
								}
								echo '
							</td>
						</tr>';
					}
				echo '
				</tbody>
			</table>';
		
			//-------------- Pagination ------------------
			if($count>$Limit) {
				echo '
				<div class="widget-foot">
				<!--WI_PAGINATION-->
				<ul class="pagination pull-right">';
				//--------------------------------------------------
				$current_page = strstr(basename($_SERVER['REQUEST_URI']), '.php', true);
				//--------------------------------------------------
				$pagination = "";
				if($lastpage > 1) { 
				//previous button
				if ($page > 1) {
					$pagination.= '<li><a href="'.$current_page.'.php?search_word='.$search_word.'&page='.$prev.$sqlstring.'"><span class="fa fa-chevron-left"></span></a></a></li>';
				}
				//pages 
				if ($lastpage < 7 + ($adjacents * 3)) { //not enough pages to bother breaking it up
					for ($counter = 1; $counter <= $lastpage; $counter++) {
						if ($counter == $page) {
							$pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
						} else {
							$pagination.= '<li><a href="'.$current_page.'.php?search_word='.$search_word.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';
						}
					}
				} else if($lastpage > 5 + ($adjacents * 3)) { //enough pages to hide some
				//close to beginning; only hide later pages
					if($page < 1 + ($adjacents * 3)) {
						for ($counter = 1; $counter < 4 + ($adjacents * 3); $counter++) {
							if ($counter == $page) {
								$pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
							} else {
								$pagination.= '<li><a href="'.$current_page.'.php?search_word='.$search_word.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';
							}
						}
						$pagination.= '<li><a href="#"> ... </a></li>';
						$pagination.= '<li><a href="'.$current_page.'.php?search_word='.$search_word.'&page='.$lpm1.$sqlstring.'">'.$lpm1.'</a></li>';
						$pagination.= '<li><a href="'.$current_page.'.php?search_word='.$search_word.'&page='.$lastpage.$sqlstring.'">'.$lastpage.'</a></li>';   
				} else if($lastpage - ($adjacents * 3) > $page && $page > ($adjacents * 3)) { //in middle; hide some front and some back
						$pagination.= '<li><a href="'.$current_page.'.php?search_word='.$search_word.'&page=1'.$sqlstring.'">1</a></li>';
						$pagination.= '<li><a href="'.$current_page.'.php?search_word='.$search_word.'&page=2'.$sqlstring.'">2</a></li>';
						$pagination.= '<li><a href="'.$current_page.'.php?search_word='.$search_word.'&page=3'.$sqlstring.'">3</a></li>';
						$pagination.= '<li><a href="#"> ... </a></li>';
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
						if ($counter == $page) {
							$pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
						} else {
							$pagination.= '<li><a href="'.$current_page.'.php?search_word='.$search_word.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';                 
						}
					}
					$pagination.= '<li><a href="#"> ... </a></li>';
					$pagination.= '<li><a href="'.$current_page.'.php?search_word='.$search_word.'&page='.$lpm1.$sqlstring.'">'.$lpm1.'</a></li>';
					$pagination.= '<li><a href="'.$current_page.'.php?search_word='.$search_word.'&page='.$lastpage.$sqlstring.'">'.$lastpage.'</a></li>';   
				} else { //close to end; only hide early pages
					$pagination.= '<li><a href="'.$current_page.'.php?search_word='.$search_word.'&page=1'.$sqlstring.'">1</a></li>';
					$pagination.= '<li><a href="'.$current_page.'.php?search_word='.$search_word.'&page=2'.$sqlstring.'">2</a></li>';
					$pagination.= '<li><a href="'.$current_page.'.php?search_word='.$search_word.'&page=3'.$sqlstring.'">3</a></li>';
					$pagination.= '<li><a href="#"> ... </a></li>';
					for ($counter = $lastpage - (3 + ($adjacents * 3)); $counter <= $lastpage; $counter++) {
						if ($counter == $page) {
							$pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
						} else {
							$pagination.= '<li><a href="'.$current_page.'.php?search_word='.$search_word.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';                 
						}
					}
				}
				}
				//next button
				if ($page < $counter - 1) {
					$pagination.= '<li><a href="'.$current_page.'.php?search_word='.$search_word.'&page='.$next.$sqlstring.'"><span class="fa fa-chevron-right"></span></a></li>';
				} else {
					$pagination.= "";
				}
					echo $pagination;
				}
				echo '
				</ul>
				<!--WI_PAGINATION-->
					<div class="clearfix"></div>
				</div>';
			}
			//------------------------------------------------
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