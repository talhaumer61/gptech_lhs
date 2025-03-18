<?php
if(isset($_GET['edit_id'])){
    $sqllmsattendance  = $dblms->querylms("SELECT * 
                                            FROM ".STUDENT_ATTENDANCE." 
                                            WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
                                            AND id          = '".cleanvars($_GET['edit_id'])."'
                                            LIMIT 1");
    $value_att = mysqli_fetch_array($sqllmsattendance);
    echo'
        <form action="" id="form" method="post" accept-charset="utf-8">
            <input type="hidden" id="id" name="id" value="'.$_GET['edit_id'].'">
            <input type="hidden" id="id_subject" name="id_subject" value="'.$_GET['id'].'">
            <input type="hidden" id="id_section" name="id_section" value="'.$_GET['section'].'">
            <input type="hidden" id="id_class" name="id_class" value="'.$_GET['class'].'">';
            $sqllms	= $dblms->querylms("SELECT s.std_id, s.std_name, s.std_photo, s.id_class, s.id_section, s.std_regno	
                                        FROM ".STUDENTS." s
                                        INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
                                        WHERE s.id_campus   = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                        AND s.id_class      = '".cleanvars($_GET['class'])."'
                                        AND s.id_section    = '".cleanvars($_GET['section'])."'
                                        AND s.std_status    = '1'
                                        AND s.id_session    = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."'
                                        ORDER BY s.std_id ASC");
            echo'
            <div class="row mb-md">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label">Date <span class="required" aria-required="true">*</span></label>
                        <input type="text" class="form-control" id="dated" name="dated" value="'.$value_att['dated'].'" required="" title="Must Be Required" autocomplete="off" readonly aria-required="true">
                    </div>
                </div>
                <div class="col-md-6"> 
                    <div class="form-group">
                        <label class="control-label">Type <span class="required">*</span></label>
                        <select data-plugin-selectTwo data-width="100%" id="id_examtype" name="id_examtype" required title="Must Be Required" class="form-control" disabled>
                            <option value="">Select</option>';
                                $sqlExamType = $dblms->querylms("SELECT type_id, type_name 
                                                                    FROM ".EXAM_TYPES."
                                                                    WHERE is_deleted    = '0'
                                                                    AND type_status     = '1'
                                                                ");
                                while($valExamType = mysqli_fetch_array($sqlExamType)) {
                                    echo '<option value="'.$valExamType['type_id'].'" '.($valExamType['type_id']==$value_att['id_examtype'] ? 'selected' : '').'>'.$valExamType['type_name'].'</option>';
                                }
                            echo'
                        </select>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed mb-none ">
                    <thead>
                        <tr>
                            <th width="40" class="center">Sr.</th>
                            <th width="40">Photo</th>
                            <th>Name</th>
                            <th>Regno</th>
                            <th width="40%">Status</th>
                        </tr>
                    </thead>
                    <tbody>';
                        $srno = 0;
                        while($rowsvalues = mysqli_fetch_array($sqllms)) {
                            $sqllmsattendance  = $dblms->querylms("SELECT id, id_setup, id_std, status    
                                                                    FROM ".STUDENT_ATTENDANCE_DETAIL." 
                                                                    WHERE id_setup = '".cleanvars($_GET['edit_id'])."' 
                                                                    AND id_std = '".cleanvars($rowsvalues['std_id'])."' LIMIT 1");
                            $valueattendance 	= mysqli_fetch_array($sqllmsattendance);

                            if($valueattendance['status'] == 1) { $present = 'checked="checked"';} else { $present = '';}
                            if($valueattendance['status'] == 2) { $absent = 'checked="checked"';} else { $absent = '';}
                            if($valueattendance['status'] == 3) { $late = 'checked="checked"';} else { $late = '';}
                            $srno++;
                            echo'
                            <tr>
                                <td class="center">'.$srno.'</td>
                                <td class="center"> <img src="uploads/images/students/'.$rowsvalues['std_photo'].'" width="35" height="35"</td>  
                                <td>'.$rowsvalues['std_name'].'</td>
                                <td>'.$rowsvalues['std_regno'].'</td>
                                <td>
                                    <div class="radio-custom radio-success radio-inline">
                                        <input type="radio" value="1" '.$present.' name="status['.$srno.']" id="pstatus_'.$srno.'">
                                        <label for="pstatus_'.$srno.'">Present</label>
                                    </div>
                                    <div class="radio-custom radio-danger radio-inline">
                                        <input type="radio" value="2" '.$absent.' name="status['.$srno.']" id="astatus_'.$srno.'">
                                        <label for="astatus_'.$srno.'">Absent</label>
                                    </div>
                                    <div class="radio-custom radio-info radio-inline">
                                        <input type="radio" value="3" '.$late.' name="status['.$srno.']" id="lstatus_'.$srno.'">
                                        <label for="lstatus_'.$srno.'">Late</label>
                                    </div>
                                </td>
                            </tr>
                            <input type="hidden" name="std_id['.$srno.']" id="std_id['.$srno.']" value="'.$rowsvalues['std_id'].'">';
                        }
                        echo'
                    </tbody>
                </table>
            </div>
            <div class="mt-md">
                <center>
                    <button type="submit" class="btn btn-primary" id="update_attendance" name="update_attendance">
                        <i class="fa fa-save"></i> Update Attendance</button>
                </center>
            </div>
        </form>
    </section>';
}