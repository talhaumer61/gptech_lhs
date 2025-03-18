<?php
echo'
<div class="col-md-6">
    <section class="panel">
        <div class="panel-body">
            <div id="emplyAttendancegraph" style="height: 400px;"></div>
        </div>
    </section>
</div>';
?>
<script type="application/javascript">
//TOTAL SCHOOL ATTENDANCE GRAPH SCRIPT
	Highcharts.chart('emplyAttendancegraph', {
		chart: {
			type: 'areaspline',
			backgroundColor: 'transparent'
		},
		title: {
						text: 'Employees Attendance'
		},
		xAxis: {
			categories:[
				<?php
					$sqllmsEmplAtt	= $dblms->querylms("SELECT a.id, a.dated
														FROM ".EMPLOYEES_ATTENDCE." a
														WHERE a.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
														AND a.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."' 
														ORDER BY a.dated");
					$idEmplAtt = array();
					while($valEmplAtt = mysqli_fetch_array($sqllmsEmplAtt)) {
						$idEmplAtt[] = $valEmplAtt['id'];
						echo '"'.$valEmplAtt['dated'].'",';
					}
				?>
			],
			crosshair: true
		},
		yAxis: {
			min: 0,
			title: {
				text: 'No. of Employees'
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
				'<td style="padding:0"><b>{point.y}</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
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
		series: [
			{
				name: 'Present',
				data: [
					<?php
						foreach($idEmplAtt as $idAttEmpl){
							$sqllmsEmplPresDet	= $dblms->querylms("SELECT SUM(status) as totPresent
																FROM ".EMPLOYEES_ATTENDCE_DETAIL."
																WHERE id_setup = '".$idAttEmpl."' AND status = '1' ");
							$valEmpPresDet = mysqli_fetch_array($sqllmsEmplPresDet);
							if($valEmpPresDet['totPresent'] > 0){
								echo"{ y:".$valEmpPresDet['totPresent']."},";
							}
							else{
								echo"{ y:0},";
							}
						}
					?>
					// { y:0},{ y:4},{ y:0},{ y:0},{ y:0},{ y:0},{ y:6},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:6},{ y:0},{ y:5},{ y:0},			
					// {y:6},{y:2},{y:9},
				
				]

			}, {
				name: 'Absent',
				data: [
					<?php
					foreach($idEmplAtt as $idAttEmpl){
						$sqllmsEmplAbDet	= $dblms->querylms("SELECT SUM(status) as totAbsent
															FROM ".EMPLOYEES_ATTENDCE_DETAIL."
															WHERE id_setup = '".$idAttEmpl."' AND status = '2' ");
						$valEmplAbDet = mysqli_fetch_array($sqllmsEmplAbDet);
						if($valEmplAbDet['totAbsent'] > 0){
							echo"{ y:".$valEmplAbDet['totAbsent']."},";
						}
						else{
							echo"{ y:0},";
						}
					}
					?>
					// { y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:1},{ y:0},
				],
				color: 'rgba(192,2,22,0.75)'
			}, {
				name: 'Late',
				data: [
					<?php
					foreach($idEmplAtt as $idAttEmpl){
						$sqllmsStdAbDet	= $dblms->querylms("SELECT COUNT(status) as totLate
															FROM ".EMPLOYEES_ATTENDCE_DETAIL."
															WHERE id_setup = '".$idAttEmpl."' AND status = '4' ");
						$valStdAbDet = mysqli_fetch_array($sqllmsStdAbDet);
						if($valStdAbDet['totLate'] > 0){
							echo"{ y:".$valStdAbDet['totLate']."},";
						}
						else{
							echo"{ y:0},";
						}
					}
					?>
					// { y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:0},{ y:1},{ y:0},
				],
				color: 'rgb(119,119,119)'
			}
		]
	});
</script>