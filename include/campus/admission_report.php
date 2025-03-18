<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '73', 'view' => '1'))){
    $today = date('d-m-Y');	
    if(isset($_POST['start_date'])){$start_date = $_POST['start_date'];}else{$start_date = $today;}
    if(isset($_POST['end_date'])){$end_date = $_POST['end_date'];}else{$end_date = $today;}
    if(isset($_POST['id_class'])){$class = $_POST['id_class'];}else{$class = "";}
    if(isset($_POST['id_section'])){$section = $_POST['id_section'];}else{$section = "";}

    echo'
    <style>
        .ui-datepicker-calendar {
            display: none;
        }
    </style>
    <title>Admission Report| '.TITLE_HEADER.'</title>
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Admission Report</h2>
        </header>
        <div class="row">
            <div class="col-md-12">
                <section class="panel panel-featured panel-featured-primary">
                    <header class="panel-heading">
                        <h2 class="panel-title"><i class="fa fa-list"></i>  Select </h2>
                    </header>
                    <form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                        <div class="panel-body">
                            <div class="row mb-lg">
                                <div class="col-md-4">
                                    <label class="control-label">Class </label>
                                    <select class="form-control" data-plugin-selectTwo data-width="100%" name="id_class" name="id_class" onchange="get_classsection(this.value)">
                                    <option value="">Select</option>';
                                        $sqllmsclass	= $dblms->querylms("SELECT class_id, class_name 
                                                                                FROM ".CLASSES." 
                                                                                WHERE class_status = '1' ORDER BY class_id ASC");
                                        while($value_class 	= mysqli_fetch_array($sqllmsclass)) {
                                            if($value_class['class_id'] == $class){
                                                echo '<option value="'.$value_class['class_id'].'" selected>'.$value_class['class_name'].'</option>';
                                            }
                                            else{
                                                echo '<option value="'.$value_class['class_id'].'">'.$value_class['class_name'].'</option>';
                                            }
                                        }
                                    echo '
                                    </select>
                                </div>
                                <div class="col-md-4">                           
                                    <div class="form-group" id="getclasssection">
                                        <label class="control-label">Section </label>
                                        <select class="form-control" data-plugin-selectTwo data-width="100%" id="id_section" name="id_section">
                                            <option value="">Select</option>';
                                            $sqllmscls	= $dblms->querylms("SELECT section_id, section_name 
                                                                                    FROM ".CLASS_SECTIONS."
                                                                                    WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                                                                    AND section_status = '1' AND id_class = '".$class."' AND is_deleted != '1'
                                                                                    ORDER BY section_name ASC");
                                            while($valuecls = mysqli_fetch_array($sqllmscls)){
                                                echo '<option value="'.$valuecls['section_id'].'" '.($valuecls['section_id']==$section ? 'selected' : '').'>'.$valuecls['section_name'].'</option>';
                                            }
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">				
                                    <div class="form-group">
                                        <label class=" control-label">Date </label>
                                        <div class="input-daterange input-group" data-plugin-datepicker="" data-plugin-options="{&quot;format&quot;: &quot;dd-mm-yyyy&quot;}">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="form-control" value="'.$start_date.'" name="start_date">
                                            <span class="input-group-addon">to</span>
                                            <input type="text" class="form-control" value="'.$end_date.'" name="end_date" max="'.$today.'">
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
                if(isset($_POST['view_students']) && ( ($start_date && $end_date) || $class || $section)){
                    echo'
                    <section class="panel panel-featured panel-featured-primary">
                        <header class="panel-heading">
                            <h2 class="panel-title"><i class="fa fa-list"></i> Admission List'; if($start_date && $end_date){echo' From '.date('d, M, Y' , strtotime($start_date)).' To '.date('d, M, Y' , strtotime($end_date)).' ';} echo'</h2>
                        </header>
                        <div class="panel-body">';
                            if($class && $section){
                                $sql2 = " AND s.id_class = '".$class."' AND s.id_section = '".$section."' ";
                            }else{
                                $sql2 = "";
                            }
                            if($start_date &&  $end_date){
                                $sql3 = "AND (s.std_admissiondate BETWEEN '".date('Y-m-d' , strtotime(cleanvars($start_date)))."' AND '".date('Y-m-d' , strtotime(cleanvars($end_date)))."')";
                            }else{
                                $sql3 = "";
                            }
                            
                            $sqllmsstudent	= $dblms->querylms("SELECT s.std_name, s.std_fathername, s.std_phone, s.std_whatsapp, s.std_rollno, s.std_regno, s.std_admissiondate, s.std_photo, c.class_name, se.session_name
                                                                FROM ".STUDENTS." s
                                                                INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
                                                                INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
                                                                WHERE s.std_status = '1' AND s.is_deleted != '1' AND s.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                                                $sql2 $sql3
                                                                ORDER BY s.id_class ASC");
                            if(mysqli_num_rows($sqllmsstudent) > 0){
                                echo'
                                <div class="text-right mr-lg on-screen">
                                    <button onclick="print_report(\'printResult\')" class="mr-xs btn btn-primary btn-sm"><i class="glyphicon glyphicon-print"></i> Print</button>
                                    <button id="export_button" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Excel</button>
                                </div>
                                <div id="printResult">
                                    <div class="invoice mt-md">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-condensed">
                                                <thead>
                                                    <tr class="h5 text-dark">
                                                        <th width="40" class="center">Sr.</th>
                                                        <th width="100">Date</th>
                                                        <th width="80">Photo</th>
                                                        <th>Name</th>
                                                        <th>F.Name</th>
                                                        <th>Reg No #</th>
                                                        <th>Roll #</th>
                                                        <th>Class</th>
                                                        <th>Session</th>
                                                        <th>Phone</th>
                                                        <th>Whatsapp</th>
                                                    </tr>
                                                </thead>
                                                <tbody>';
                                                    $srno = 0;
                                                    while($value_stu = mysqli_fetch_array($sqllmsstudent)) {
                                                        $srno++;
                                                        if($value_stu['std_photo']) { 
                                                            $photo = "uploads/images/students/".$value_stu['std_photo']."";
                                                        }else{
                                                            $photo = "uploads/default-student.jpg";
                                                        }
                                                        echo'
                                                        <tr>
                                                            <td class="center">'.$srno.'</td>
                                                            <td>'.date('d, M, Y' , strtotime($value_stu['std_admissiondate'])).'</td>
                                                            <td><img src="'.$photo.'" style="width:40px; height:40px;"></td>
                                                            <td>'.$value_stu['std_name'].'</td>
                                                            <td>'.$value_stu['std_fathername'].'</td>
                                                            <td>'.$value_stu['std_regno'].'</td>
                                                            <td>'.$value_stu['std_rollno'].'</td>
                                                            <td>'.$value_stu['class_name'].'</td>
                                                            <td>'.$value_stu['session_name'].'</td>
                                                            <td>'.$value_stu['std_phone'].'</td>
                                                            <td>'.$value_stu['std_whatsapp'].'</td>
                                                        </tr>';
                                                    }
                                                    echo '
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
    function print_report(printResult) {
        var printContents = document.getElementById(printResult).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
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

    function get_classsection(id_class) {  
        $("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
        $.ajax({  
            type: "POST",  
            url: "include/ajax/get_classsection.php",  
            data: "id_class="+id_class,  
            success: function(msg){  
                $("#getclasssection").html(msg); 
                $("#loading").html(''); 
            }
        });  
    }
</script>