<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '73', 'view' => '1'))){
echo'
<style>
.ui-datepicker-calendar{
    display: none;
 }
</style>
<title>Student Detail Panel| '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Student Detail Panel</h2>
	</header>
	<!-- INCLUDEING PAGE -->
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-list"></i> Campus Students Detail</h2>
				</header>
				<div class="panel-body">';
				$sqlCampus	= $dblms->querylms("SELECT c.campus_id, c.campus_name, 
												COUNT(s.std_id) as total_students, 
												COUNT(CASE WHEN s.std_status = '1' THEN 1 else null end) as active_students, 
												COUNT(CASE WHEN s.std_status = '2' THEN 1 else null end) as inactive_students, 
												COUNT(DISTINCT s.id_class) as total_classes 
												FROM ".CAMPUS." c
												LEFT JOIN ".STUDENTS." s ON (s.id_campus = c.campus_id AND s.is_deleted = '0')
												WHERE c.campus_id != '' 
												AND c.campus_status = '1' 
												AND c.is_deleted = '0'
												GROUP BY c.campus_id");
				if(mysqli_num_rows($sqlCampus) > 0){
					echo'
					<div class="text-right mr-lg on-screen">
						<button onclick="print_report(\'printResult\')" class="mr-xs btn btn-primary btn-sm"><i class="glyphicon glyphicon-print"></i> Print</button>
						<button id="export_button" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Excel</button>
					</div>
					<div id="printResult">
						<div class="invoice mt-md">
							<div id="header" style="display:none;">
								<h2 style="text-align: center;">
									<img src="uploads/logo.png" class="img-fluid" style="width: 70px; height: 70px;"> 
									<span><b>Laurel Home Schoool</b></span>
								</h2>
								<h4 style="text-align: center;"><b>Campus Wise Students Details</b></h4>
							</div>
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-condensed">
									<thead>
										<tr class="h5 text-dark">
											<th width="50" class="center">Sr.</th>
											<th>Campus Name</th>
											<th class="text-center" width="135">Total Students</th>
											<th class="text-center" width="135">Active Students</th>
											<th class="text-center" width="135">Inactive Students</th>
											<th class="text-center" width="135">Total Classes</th>
										</tr>
									</thead>
									<tbody>';
										$totalStd		=	0;
										$totalActive	=	0;
										$totalInActive	=	0;
										$totalClasses	= 	0;
										$srno 			= 	0;
										while($valCampus = mysqli_fetch_array($sqlCampus)){
											$totalStd		=	$totalStd 		+ 	$valCampus['total_students'];
											$totalActive	=	$totalActive	+	$valCampus['active_students'];
											$totalInActive	=	$totalInActive	+	$valCampus['inactive_students'];
											$totalClasses	=	$totalClasses	+	$valCampus['total_classes'];
											$srno++;
											echo'
											<tr>
												<td class="center">'.$srno.'</td>
												<td><b>'.$valCampus['campus_name'].'</b></td>
												<td class="text-center">'.$valCampus['total_students'].'</td>
												<td class="text-center">'.$valCampus['active_students'].'</td>
												<td class="text-center">'.$valCampus['inactive_students'].'</td>
												<td class="text-center">'.$valCampus['total_classes'].'</td>
											</tr>';
										}
										echo '
										<tr>
											<th class="center" colspan="2">Gran Total</th>
											<th class="center">'.$totalStd.'</th>
											<th class="center">'.$totalActive.'</th>
											<th class="center">'.$totalInActive.'</th>
											<th class="center">'.$totalClasses.'</th>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>';
				}
				else{
					echo '<h2 class="center">No Record Found</h2>';
				}
				echo'
				</div>
			</section>
		</div>
	</div>
</section>
';
}
else{
	header("Location: dashboard.php");
}
?>
<script>
    // PRINT THE TABLE 
    function print_report(printResult) {
		
		document.getElementById('header').style.display = 'block';
        var printContents = document.getElementById(printResult).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
		var css = '@page {   }',
		head = document.head || document.getElementsByTagName('head')[0],
		style = document.createElement('style');
		style.type = 'text/css';
		style.media = 'print';
		if (style.styleSheet){
		style.styleSheet.cssText = css;
		} else {
		style.appendChild(document.createTextNode(css));
		}
		head.appendChild(style);
        window.print();
        document.body.innerHTML = originalContents;
		document.getElementById('header').style.display = 'none';
    }

	// EXPORT TO EXCEL
	function html_table_to_excel(type){
		var data = document.getElementById('printResult');
		var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
		XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
		XLSX.writeFile(file, 'report.' + type);
	}

	const export_button = document.getElementById('export_button');
	export_button.addEventListener('click', () =>  {
		html_table_to_excel('xlsx');
	});
</script>