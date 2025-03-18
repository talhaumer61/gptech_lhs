<div class="row">
    <div class="col-md-12">
		<section class="panel">
			<div class="panel-body">
				<div id="campuswisestaff" style="height: 400px;"></div>
			</div>
		</section>
    </div>
</div>
<script type="application/javascript">
	// CAMPUS WISE STAFF
	Highcharts.chart('campuswisestaff', {
		chart: {
			type: 'column',
			backgroundColor: 'transparent'
		},
		title: {
			text: 'Campus Wise Staff'
		},
		xAxis: {
			categories: [
				<?php
					$campusid = array();
					$sqllmscampus	= $dblms->querylms("SELECT campus_id, campus_code 
														FROM ".CAMPUS."
														WHERE campus_status = '1'
														$sqlCampus
														ORDER BY campus_id ASC");
					while($value_camp = mysqli_fetch_array($sqllmscampus)) {
						$campusid[] = $value_camp['campus_id'];
						echo '"'.$value_camp['campus_code'].'",';
					}
				?>
			],
			crosshair: true
		},
		yAxis: {
			min: 0,
			title: {
				text: 'No of Employes'
			}
		},
		tooltip: {
			crosshairs: true,
			shared: true
		},
		credits: {
			enabled: false
		},
		legend: {
			itemStyle: { "color": "#505461"},
			itemHoverStyle: { "color": "#505461" }
		},
		plotOptions: {
			bar: {
				dataLabels: {
					enabled: true
				}
			}
		},
		series: [{
			name: 'Total No. of Employes',
			data: [
					<?php
						foreach($campusid as $id){
							$sqllmsstaff	= $dblms->querylms("SELECT COUNT(emply_id) as total
																FROM ".EMPLOYEES."
																WHERE emply_id != '' 
																AND id_deleted = '0'
																AND id_campus  = '".$id."'");
							$value_staff = mysqli_fetch_array($sqllmsstaff);
							echo '{y:'.$value_staff['total'].'},';
						}
					?>
			]
    	}]
	});
</script>