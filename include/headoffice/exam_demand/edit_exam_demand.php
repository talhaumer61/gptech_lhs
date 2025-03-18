<?php
if(isset($_GET['id_demand'])){
    
    // POST data
    if(isset($_GET['id_demand'])) { $id_demand = $_GET['id_demand']; } else { $id_demand = '';}
    if(isset($_GET['id_session'])) { $id_session = $_GET['id_session']; } else { $id_session = '';}
    if(isset($_GET['id_examtype'])) { $id_examtype = $_GET['id_examtype']; } else { $id_examtype = '';}
    if(isset($_GET['demand_status'])) { $demand_status = $_GET['demand_status']; } else { $demand_status = '';}
    if(isset($_GET['is_publish'])) { $is_publish = $_GET['is_publish']; } else { $is_publish = '';}
    if(isset($_GET['total_amount'])) { $total_amount = $_GET['total_amount']; } else { $total_amount = '';}
    if(isset($_GET['total_std'])) { $total_std = $_GET['total_std']; } else { $total_std = '';}

    $sqlAmountPerStd = $dblms->querylms("SELECT dd.amount_per_std
                                        FROM ".EXAM_DEMAND_DET." as dd
                                        WHERE dd.id_demand  = '".cleanvars($id_demand)."'
                                        GROUP BY dd.id_demand
                                    ");
    if(mysqli_num_rows($sqlAmountPerStd) > 0){
        $sqlAmountPerStd = mysqli_fetch_array($sqlAmountPerStd);
        $examFee    = $sqlAmountPerStd['amount_per_std'];
    }else{
        $sqlAmountPerStd = $dblms->querylms("SELECT ef.fee_per_std
                                        FROM ".EXAM_FEE." as ef
                                        WHERE ef.id_demand  = '".cleanvars($id_demand)."'
                                        AND ef.id_exam_type = '".cleanvars($id_examtype)."'
                                        AND ef.id_campus    = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                                        AND ef.status       = '1'
                                        AND ef.is_deleted   = '0'
                                    ");
        if(mysqli_num_rows($sqlAmountPerStd) > 0){
            $sqlAmountPerStd = mysqli_fetch_array($sqlAmountPerStd);
            $examFee    = $sqlAmountPerStd['amount_per_std'];
        }else{
            $examFee    = DEFAULT_EXAM_FEE;
        }
    }

    $sqlClass = $dblms->querylms("SELECT c.class_id, c.class_name, dd.*
                                    FROM ".CLASSES." as c
                                    LEFT JOIN ".EXAM_DEMAND_DET." dd ON (
                                        dd.id_class = c.class_id
                                        AND dd.id_demand = '".cleanvars($id_demand)."'  
                                    )
                                    WHERE c.is_deleted = '0'
                                    AND c.class_status = '1'
                                    ORDER BY c.class_id");
    if(mysqli_num_rows($sqlClass) > 0){
        echo'
        <section class="panel panel-featured panel-featured-primary appear-animation" data-appear-animation="fadeInRight" data-appear-animation-delay="100">
            <form action="exam_demand.php" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">    
                <header class="panel-heading">
                    <h2 class="panel-title"><i class="fa fa-graduation-cap"></i> 
                        Edit Exam Demand
                    </h2>
                </header>
                <div class="panel-body">
                    <input type="hidden" name="id_demand" value="'.$id_demand.'">	
                    <input type="hidden" name="id_session" value="'.$id_session.'">	
                    <input type="hidden" name="id_examtype" value="'.$id_examtype.'">
                    <input type="hidden" name="demand_status" value="'.$demand_status.'">
                    <input type="hidden" name="total_std" value="'.$total_std.'">
                    <div class="row mb-lg">
                        <div class="col-md-3">
                            <label class="control-label">Session <span class="required">*</span></label>
                            <select data-plugin-selectTwo data-width="100%" id="id_session" name="id_session" required title="Must Be Required" class="form-control populate" disabled>
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
                            <select data-plugin-selectTwo data-width="100%" id="id_examtype" name="id_examtype" required title="Must Be Required" class="form-control populate" disabled>
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
                        <div class="col-md-2">
                            <label class="control-label">Status <span class="required">*</span></label>
                            <select data-plugin-selectTwo data-width="100%" id="demand_status" name="demand_status" required title="Must Be Required" class="form-control populate" disabled>
                                <option value="">Select</option>';
                                    foreach($admstatus as $status){
                                        echo '<option value="'.$status['id'].'" '.($status['id']==$demand_status ? 'selected' : '').'>'.$status['name'].'</option>';
                                    }
                                echo'
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="control-label">Publish <span class="required">*</span></label>
                            <select data-plugin-selectTwo data-width="100%" id="is_publish" name="is_publish" required title="Must Be Required" class="form-control populate">
                                <option value="">Select</option>';
                                    foreach($statusyesno as $yesno){
                                        echo '<option value="'.$yesno['id'].'" '.($yesno['id']==$is_publish ? 'selected' : '').'>'.$yesno['name'].'</option>';
                                    }
                                echo'
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="control-label">Demand Fee <span class="required">*</span></label>
                            <input type="number" class="form-control" name="examFee" id="examFee" value="'.$examFee.'" required/>
                        </div>
                    </div>
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
                                    echo $valClass[''];
                                    $srno++;
                                    echo'
                                    <tr>
                                        <td class="center">'.$srno.'</td>
                                        <td>
                                            <input type="hidden" name="detail_id['.$srno.']" id="detail_id'.$srno.'" value="'.$valClass['detail_id'].'"/>
                                            <input type="hidden" name="id_class['.$srno.']" id="id_class'.$srno.'" value="'.$valClass['class_id'].'"/> '.$valClass['class_name'].'
                                            <input type="hidden" class="form-control amount'.$srno.'" name="amount['.$srno.']" id="amount'.$srno.'" value="'.(isset($valClass['amount_per_std']) ? $valClass['amount_per_std'] : $examFee).'" readonly/>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control stds'.$srno.'" required name="stds['.$srno.']" id="stds'.$srno.'" value="'.(!empty($valClass['no_of_std']) ? $valClass['no_of_std'] : '0').'" readonly/>
                                            <input type="hidden" class="form-control total totalAmount'.$srno.'" name="totalAmount['.$srno.']" id="totalAmount'.$srno.'" value="'.(!empty($valClass['total_amount']) ? $valClass['total_amount'] : '0').'" readonly/>
                                        </td>
                                    </tr>';
                                }
                                echo'
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- <label class="control-label">Grand Total <span class="required">*</span></label> -->
                            <input class="form-control" type="hidden" class="form-control" name="grandTotal" id="grandTotal" value="'.$total_amount.'" readonly/>
                        </div>
                    </div>
                </div>

                <div class="panel-footer">
                    <center>
                        <button type="submit" class="btn btn-primary" id="update_demand" name="update_demand">
                            <i class="fa fa-check"></i> Update Exam Demand</button>
                    </center>
                </div>
            </form>
        </section>';
    }else{
        echo'
        <section class="panel panel-featured panel-featured-primary appear-animation mt-sm" data-appear-animation="fadeInRight" data-appear-animation-delay="100">
            <h2 class="panel-body text-center font-bold text text-danger">No Record Found</h2>
        </section';
    }
}else{
	header("Location: dashboard.php");
}
?>