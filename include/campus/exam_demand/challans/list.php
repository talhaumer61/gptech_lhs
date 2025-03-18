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
$id_campus = $_SESSION['userlogininfo']['LOGINCAMPUS'];
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))){ 

	$sqllmspaid	= $dblms->querylms("SELECT f.status, SUM(f.total_amount) as paid
									FROM ".EXAM_FEE_CHALLANS." f
									WHERE f.status = '1' 
									AND f.id_campus = '".$id_campus."'
									AND f.is_deleted = '0'
								  ");
	$value_paid = mysqli_fetch_array($sqllmspaid);
	if($value_paid['paid']){$paid = $value_paid['paid'];}else{$paid = 0;}

	$sqllmspending	= $dblms->querylms("SELECT f.status, SUM(f.total_amount) as pending
										FROM ".EXAM_FEE_CHALLANS." f
										WHERE f.status = '2'
										AND f.id_campus = '".$id_campus."' 
										AND f.is_deleted = '0'
								      ");
	$value_pending = mysqli_fetch_array($sqllmspending);
	if($value_pending['pending']){$pending = $value_pending['pending'];}else{$pending = 0;}

	$sqllmsunpaid	= $dblms->querylms("SELECT f.status, SUM(f.total_amount) as unpaid
										FROM ".EXAM_FEE_CHALLANS." f
										WHERE f.status = '3' 
										AND f.id_campus = '".$id_campus."'
										AND f.is_deleted = '0'
									  ");
	$value_unpaid = mysqli_fetch_array($sqllmsunpaid);
	if($value_unpaid['unpaid']){$unpaid = $value_unpaid['unpaid'];}else{$unpaid = 0;}

	$sqlSearch   = "";
	$search_word = "";
	if(isset($_GET['search_word']))
	{
		$sqlSearch 	 = "AND (f.challan_no LIKE '%".$_GET['search_word']."%' OR c.campus_name LIKE '%".$_GET['search_word']."%')";
		$search_word = $_GET['search_word'];
	}
	

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
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i>  Challans List</h2>
		</header>
		<div class="panel-body">
			<form action="#" method="GET" autocomplete="off">
				<div class="form-group mb-lg">
					<div class="row">
						<div class="col-sm-3 col-sm-offset-9">
							<div class="input-group">
								<input type="search" class="form-control" name="search_word" id="search_word" value="'.$search_word.'" placeholder="Search">
								<div class="input-group-btn">
									<button class="btn btn-primary" type="submit">
										<i class="fa fa-search"></i>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>';
			// PAGINATION
			$sqlstring	    = "";
			$adjacents = 3;
			if(!($Limit)) 	{ $Limit = 50; } 
			if($page)		{ $start = ($page - 1) * $Limit; } else {	$start = 0;	}
			
			$sqllms	= $dblms->querylms("SELECT f.id
											FROM ".EXAM_FEE_CHALLANS." f				   
											INNER JOIN ".CAMPUS." c ON c.campus_id = f.id_campus
											INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session
											INNER JOIN ".EXAM_TYPES." et ON et.type_id = f.id_examtype							 
											WHERE f.is_deleted = '0' $sqlSearch
											AND f.id_campus = '".$id_campus."'
											ORDER BY f.id DESC
									  ");
											
			$count = mysqli_num_rows($sqllms);
			if($page == 0) { $page = 1; }							//if no page var is given, default to 1.
			$prev 		    = $page - 1;							//previous page is page - 1
			$next 		    = $page + 1;							//next page is page + 1
			$lastpage  		= ceil($count/$Limit);					//lastpage is = total pages / items per page, rounded up.
			$lpm1 		    = $lastpage - 1;
			
			$sqllms	= $dblms->querylms("SELECT f.id, f.status, f.challan_no, f.issue_date, f.due_date, f.total_amount, 
											c.campus_name, s.session_name, et.type_name
											FROM ".EXAM_FEE_CHALLANS." f				   
											INNER JOIN ".CAMPUS." c ON c.campus_id = f.id_campus
											INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session
											INNER JOIN ".EXAM_TYPES." et ON et.type_id = f.id_examtype							 
											WHERE f.is_deleted = '0' $sqlSearch AND f.id_campus = '".$id_campus."'
											ORDER BY f.id DESC  LIMIT ".($page-1)*$Limit .",$Limit
									  ");
			$srno = 0;
			
			if(mysqli_num_rows($sqllms) > 0){
				echo'
				<table class="table table-bordered table-striped table-condensed mb-none">
					<thead>
						<tr>
							<th width="40" class="center">Sr#</th>
							<th width="80" class="center">Session</th>
							<th width="130" class="center">Challan #</th>
							<th>Campus</th>
							<th>Exam Type</th>
							<th width="100" class="center">Issue Date</th>
							<th width="100" class="center">Due Date</th>
							<th width="100" class="center">Payable</th>
							<th width="70px;" class="center">Status</th>
							<th width="70" class="center">Options</th>
						</tr>
					</thead>
					<tbody>';
						while($rowsvalues = mysqli_fetch_array($sqllms)) {
							$srno++;
							echo '
							<tr>
								<td class="center">'.$srno.'</td>
								<td>'.$rowsvalues['session_name'].'</td>
								<td class="center">'.$rowsvalues['challan_no'].'</td>
								<td>'.$rowsvalues['campus_name'].'</td>
								<td>'.$rowsvalues['type_name'].'</td>
								<td class="center">'.$rowsvalues['issue_date'].'</td>
								<td class="center">'.$rowsvalues['due_date'].'</td>
								<td class="center">'.number_format(round($rowsvalues['total_amount'])).'</td>
								<td class="center">'.get_payments($rowsvalues['status']).'</td>
								<td class="center">';
								echo '
									<a class="btn btn-success btn-xs" class="center" href="examDemandChallanPrint.php?id='.$rowsvalues['challan_no'].'" target="_blank"> <i class="fa fa-file"></i></a>
								</td>
							</tr>';
						}
					echo '
					</tbody>
				</table>';
				// PAGINATION
				if($count>$Limit) {
					echo '
					<div class="widget-foot">
					<!--WI_PAGINATION-->
					<ul class="pagination pull-right">';
					
					$current_page = strstr(basename($_SERVER['REQUEST_URI']), '.php', true);
					
					$pagination = "";
					if($lastpage > 1) { 
					// PREVIOUS BUTTON
					if ($page > 1) {
						$pagination.= '<li><a href="'.$current_page.'.php?search_word='.$search_word.'&page='.$prev.$sqlstring.'"><span class="fa fa-chevron-left"></span></a></a></li>';
					}
					// PAGES
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