<?php 
if(($view != 'add') && !isset($_GET['edit_id'])){
//----------------------------------------------------- 
$sqllmsdlp	= $dblms->querylms("SELECT s.syllabus_term, s.syllabus_file, s.id_month, s.id_week, s.note, c.class_name, su.subject_name
                                   	FROM ".SYLLABUS." s
                                    INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
                                    INNER JOIN ".CLASS_SUBJECTS." su ON su.subject_id = s.id_subject
									WHERE s.id_session = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."' 
									AND s.id_class = '".$_GET['class']."' AND s.id_subject = '".$_GET['id']."'
									AND s.syllabus_status = '1' AND s.is_deleted != '1' AND s.syllabus_type = '2'
								    ORDER BY s.syllabus_id DESC
                                    ");
//-----------------------------------------------------
    if (mysqli_num_rows($sqllmsdlp) > 0) {
    echo '
        <table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
            <thead>
                <tr>
                    <th width="70" class="text-center"">#</th>
                    <th>Term</th>
                    <th>Week</th>
                    <th>Note</th>
                    <th width="100px;" class="center">Download</th>
                </tr>
            </thead>
            <tbody>';

    $sratt = 0;
    while($value_dlp = mysqli_fetch_assoc($sqllmsdlp)) { 
	//-----------------------------------------------------
	if($value_dlp['syllabus_term'] == 1){
		$term = 'First';
	}
	elseif($value_dlp['syllabus_term'] == 2){
		$term = 'Second';
	}
    $sratt ++;
    //------------------------------------------------
    echo '
    <tr>
        <td  class="text-center">'.$sratt.'</td>
        <td>'.$term.'</td>
        <td>Week '.$value_dlp['id_week'].'</td>
        <td>'.$value_dlp['note'].'</td>
        <td class="center">';
            // if($value_dlp['note']){
            //     echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-info btn-xs" onclick="showAjaxModalZoom(\'include/modals/syllabus-dlp/modal_dlp_details.php?id='.$rowsvalues['syllabus_id'].'\');"><i class="glyphicon glyphicon-eye-open"></i></a>';
                
            // }
            echo'
            <a href="uploads/dlp/'.$value_dlp['syllabus_file'].'" download="DLP-'.get_monthtypes($value_dlp['id_month']).'-'.get_week($value_dlp['id_week']).'-'.$value_dlp['class_name'].'-'.$value_dlp['subject_name'].'" class="btn btn-success btn-xs");"><i class="glyphicon glyphicon-download"></i></a>
        </td>
    </tr>';
    }
    //-----------------------------------------------------
    echo '
            </tbody>
        </table>';
    }
    else{
        echo'<h4 class="center">No Record Found</h4>';
    }
}