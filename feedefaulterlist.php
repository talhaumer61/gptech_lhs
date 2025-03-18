<?php 
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))){
	include_once("include/header.php");
    echo '
    <title> Fee Defaulter List | '.TITLE_HEADER.'</title>
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Fee Defaulter List </h2>
        </header>
        <div class="row">
            <div class="col-md-12">
                <section class="panel panel-featured panel-featured-primary">
                    <header class="panel-heading">
                        <h2 class="panel-title">
                            <i class="fa fa-list"></i> Fee Defaulter List
                            <button id="export_button" class="btn btn-success btn-xs pull-right"><i class="fa fa-file-excel-o"></i> Excel</button>  
                        </h2>                      
                    </header>
                    <div class="panel-body">';
                        
                        $sqllmsFeeDefaulter	= $dblms->querylms("SELECT f.id, f.status, f.id_month, GROUP_CONCAT(f.challan_no) as challan_no, f.issue_date, f.due_date, f.paid_date, SUM(f.total_amount) as total_amount,
                                                                    c.class_name, cs.section_name, s.session_name, st.std_id, st.std_name, st.std_regno
                                                                    FROM ".FEES." f				   
                                                                    INNER JOIN ".CLASSES." c ON c.class_id = f.id_class	 	
                                                                    LEFT JOIN ".CLASS_SECTIONS." cs ON cs.section_id = f.id_section							 
                                                                    INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session							 
                                                                    INNER JOIN ".STUDENTS." st ON st.std_id = f.id_std
                                                                    WHERE f.status = '2'
                                                                    AND f.is_deleted != '1'
                                                                    AND f.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                                                                    GROUP By f.id_std   
                                                                    ORDER BY f.id DESC");
                        if(mysqli_num_rows($sqllmsFeeDefaulter) > 0){
                            echo '
                            <table id="printResult" class="table table-bordered table-striped table-condensed mb-none">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;">#</th>
                                        <th>Challan #</th>
                                        <th>Reg #</th>
                                        <th>Student</th>
                                        <th>Class</th>
                                        <th>Session</th>
                                        <th width="70px;" style="text-align:center;">Status</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                    $srno = 0;
                                    while($valueFee = mysqli_fetch_array($sqllmsFeeDefaulter)) {
                                        $srno++;
                                        echo '
                                        <tr>
                                            <td style="text-align:center;">'.$srno.'</td>
                                            <td>'.$valueFee['challan_no'].'</td>
                                            <td>'.$valueFee['std_regno'].'</td>
                                            <td>'.$valueFee['std_name'].'</td>
                                            <td>'.$valueFee['class_name'].' '; if($valueFee['section_name']){echo' ('.$valueFee['section_name'].') ';} echo'</td>
                                            <td>'.$valueFee['session_name'].'</td>
                                            <td style="text-align:center;">'.get_payments($valueFee['status']).'</td>
                                            <td>'.number_format(round($valueFee['total_amount'])).'</td>
                                        </tr>';
                                    }
                                    echo '
                                </tbody>
                            </table>';
                        }else{
                            echo'<div class="panel-body"><h2 class="text text-center text-danger mt-lg">No Record Found!</h2></div>';
                        }
                        echo'
                    </div>
                </section>
            </div>
        </div>
    </section>';
    ?>
    <script type="text/javascript">
       
    </script>
    <?php
	include_once("include/footer.php");
}else{
    header("Location: dashboard.php");
}

?>
<script>
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