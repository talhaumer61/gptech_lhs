<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '73', 'view' => '1'))){

    $campus      = '';
    $campus_name = '';
    if(isset($_POST['campus'])){
        $array       = explode('|', $_POST['campus']);
        $campus      = $array[0];
        $campus_name = $array[1];
    }
    $today = date('d-m-Y');	

    $sql1 = "";
    $sql2 = "";
    $sql3 = "";
    $sql4 = "";
    $sql5 = "";
    $std_status = "";
    $std_gender = "";
    $start_date = $today;
    $end_date = $today;
    $id_class = "";
    $id_session = "";

    // GENDER
    if($_POST['std_gender']){
        $sql1 = "AND st.std_gender = '".$_POST['std_gender']."' ";
        $std_gender = $_POST['std_gender'];
    }

    // STATUS
    if($_POST['std_status']){
        $sql2 = "AND st.std_status = '".$_POST['std_status']."'";
        $std_status = $_POST['std_status'];
    }
    // DATES
    if($_POST['start_date'] && $_POST['end_date']){
        $sql3 = "AND (st.std_admissiondate BETWEEN '".date('Y-m-d' , strtotime(cleanvars($_POST['start_date'])))."' AND '".date('Y-m-d' , strtotime(cleanvars($_POST['end_date'])))."')";
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
    }
    // CLASS
    if($_POST['id_class']){
        $sql4 = "AND st.id_class = '".$_POST['id_class']."'";
        $id_class = $_POST['id_class'];
    }

    // SESSION
    if($_POST['id_session']){
        $sql5 = "AND st.id_session = '".$_POST['id_session']."'";
        $id_session = $_POST['id_session'];
    }

    echo'
    <style>
    .ui-datepicker-calendar {
        display: none;
    }
    </style>
    <title>Class Contact Report| '.TITLE_HEADER.'</title>
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Class Contact Report</h2>
        </header>
        <div class="row">
            <div class="col-md-12">
                <section class="panel panel-featured panel-featured-primary">
                    <header class="panel-heading">
                        <h2 class="panel-title"><i class="fa fa-list"></i>  Select Filters </h2>
                    </header>
                    <form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                        <div class="panel-body">
                            <div class="row mb-sm">
                                <div class="col-md-4">
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Campus <span class="required">*</span></label>
                                        <select data-plugin-selectTwo data-width="100%" name="campus" id="campus" required title="Must Be Required" class="form-control populate">
                                            <option value="">Select</option>';
                                        $sqllmscampus	= $dblms->querylms("SELECT c.campus_id, c.campus_name
                                                                                FROM ".CAMPUS." c  
                                                                                WHERE c.campus_id != '' AND campus_status = '1'
                                                                                ORDER BY c.campus_name ASC");
                                            while($value_campus = mysqli_fetch_array($sqllmscampus)){
                                                echo'<option value="'.$value_campus['campus_id'].'|'.$value_campus['campus_name'].'" '.($campus == $value_campus['campus_id'] ? 'selected' : '').'>'.$value_campus['campus_name'].'</option>';
                                            }
                                            echo'
                                            </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Class <span class="required">*</span></label>
                                    <select class="form-control" data-plugin-selectTwo data-width="100%" name="id_class" name="id_class" required>
                                        <option value="">Select</option>';
                                        $sqlClass = $dblms->querylms("SELECT class_id, class_name 
                                                                        FROM ".CLASSES."
                                                                        WHERE class_status = '1' ORDER BY class_id ASC");
                                        while($valClass = mysqli_fetch_array($sqlClass)){
                                            echo '<option value="'.$valClass['class_id'].'" '.($id_class == $valClass['class_id'] ? 'selected' : '').'>'.$valClass['class_name'].'</option>';
                                        }
                                        echo '
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-sm">
                                <div class="col-md-4">
                                    <label class="control-label">Gender </label>
                                    <select class="form-control" data-plugin-selectTwo data-width="100%" id="std_gender" name="std_gender">
                                        <option value="">Select</option>';
                                        foreach($gender as $gndr){
                                            echo '<option value="'.$gndr.'" '.($std_gender==$gndr ? 'selected' : '').'>'.$gndr.'</option>';
                                        }
                                        echo'
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Status </label>
                                    <select class="form-control" data-plugin-selectTwo data-width="100%" id="std_status" name="std_status">
                                        <option value="">Select</option>';
                                        foreach($admstatus as $stat){
                                            echo '<option value="'.$stat['id'].'"'; if($std_status == $stat['id']){ echo'selected';} echo'>'.$stat['name'].'</option>';
                                        }
                                        echo'
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class=" control-label">Date </label>
                                    <div class="input-daterange input-group" data-plugin-datepicker="" data-plugin-options="{&quot;format&quot;: &quot;dd-mm-yyyy&quot;}">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input type="text" class="form-control" value="'.$start_date.'" name="start_date" >
                                        <span class="input-group-addon">to</span>
                                        <input type="text" class="form-control" value="'.$end_date.'" name="end_date" max="'.$today.'">
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
                            <h2 class="panel-title"><i class="fa fa-list"></i> Class Contact List ';
                                if($start_date && $end_date){
                                    echo' From '.date('d M, Y' , strtotime($start_date)).' To '.date('d M, Y' , strtotime($end_date)).' ';
                                }
                                echo'
                            </h2>
                        </header>
                        <div class="panel-body">';
                            $sqlStudent	= $dblms->querylms("SELECT c.class_name, cs.section_name, st.*
                                                            FROM ".STUDENTS." as st
                                                            INNER JOIN ".CLASSES." c ON c.class_id = st.id_class
                                                            INNER JOIN ".CLASS_SECTIONS." cs ON (cs.section_id = st.id_section AND cs.is_deleted = '0' AND cs.section_status = '1')
                                                            AND st.id_campus = '".cleanvars($campus)."' 
                                                            AND st.is_deleted = '0'
                                                            $sql1 $sql2 $sql3 $sql4 $sql5
                                                            ORDER BY st.std_id");
                            if(mysqli_num_rows($sqlStudent) > 0){
                                echo'
                                <div class="text-right mr-lg on-screen">
                                    <button id="print-button" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-print"></i> Print</button>
                                    <!--<button id="export-button" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Excel</button>-->
                                </div>
                                <div id="printResult">
                                    <div class="invoice mt-md">
                                        <div class="table-responsive" id="student-table">
                                            <table class="table table-bordered table-striped table-condensed" id="myTable">
                                                <thead>
                                                    <tr class="h5 text-dark row-checkbox">
                                                        <th width="40" class="center"><input type="checkbox" class="column-checkbox" data-col="0"></th>
                                                        <th><input type="checkbox" class="column-checkbox" data-col="1"></th>
                                                        <th><input type="checkbox" class="column-checkbox" data-col="2"></th>
                                                        <th><input type="checkbox" class="column-checkbox" data-col="3"></th>
                                                        <th width="120"><input type="checkbox" class="column-checkbox" data-col="4"></th>
                                                        <th width="70"><input type="checkbox" class="column-checkbox" data-col="5"></th>
                                                        <th width="80"><input type="checkbox" class="column-checkbox" data-col="6"></th>
                                                        <th width="90"><input type="checkbox" class="column-checkbox" data-col="7"></th>
                                                        <th width="90"><input type="checkbox" class="column-checkbox" data-col="8"></th>
                                                    </tr>
                                                    <tr class="h5 text-dark">
                                                        <th width="40" class="center">Sr.</th>
                                                        <th>Reg No.</th>
                                                        <th>Student Name</th>
                                                        <th>Father Name</th>
                                                        <th width="120" class="center">CNIC</th>
                                                        <th width="70" class="center">Section</th>
                                                        <th width="80" class="center">Area/City</th>
                                                        <th width="90" class="center">Phone No</th>
                                                        <th width="90" class="center">What\'s App</th>
                                                    </tr>
                                                </thead>
                                                <tbody>';
                                                    $srno = 0;
                                                    while($valStudent = mysqli_fetch_array($sqlStudent)){
                                                        $srno++;
                                                        echo'
                                                        <tr>
                                                            <td class="center">'.$srno.'</td>
                                                            <td>'.$valStudent['std_regno'].'</td>
                                                            <td>'.$valStudent['std_name'].'</td>
                                                            <td>'.$valStudent['std_fathername'].'</td>
                                                            <td class="center">'.$valStudent['std_nic'].'</td>
                                                            <td class="center">'.$valStudent['section_name'].'</td>
                                                            <td class="center">'.$valStudent['std_city'].'</td>
                                                            <td class="center">'.$valStudent['std_phone'].'</td>
                                                            <td class="center">'.$valStudent['std_whatsapp'].'</td>
                                                        </tr>';
                                                    }
                                                    echo'
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>';
                            }else{
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
    $(document).ready(function() {

        // Event listener for the print button
        $("#print-button").click(function() {

            // Get an array of the selected columns
            var selectedCols = $(".column-checkbox:checked").map(function() {
                return $(this).data("col");
            }).get();

            // Print the selected table
            const printWindow = window.open('', 'Print Window');
            printWindow.document.write('<html><head><title>Print Selected Columns</title>');
            printWindow.document.write('<link rel="stylesheet" href="assets/stylesheets/theme.css" /><link rel="stylesheet" href="assets/stylesheets/skins/default.css" /><link rel="stylesheet" href="assets/stylesheets/theme-custom.css"><link rel="stylesheet" href="assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" /><link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />');

            // Copy the stylesheets to the new document
            $("link[rel='stylesheet']").each(function() {
                printWindow.document.write($(this).prop('outerHTML'));
            });

            // Add a stylesheet for hiding non-selected columns
            printWindow.document.write('<style>table td, table th {display: none;}');
            for (var i = 0; i < selectedCols.length; i++) {
                printWindow.document.write('table td:nth-child(' + (selectedCols[i] + 1) + '), table th:nth-child(' + (selectedCols[i] + 1) + ') {display: table-cell;}');
            }
            printWindow.document.write('</style></head><body>');

            const selectedTable = document.createElement('div');
            selectedTable.classList.add('invoice', 'mt-md');
            selectedTable.innerHTML = `
                <style>
                    .row-checkbox{
                        display: none;
                    }
                </style>
                <div id="header">
                    <h2 style="text-align: center;">
                        <img src="uploads/logo.png" class="img-fluid" style="width: 70px; height: 70px;"> 
                        <span><b>Laurel Home Schoool</b></span>
                    </h2>
                    <h4 style="text-align: center;"><b>Students Contact List</b></h4>
                    <br>
                    <div>
                        <h5 class="mb-md">
                            <?php 
                            if($start_date && $end_date){
                                echo' From '.date('d M, Y' , strtotime($start_date)).' To '.date('d M, Y' , strtotime($end_date)).' ';
                            }
                            if($std_gender){
                                echo'<span style="margin-left:12rem"> Gender: '.$std_gender.'</span>';
                            }
                            if($std_status){
                                echo'<span style="margin-left:12rem"> Status: '.get_status($std_status).'</span>';
                            }
                            ?>
                        </h5>
                    </div>
                </div>
            `;

            // Copy the selected columns to the new document            
            printWindow.document.write(selectedTable.outerHTML);
            printWindow.document.write('<table class="table table-bordered table-striped table-condensed">' + $("#myTable").html() + '</table>');

            // Close the new document and print it
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        });

    });
</script>