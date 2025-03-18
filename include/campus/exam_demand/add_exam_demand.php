<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '14', 'view' => '1'))){

    // POST data
    if(isset($_POST['id_session'])) { $id_session = $_POST['id_session']; } else { $id_session = '';}
    if(isset($_POST['id_examtype'])) { $id_examtype = $_POST['id_examtype']; } else { $id_examtype = '';}
    if(isset($_POST['demand_status'])) { $demand_status = $_POST['demand_status']; } else { $demand_status = '';}
    if(isset($_POST['is_publish'])) { $is_publish = $_POST['is_publish']; } else { $is_publish = '';}

    echo'
    <section class="panel panel-featured panel-featured-primary">
        <form action="#" id="form" enctype="multipart/form-data" autocomplete="off" method="post" accept-charset="utf-8">
            <header class="panel-heading">
                <h2 class="panel-title">
                    <i class="fa fa-list"></i> <span class="hidden-xs"> Select 		
                </h2>
            </header>
            <div class="panel-body">
                <div class="form-group mb-sm">
                    <div class="col-md-3">
                        <label class="control-label">Session <span class="required">*</span></label>
                        <select data-plugin-selectTwo data-width="100%" id="id_session" name="id_session" required title="Must Be Required" class="form-control populate">
                            <option value="">Select</option>';
                                $sqlSession	= $dblms->querylms("SELECT session_id, session_name 
                                                                    FROM ".SESSIONS."
                                                                    WHERE is_deleted    = '0'
                                                                    AND session_status  = '1'
                                                                ");
                                while($valSession = mysqli_fetch_array($sqlSession)) {
                                    echo '<option value="'.$valSession['session_id'].'" '.($valSession['session_id']==$id_session ? 'selected' : '').'>'.$valSession['session_name'].'</option>';
                                }
                            echo'
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Type <span class="required">*</span></label>
                        <select data-plugin-selectTwo data-width="100%" id="id_examtype" name="id_examtype" required title="Must Be Required" class="form-control populate">
                            <option value="">Select</option>';
                                $sqlExamType	= $dblms->querylms("SELECT type_id, type_name 
                                                                    FROM ".EXAM_TYPES."
                                                                    WHERE is_deleted    = '0'
                                                                    AND type_status     = '1'
                                                                ");
                                while($valExamType = mysqli_fetch_array($sqlExamType)) {
                                    echo '<option value="'.$valExamType['type_id'].'" '.($valExamType['type_id']==$id_examtype ? 'selected' : '').'>'.$valExamType['type_name'].'</option>';
                                }
                            echo'
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Status <span class="required">*</span></label>
                        <select data-plugin-selectTwo data-width="100%" id="demand_status" name="demand_status" required title="Must Be Required" class="form-control populate">
                            <option value="">Select</option>';
                                foreach($admstatus as $status){
                                    echo '<option value="'.$status['id'].'" '.($status['id']==$demand_status ? 'selected' : '').'>'.$status['name'].'</option>';
                                }
                            echo'
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Publish <span class="required">*</span></label>
                        <select data-plugin-selectTwo data-width="100%" id="is_publish" name="is_publish" required title="Must Be Required" class="form-control populate">
                            <option value="">Select</option>';
                                foreach($statusyesno as $yesno){
                                    echo '<option value="'.$yesno['id'].'" '.($yesno['id']==$is_publish ? 'selected' : '').'>'.$yesno['name'].'</option>';
                                }
                            echo'
                        </select>
                    </div>
                </div>
                <center>
                    <button type="submit" name="view_results" class="btn btn-primary mt-md"><i class="fa fa-search"></i> View Results</button>
                </center>
            </div>
        </form>
    </section>';

    if(isset($_POST['view_results'])){
        $sqlClass = $dblms->querylms("SELECT c.class_id, c.class_name
                                        FROM ".CLASSES." as c
                                        WHERE c.is_deleted = '0'
                                        AND c.class_status = '1'
                                        ORDER BY c.class_id");
        if(mysqli_num_rows($sqlClass) > 0){
            
            $sqlExamFee = $dblms->querylms("SELECT ef.fee_per_std
                                            FROM ".EXAM_FEE." as ef
                                            WHERE ef.id_campus       = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                                            AND ef.id_exam_type      = '".cleanvars($id_examtype)."'
                                            AND ef.is_deleted        = '0'
                                          ");
            if(mysqli_num_rows($sqlExamFee) > 0){
                $valExamFee = mysqli_fetch_array($sqlExamFee);
                $examFee    = $valExamFee['fee_per_std'];
            }else{
                $examFee    = DEFAULT_EXAM_FEE;
            }
            
            echo'
            <section class="panel panel-featured panel-featured-primary appear-animation" data-appear-animation="fadeInRight" data-appear-animation-delay="100">
                <form action="exam_demand.php" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">    
                    <header class="panel-heading">
                        <h2 class="panel-title"><i class="fa fa-graduation-cap"></i> 
                            Exam Demand
                        </h2>
                    </header>
                    <div class="panel-body">
                        <div class="table-responsive mt-sm mb-md">
                            <table class="table table-bordered table-striped table-condensed  mb-none" id="my_table">
                                <thead>
                                    <tr>
                                        <th class="center" width="40">Sr.</th>
                                        <th>Classes</th>
                                        <!-- <th width="200" class="center">Amount Per Student</th> -->
                                        <th width="200" class="center">No of Students</th>
                                        <!-- <th width="200" class="center">Total Amount</th> -->
                                    </tr>
                                </thead>
                                <tbody>';	
                                    $srno = 0;
                                    while($valClass = mysqli_fetch_array($sqlClass)){
                                        $srno++;
                                        echo'
                                        <tr>
                                            <td class="center">'.$srno.'</td>
                                            <td>
                                                <input type="hidden" name="id_class['.$srno.']" id="id_class" value="'.$valClass['class_id'].'"/> '.$valClass['class_name'].'
                                                <input type="hidden" class="form-control amount'.$srno.'" name="amount['.$srno.']" id="amount'.$srno.'" value="'.$examFee.'" readonly/>
                                            </td>
                                            <td>
                                                <input type="number" required class="form-control stds'.$srno.'" name="stds['.$srno.']" id="stds'.$srno.'"/>
                                                <input type="hidden" class="form-control total totalAmount'.$srno.'" name="totalAmount['.$srno.']" id="totalAmount'.$srno.'" readonly/>
                                            </td>
                                        </tr>
                                        <script type="text/javascript">
                                            //Calculate Total Amount
                                            $(document).on("input", ".stds'.$srno.'", function() {
                                                var stds = document.getElementById("stds'.$srno.'").value;
                                                var amount = document.getElementById("amount'.$srno.'").value;
                                                totalAmount = stds *  amount;
                                                $("#totalAmount'.$srno.'").val(totalAmount);
                                                
                                                //Grand Total
                                                var grandTotal = 0;
                                                $(".total").each(function(){
                                                    grandTotal += +$(this).val();
                                                });
                                                $("#grandTotal").val(grandTotal);
                                            });
                                        </script>';
                                    }
                                    echo'
                                    <input type="hidden" name="id_session" value="'.$id_session.'">
                                    <input type="hidden" name="id_examtype" value="'.$id_examtype.'">
                                    <input type="hidden" name="demand_status" value="'.$demand_status.'">
                                    <input type="hidden" name="is_publish" value="'.$is_publish.'">
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <!-- <label class="control-label">Grand Total <span class="required">*</span></label> -->
                                <input class="form-control" type="hidden" class="form-control" name="grandTotal" id="grandTotal" value="" readonly/>
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <center>
                            <button type="submit" class="btn btn-primary" id="add_demand" name="add_demand">
                                <i class="fa fa-check"></i> Make Exam Demand</button>
                        </center>
                    </div>
                </form>
            </section>';
        } else {
            echo'
            <section class="panel panel-featured panel-featured-primary appear-animation mt-sm" data-appear-animation="fadeInRight" data-appear-animation-delay="100">
                <h2 class="panel-body text-center font-bold text text-danger">No Record Found</h2>
            </section';
        }
    }

}else{
	header("Location: dashboard.php");
}
?>