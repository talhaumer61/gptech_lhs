<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '73', 'view' => '1'))){

    $sql2       = "";
    $sql3       = "";
    $sql4       = "";
    $std_status = "";
    $id_session = "";
    $start_date = "";
    $end_date   = "";

    // GENDER
    if($_POST['std_gender'] && $_POST['std_gender']=='Male'){
        $sql1 = "COUNT(CASE WHEN s.std_gender = '".$_POST['std_gender']."' THEN 1 else null end) as boys,";
        $std_gender = $_POST['std_gender'];
    }elseif($_POST['std_gender'] && $_POST['std_gender']=='Female'){
        $sql1 = "COUNT(CASE WHEN s.std_gender = '".$_POST['std_gender']."' THEN 1 else null end) as girls,";
        $std_gender = $_POST['std_gender'];
    }else{
        $sql1 = "COUNT(CASE WHEN s.std_gender = 'Male' THEN 1 else null end) as boys,
                COUNT(CASE WHEN s.std_gender = 'Female' THEN 1 else null end) as girls,";
        $std_gender = "";
    }

    // STATUS
    if($_POST['std_status']){
        $sql2 = "AND s.std_status = '".$_POST['std_status']."'";
        $std_status = $_POST['std_status'];
    }
    // DATES
    if($_POST['start_date'] && $_POST['end_date']){
        $sql3 = "AND (s.std_admissiondate BETWEEN '".date('Y-m-d' , strtotime(cleanvars($_POST['start_date'])))."' AND '".date('Y-m-d' , strtotime(cleanvars($_POST['end_date'])))."')";
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
    }
    // SESSION
    if($_POST['id_session']){
        $sql4 = "AND s.id_session = '".$_POST['id_session']."'";
        $id_session = $_POST['id_session'];
    }

    echo'
    <style>
    .ui-datepicker-calendar {
        display: none;
    }
    </style>
    <title>Class Strength Report| '.TITLE_HEADER.'</title>
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Class Strength Report</h2>
        </header>
        <div class="row">
            <div class="col-md-12">
                <section class="panel panel-featured panel-featured-primary">
                    <header class="panel-heading">
                        <h2 class="panel-title"><i class="fa fa-list"></i>  Select Filters </h2>
                    </header>
                    <form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                        <div class="panel-body">
                            <div class="row mb-lg">
                                <div class="col-md-3">
                                    <label class="control-label">Session <span class="required">*</span></label>
                                    <select class="form-control" data-plugin-selectTwo data-width="100%" name="id_session" name="id_session" required>
                                        <option value="">Select</option>';
                                        $sqlSession	= $dblms->querylms("SELECT session_id, session_name 
                                                                        FROM ".SESSIONS."
                                                                        WHERE session_status = '1' ORDER BY session_id ASC");
                                        while($valSession = mysqli_fetch_array($sqlSession)) {
                                            echo '<option value="'.$valSession['session_id'].'" '.($id_session == $valSession['session_id'] ? 'selected' : '').'>'.$valSession['session_name'].'</option>';
                                        }
                                        echo '
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label">Gender </label>
                                    <select class="form-control" data-plugin-selectTwo data-width="100%" id="std_gender" name="std_gender">
                                        <option value="">Select</option>';
                                        foreach($gender as $gndr){
                                            echo '<option value="'.$gndr.'" '.($std_gender==$gndr ? 'selected' : '').'>'.$gndr.'</option>';
                                        }
                                        echo'
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label">Status </label>
                                    <select class="form-control" data-plugin-selectTwo data-width="100%" id="std_status" name="std_status">
                                        <option value="">Select</option>';
                                        foreach($admstatus as $stat){
                                            echo '<option value="'.$stat['id'].'"'; if($std_status == $stat['id']){ echo'selected';} echo'>'.$stat['name'].'</option>';
                                        }
                                        echo'
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class=" control-label">Date </label>
                                        <div class="input-daterange input-group" data-plugin-datepicker="" data-plugin-options="{&quot;format&quot;: &quot;dd-mm-yyyy&quot;}">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="form-control" value="'.$start_date.'" name="start_date" >
                                            <span class="input-group-addon">to</span>
                                            <input type="text" class="form-control" value="'.$end_date.'" name="end_date" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <center>
                                <button type="submit" name="view_students" id="view_students" class="btn btn-primary"><i class="fa fa-search"></i> Show Result</button>
                            </center>
                        </div>
                    </form>
                </section>';
                if(isset($_POST['view_students'])){
                    echo'
                    <section class="panel panel-featured panel-featured-primary">
                        <header class="panel-heading">
                            <h2 class="panel-title"><i class="fa fa-list"></i> Class Strength List '; if($start_date && $end_date){echo' From '.date('d, M, Y' , strtotime($start_date)).' To '.date('d, M, Y' , strtotime($end_date)).' ';} echo'</h2>
                        </header>
                        <div class="panel-body">';
                            $sqlStudent	= $dblms->querylms("SELECT c.class_id, c.class_name, cs.section_id, cs.section_name,
                                                            COUNT(s.std_id) as total_students, $sql1
                                                            COUNT(cs.section_id) as no_of_sections
                                                            FROM ".CLASSES." as c
                                                            INNER JOIN ".CLASS_SECTIONS." cs ON (
                                                                cs.id_class = c.class_id
                                                                AND cs.id_campus        = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                                                                AND cs.is_deleted       = '0'
                                                                AND cs.section_status   = '1'
                                                            )
                                                            LEFT JOIN ".STUDENTS." s ON (
                                                                s.id_section = cs.section_id
                                                                AND s.is_deleted = '0'
                                                                $sql2 $sql3 $sql4
                                                            )
                                                            WHERE c.is_deleted  = '0'
                                                            AND c.class_status  = '1'
                                                            GROUP BY cs.section_id
                                                            ORDER BY c.class_id");
                            if(mysqli_num_rows($sqlStudent) > 0){
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
                                            <h4 style="text-align: center;"><b>Class Wise Students Details</b></h4>
                                            <br>
                                            <div>
                                                <h5 class="mb-md">'; if($start_date && $end_date){echo' From '.date('d M, Y' , strtotime($start_date)).' To '.date('d M, Y' , strtotime($end_date)).' ';} echo'
                                                    <span style="margin-left:35rem">'.(!empty($std_status) ? 'Status: '.get_status($std_status).'' : '').'</span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-condensed">
                                                <thead>
                                                    <tr class="h5 text-dark">
                                                        <th width="40" class="center">Sr.</th>
                                                        <th>Class</th>
                                                        <th width="120">Section</th>';
                                                        if($_POST['std_gender']=='Male'){
                                                            echo'<th width="120" class="center">Boys</th>';
                                                        }elseif($_POST['std_gender']=='Female'){
                                                            echo'<th width="120" class="center">Girls</th>';
                                                        }else{
                                                            echo'
                                                            <th width="120" class="center">Boys</th>
                                                            <th width="120" class="center">Girls</th>';
                                                        }
                                                        echo'
                                                        <th width="120" class="center">Total Students</th>     
                                                    </tr>
                                                </thead>
                                                <tbody>';
                                                    $srno = 0;
                                                    $totalBoys      =   0;
                                                    $totalGirls     =   0;
                                                    $totalAll       =   0;
                                                    while($valStudent = mysqli_fetch_array($sqlStudent)){
                                                        $totalBoys      =   $totalBoys + $valStudent['boys'];
                                                        $totalGirls     =   $totalGirls + $valStudent['girls'];
                                                        $totalAll       =   $totalAll + $valStudent['total_students'];
                                                        $srno++;
                                                        echo'
                                                        <tr>
                                                            <td class="center">'.$srno.'</td>
                                                            <td>'.$valStudent['class_name'].'</td>
                                                            <td>'.$valStudent['section_name'].'</td>';
                                                            if(isset($valStudent['boys'])){
                                                                echo'<td class="center">'.$valStudent['boys'].'</td>';
                                                            }
                                                            if(isset($valStudent['girls'])){
                                                                echo'<td class="center">'.$valStudent['girls'].'</td>';
                                                            }
                                                            echo'
                                                            <td class="center">'.$valStudent['total_students'].'</td>
                                                        </tr>';
                                                    }
                                                    echo '
                                                    <tr>
                                                        <th class="center" colspan="3">Grand Total</th>';
                                                        if($_POST['std_gender']=='Male'){
                                                            echo '<th class="center">'.$totalBoys.'</th>';
                                                        }elseif($_POST['std_gender']=='Female'){
                                                            echo '<th class="center">'.$totalGirls.'</th>';
                                                        }else{
                                                            echo '<th class="center">'.$totalBoys.'</th>
                                                                <th class="center">'.$totalGirls.'</th>';
                                                        }
                                                        echo '
                                                        <th class="center">'.$totalAll.'</th>
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
                    </section>';
                }
                echo'
            </div>
        </div>
    </section>';
}else{
    header("Location: dashboard.php");
}
?>
<script type="text/javascript">
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
    jQuery(document).ready(function($) {	
        var datatable = $('#table_export').dataTable({
            bAutoWidth : false,
            ordering: false,
        });
    });

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