<?php
$sqlEmp = $dblms->querylms("SELECT e.emply_id
                                FROM ".EMPLOYEES." e
                                WHERE e.id_campus	= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                AND e.id_loginid	= '".$_SESSION['userlogininfo']['LOGINIDA']."'
                                AND e.is_deleted	= '0'
                            ");
$valEmp = mysqli_fetch_array($sqlEmp);

$sqlToday = $dblms->querylms("SELECT ad.status
                                FROM ".EMPLOYEES_ATTENDCE." a
                                INNER JOIN ".EMPLOYEES_ATTENDCE_DETAIL." ad ON ad.id_setup = a.id
                                WHERE a.id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                                AND a.dated = '".date('Y-m-d')."'
                                GROUP BY ad.id_emply
                            ");
if(mysqli_num_rows($sqlToday)>0){
    $valToday = mysqli_fetch_array($sqlToday);
    if($valToday['status']==1){
        $status = 'Present';
        $color = 'rgb(0, 143, 251)';
    }elseif($valToday['status']==2){
        $status = 'Absent';
        $color = 'rgb(0, 227, 150)';
    }elseif($valToday['status']==3){
        $status = 'Leave';
        $color = 'rgb(254, 176, 25)';
    }elseif($valToday['status']==4){
        $status = 'Late';
        $color = 'rgb(255, 69, 96)';
    }
}else{
    $status = 'Not Marked';
    $color = '#777';
}

$sqllms	= $dblms->querylms("SELECT
                            COUNT(CASE WHEN ad.status = '1' THEN 1 else null end) as `present`, 
                            COUNT(CASE WHEN ad.status = '2' THEN 1 else null end) as `absent`, 
                            COUNT(CASE WHEN ad.status = '3' THEN 1 else null end) as `leave`, 
                            COUNT(CASE WHEN ad.status = '4' THEN 1 else null end) as `late`
                            FROM ".EMPLOYEES_ATTENDCE." a
                            INNER JOIN ".EMPLOYEES_ATTENDCE_DETAIL." ad ON ad.id_setup = a.id
                            WHERE a.id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                            AND ad.id_emply     = '".$valEmp['emply_id']."'
                            GROUP BY ad.id_emply
                        ");
$vallms = mysqli_fetch_array($sqllms);
echo'
<style>
.attendance-status{
    height: 100px;
    width: 100px;
    line-height: 100px;
    color: #fff;
    background: '.$color.';
    border-radius: 50%;
    font-weight: bold;
    text-align: center;
}
.attendance-status span{
    text-shadow: 1px 1px 4px #232323;
}
.status-title{
    width: 100px;
    text-align: center;
    padding-bottom: 5px;
}
</style>
<section class="panel panel-featured panel-featured-primary">
    <header class="panel-heading">
        <h2 class="panel-title"><i class="fa fa-line-chart"></i>  Attendance</h2>
    </header>
    <div class="panel-body">
        <div class="col-md-9">
            <div id="chart"></div>
        </div>
        <div class="col-md-3">
            <div class="status-title">
                Today Status
            </div>
            <div class="attendance-status shadow-style-2">
                <span>'.$status.'</span>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
jQuery(document).ready(function($) {
    var options = {
        series: ['.$vallms['present'].', '.$vallms['absent'].', '.$vallms['leave'].', '.$vallms['late'].'],
        chart: {
            width: 400,
            type: "pie",
        },
        labels: ["Present", "Absent", "Leave", "Late"],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: "bottom"
                }
            }
        }]
    };
    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
});
</script>';
?>