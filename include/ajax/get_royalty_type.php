<?php
//--------------------------------------------
	include "../dbsetting/lms_vars_config.php";
	include "../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../functions/login_func.php";
	include "../functions/functions.php";
//--------------------------------------------
if(isset($_POST['royalty_type'])) {

	//------------- Seprate The Values ----------------
	$values = explode("|",$_POST['royalty_type']);
	$type = $values[0];
	$campus = $values[1];
	//------------------------------------------------
    if($type == 2){
        //--------------- Royalty Particulars ----------------
        $sqllmsParticulars = $dblms->querylms("SELECT part_id, part_name
                                                    FROM ".ROYALTY_PARTICULARS."
                                                    WHERE part_status = '1' AND is_deleted != '1'
                                                    ORDER BY part_id DESC ");
        //-----------------------------------------------------
        if(mysqli_num_rows($sqllmsParticulars) > 0){
            echo'
            <div class="table-responsive">
                <table class="table table-bordered table-condensed table-striped mb-none">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Title</th>
                            <th>Students</th>
                            <th>Amount</th>
                            <th class="center">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>';
                    $srno = 0;
                    $grandTotal = 0;
                    $totalAmount = 0;
                    while($valPart = mysqli_fetch_array($sqllmsParticulars)){
                        // //------------------ Campus Royalty -------------------
                        $sqllmsRoyalty	= $dblms->querylms("SELECT r.id, r.grand_total, d.detail_id, d.id_particular, d.id_class, d.no_of_std, d.amount_per_std, d.tuitionfee_percentage, d.total_amount,
                                                                c.class_id, c.class_name
                                                            FROM ".ROYALTY_SETTING." r
                                                            INNER JOIN ".ROYALTY_SETTING_DET." d ON d.id_setup = r.id
                                                            LEFT JOIN ".CLASSES." c ON c.class_id = d.id_class
                                                            WHERE r.id_campus = '".$campus."'
                                                            AND r.royalty_type = '".$type."'
                                                            AND d.id_particular = '".$valPart['part_id']."' 
                                                            AND r.is_deleted != '1'
                                                            ORDER BY d.detail_id ASC");
                        
                        $valRoyalty = mysqli_fetch_array($sqllmsRoyalty);
                        //-----------------------------------------------------
                        $srno++;
                        //--------------------------------------------------
                        if($valPart['part_id'] == 3) {

                            $stdSrno = 0;
                            echo ' 
                            <tr>
                                <td class="center" width="50">'.$srno.'</td>
                                <th>'.$valPart['part_name'].'</th>
                                <td  colspan="3">
                                    <table class="table table-bordered table-condensed table-striped mb-none">
                                        <thead>
                                            <tr>
                                                <th class="center">#</th>
                                                <th>Class</th>
                                                <th>Tuition Fee</th>
                                                <th>Students</th>
                                                <th>Percentage</th>
                                                <th>Amount</th>
                                                <th class="center">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                                            //------------------ Campus Royalty -------------------
                                            $sqllmsClasses = $dblms->querylms("SELECT class_id, class_name
                                                                                    FROM ".CLASSES." c
                                                                                    WHERE c.class_status = '1' AND c.is_deleted != '1'");
                                            while($valClass = mysqli_fetch_array($sqllmsClasses)){

                                                //Royalty Detail
                                                $sqllmsRoyaDet	= $dblms->querylms("SELECT detail_id, amount_per_std, tuitionfee_percentage, total_amount
                                                                                        FROM ".ROYALTY_SETTING_DET."
                                                                                        WHERE id_setup = '".$valRoyalty['id']."'
                                                                                        AND id_class='".$valClass['class_id']."' LIMIT 1");
                                                if(mysqli_num_rows($sqllmsRoyaDet)>0) { 
                                                    $valAmountStd = mysqli_fetch_array($sqllmsRoyaDet);
                                                    $detail_id = $valAmountStd['detail_id'];
                                                    $amount = $valAmountStd['amount_per_std'];
                                                    $total_amount = $valAmountStd['total_amount'];
                                                    $tuitionfee_per = $valAmountStd['tuitionfee_percentage'];
                                                }
                                                else{
                                                    $detail_id = '';
                                                    $amount = '';
                                                    $total_amount = '';
                                                    $tuitionfee_per = '';
                                                }

                                                // Students Of Specific Class
                                                $sqllmsStd = $dblms->querylms("SELECT COUNT(std_id) as students
                                                                                    FROM ".STUDENTS." 
                                                                                    WHERE id_campus = '".$campus."'
                                                                                    AND id_class = '".$valClass['class_id']."'
                                                                                    AND std_status = '1' AND is_deleted != '1'");
                                                $valStd = mysqli_fetch_array($sqllmsStd);
                                                
                                                // Tuition Fee Of Specific Class
                                                $sqllmsFee = $dblms->querylms("SELECT d.amount
                                                                                    FROM ".FEESETUP." f
                                                                                    INNER JOIN ".FEESETUPDETAIL." d ON d.id_setup = f.id
                                                                                    WHERE d.id_cat = '1' AND f.status = '1' AND f.id_class = '".$valClass['class_id']."'
                                                                                    AND f.id_campus = '".$campus."' AND f.is_deleted != '1' 
                                                                                    ORDER BY f.id DESC");
                                                $valFee = mysqli_fetch_array($sqllmsFee);

                                                $stdSrno ++;
                                                echo'
                                                <tr>
                                                    <td class="center" width="50">'.$stdSrno.'</td>
                                                    <th>'.$valClass['class_name'].'</th>
                                                    <td width="100" class="center">
                                                        <input type="number" class="form-control" required name="tuition_fee[]" id="tuition_fee'.$stdSrno.'" value="'.$valFee['amount'].'" readonly/>
                                                    </td>
                                                    <td width="100" class="center">
                                                        <input type="number" class="form-control stds" required name="stds[]" id="stds'.$stdSrno.'" value="'.$valStd['students'].'"/>
                                                    </td>
                                                    <td width="100" class="center"> 
                                                        <input type="number" class="form-control percentage" required name="tuitionfee_percentage[]" id="tuitionfee_percentage'.$stdSrno.'" min="0" max="100" placeholder="Percentage" value="'.$tuitionfee_per.'" oninput="get_tuitionfee_percentage'.$stdSrno.'(this.value)"/>
                                                    </td>
                                                    <td width="100" class="center"> 
                                                        <div id="get_value'.$stdSrno.'">
                                                            <input type="number" class="form-control amount" required name="amount[]" id="amount'.$stdSrno.'"  placeholder="Amount" value="'.$amount.'" readonly/>
                                                        </div>
                                                    </td>
                                                    <td width="100" class="center">
                                                        <input type="hidden" name="detail_id[]" id="detail_id"  value="'.$detail_id.'">
                                                        <input type="hidden" name="id_particular[]" id="id_particular"  value="'.$valPart['part_id'].'">
                                                        <input type="hidden" name="id_class[]" id="id_class'.$stdSrno.'"  value="'.$valClass['class_id'].'">
                                                        <input type="number" class="form-control totalAmount" required name="totalAmount[]" id="totalAmount'.$stdSrno.'" value="'.$total_amount.'" readonly/>
                                                    </td>
                                                </tr>

                                                <script type="text/javascript">

                                                    //Return Tuition Fee
                                                    function get_tuitionfee_percentage'.$stdSrno.'(tuitionfee_percentage) {  
                                                        var id_class'.$stdSrno.' = document.getElementById("id_class'.$stdSrno.'").value;
                                                        $.ajax({  
                                                            type: "POST",  
                                                            url: "include/ajax/get_tuitionfee.php",
                                                            data: { percentage: tuitionfee_percentage, camp: '.$campus.', cls: id_class'.$stdSrno.', srno: '.$stdSrno.' },
                                                            success: function(msg){  
                                                                $("#get_value'.$stdSrno.'").html(msg); 
                                                                $("#loading").html(""); 
                                                                
                                                                //Calculate Total Amount
                                                                var stds = document.getElementById("stds'.$stdSrno.'").value;
                                                                var amount = document.getElementById("amount'.$stdSrno.'").value;
                                                                totalAmount = stds *  amount;
                                                                $("#totalAmount'.$stdSrno.'").val(totalAmount);
                                                
                                                                //Grand Total
                                                                var grandTotal = 0;
                                                                $(".totalAmount").each(function(){
                                                                    grandTotal += +$(this).val();
                                                                });
                                                                $("#grandTotal").val(grandTotal);
                                                            }
                                                        });  
                                                    }
                                                    
                                                    //Calculate Total Amount
                                                    $(document).on("load", "#amount'.$stdSrno.'", function() {
                                                        var stds = document.getElementById("stds'.$stdSrno.'").value;
                                                        var amount = document.getElementById("amount'.$stdSrno.'").value;
                                                        totalAmount = stds *  amount;
                                                        $("#totalAmount'.$stdSrno.'").val(totalAmount);
                                        
                                                        //Grand Total
                                                        var grandTotal = 0;
                                                        $(".totalAmount").each(function(){
                                                            grandTotal += +$(this).val();
                                                        });
                                                        $("#grandTotal").val(grandTotal);
                                                    }); 
                                                        
                                                    
                                                    //Calculate Total Amount
                                                    $(document).on("input", "#stds'.$stdSrno.'", function() {
                                                        var stds = document.getElementById("stds'.$stdSrno.'").value;
                                                        var amount = document.getElementById("amount'.$stdSrno.'").value;
                                                        totalAmount = stds *  amount;
                                                        $("#totalAmount'.$stdSrno.'").val(totalAmount);
                                        
                                                        //Grand Total
                                                        var grandTotal = 0;
                                                        $(".totalAmount").each(function(){
                                                            grandTotal += +$(this).val();
                                                        });
                                                        $("#grandTotal").val(grandTotal);
                                                    });
                                                </script>';
                                            }
                                            echo'
                                        <tbody>
                                    </table>
                                <td>
                            </tr>';
                            
                        }
                        else if($valPart['part_id'] == 2) {
                            //Total Students
                            $sqllmsTotalStd = $dblms->querylms("SELECT COUNT(std_id) as toatal_students
                                                                FROM ".STUDENTS." 
                                                                WHERE id_campus = '".$campus."'
                                                                AND std_status = '1' AND is_deleted != '1'");
                            $valTotalstd = mysqli_fetch_array($sqllmsTotalStd);

                            $students =  $valTotalstd['toatal_students'];

                            echo'
                            <tr>
                                <td class="center" width="50">'.$srno.'</td>
                                <th>'.$valPart['part_name'].'</th>
                                <td class="center">
                                    <input type="number" class="form-control std" required name="stds[]" id="stds" value="'.$students.'"/>
                                </td>
                                <td class="center">
                                    <input type="number" class="form-control amount" required name="amount[]" id="amount" value="'.$valRoyalty['amount_per_std'].'"/>
                                </td>
                                <td class="center">';
                                    if(mysqli_num_rows($sqllmsRoyalty) > 0){
                                        echo'<input type="hidden" name="detail_id[]" id="detail_id"  value="'.$valRoyalty['detail_id'].'">'; 
                                    }
                                    echo'
                                    <input type="hidden" name="id_particular[]" id="id_particular"  value="'.$valPart['part_id'].'">
                                    <input type="number" class="form-control totalAmount" required name="totalAmount[]" id="totalAmount" value="'.$valRoyalty['total_amount'].'" readonly/>
                                </td>
                            </tr>

                            <script type="text/javascript">
                                //Calculate Total Amount
                                $(document).on("input", ".amount", function() {
                                    var stds = document.getElementById("stds").value;
                                    var amount = document.getElementById("amount").value;
                                    totalAmount = stds *  amount;
                                    $("#totalAmount").val(totalAmount);
                                    
                                    //Grand Total
                                    var grandTotal = 0;
                                    $(".totalAmount").each(function(){
                                        grandTotal += +$(this).val();
                                    });
                                    $("#grandTotal").val(grandTotal);
                                });
                                
                                //Calculate Total Amount
                                $(document).on("input", ".stds", function() {
                                    var stds = document.getElementById("stds").value;
                                    var amount = document.getElementById("amount").value;
                                    totalAmount = stds *  amount;
                                    $("#totalAmount").val(totalAmount);
                                    
                                    //Grand Total
                                    var grandTotal = 0;
                                    $(".totalAmount").each(function(){
                                        grandTotal += +$(this).val();
                                    });
                                    $("#grandTotal").val(grandTotal);
                                });
                            </script>';
                        }	
                        else{
                            
                            echo'
                            <tr>
                                <td class="center" width="50">'.$srno.'</td>
                                <th colspan="3">'.$valPart['part_name'].'</th>
                                <td class="center">';
                                    if(mysqli_num_rows($sqllmsRoyalty) > 0){
                                        echo'<input type="hidden" name="detail_id[]" id="detail_id"  value="'.$valRoyalty['detail_id'].'">'; }
                                    echo'
                                    <input type="hidden" name="id_particular[]" id="id_particular"  value="'.$valPart['part_id'].'">
                                    <input type="number" class="form-control totalAmount" required name="totalAmount[]" id="totalAmount[]" value="'.$valRoyalty['total_amount'].'"/>
                                </td>
                            </tr>';
                        }
                        }
                        echo'
                    </tbody>
                </table>
                <h5><b>Grand Total</b></h5>	
                <div class="center">
                    <input class="form-control" type="number" class="form-control" name="grandTotal" id="grandTotal" value="'.$valRoyalty['grand_total'].'" readonly/>
                </div>
            </div>';
        }
        else{
            echo'<h4 class="text text-danger center">No Royalty Particular Added!</h4>';
        }
    } else if($type == 1) {
        //--------------- Royalty Particulars ----------------
        $sqllmsParticulars = $dblms->querylms("SELECT part_id, part_name
                                                    FROM ".ROYALTY_PARTICULARS."
                                                    WHERE part_status = '1' AND is_deleted != '1'
                                                    ORDER BY part_id DESC ");
        //-----------------------------------------------------
        if(mysqli_num_rows($sqllmsParticulars) > 0){
            echo'
            <div class="table-responsive">
                <table class="table table-bordered table-condensed table-striped mb-none">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Title</th>
                            <th>Students</th>
                            <th>Amount</th>
                            <th class="center">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>';
                        $srno = 0;
                        $totalAmount = 0;
                        while($valPart = mysqli_fetch_array($sqllmsParticulars)){
                            // //------------------ Campus Royalty -------------------
                            $sqllmsRoyalty	= $dblms->querylms("SELECT r.id, r.grand_total, d.detail_id, d.id_particular, d.id_class, d.no_of_std, d.amount_per_std, d.tuitionfee_percentage, d.total_amount,
                                                                    c.class_id, c.class_name
                                                                FROM ".ROYALTY_SETTING." r
                                                                INNER JOIN ".ROYALTY_SETTING_DET." d ON d.id_setup = r.id
                                                                LEFT JOIN ".CLASSES." c ON c.class_id = d.id_class
                                                                WHERE r.id_campus = '".$campus."'
                                                                AND r.royalty_type = '".$type."'
                                                                AND d.id_particular = '".$valPart['part_id']."' 
                                                                AND r.is_deleted != '1'
                                                                ORDER BY d.detail_id ASC");
                            
                            $valRoyalty = mysqli_fetch_array($sqllmsRoyalty);
                            //-----------------------------------------------------
                            $srno++;
                            //--------------------------------------------------
                            if($valPart['part_id'] == 3) {

                                $stdSrno = 0;
                                echo ' 
                                <tr>
                                    <td class="center" width="50">'.$srno.'</td>
                                    <th>'.$valPart['part_name'].'</th>
                                    <td  colspan="3">
                                        <table class="table table-bordered table-condensed table-striped mb-none">
                                            <thead>
                                                <tr>
                                                    <th class="center">#</th>
                                                    <th>Class</th>
                                                    <th>Tuition Fee</th>
                                                    <th>Students</th>
                                                    <th>Amount</th>
                                                    <th class="center">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
                                                //------------------ Campus Royalty -------------------
                                                $sqllmsClasses = $dblms->querylms("SELECT class_id, class_name
                                                                                        FROM ".CLASSES." c
                                                                                        WHERE c.class_status = '1' AND c.is_deleted != '1'");
                                                while($valClass = mysqli_fetch_array($sqllmsClasses)){

                                                    //Royalty Detail
                                                    $sqllmsRoyaDet	= $dblms->querylms("SELECT detail_id, amount_per_std, total_amount
                                                                                            FROM ".ROYALTY_SETTING_DET."
                                                                                            WHERE id_setup = '".$valRoyalty['id']."'
                                                                                            AND id_class='".$valClass['class_id']."' LIMIT 1");
                                                    if(mysqli_num_rows($sqllmsRoyaDet)>0) { 
                                                        $valAmountStd = mysqli_fetch_array($sqllmsRoyaDet);
                                                        $detail_id = $valAmountStd['detail_id'];
                                                        $amount = $valAmountStd['amount_per_std'];
                                                        $total_amount = $valAmountStd['total_amount'];
                                                    }
                                                    else{
                                                        $detail_id = '';
                                                        $amount = '';
                                                        $total_amount = '';
                                                    }

                                                    //Students Of Specific Class
                                                    $sqllmsStd = $dblms->querylms("SELECT COUNT(std_id) as students
                                                                                        FROM ".STUDENTS." 
                                                                                        WHERE id_campus = '".$campus."'
                                                                                        AND id_class = '".$valClass['class_id']."'
                                                                                        AND std_status = '1' AND is_deleted != '1'");
                                                    $valStd = mysqli_fetch_array($sqllmsStd);

                                                    // Tuition Fee Of Specific Class
                                                    $sqllmsFee = $dblms->querylms("SELECT d.amount
                                                                                        FROM ".FEESETUP." f
                                                                                        INNER JOIN ".FEESETUPDETAIL." d ON d.id_setup = f.id
                                                                                        WHERE d.id_cat = '1' AND f.status = '1' AND f.id_class = '".$valClass['class_id']."'
                                                                                        AND f.id_campus = '".$campus."' AND f.is_deleted != '1' 
                                                                                        ORDER BY f.id DESC");
                                                    $valFee = mysqli_fetch_array($sqllmsFee);

                                                    $stdSrno ++;
                                                    echo'
                                                    <tr>
                                                        <td class="center" width="50">'.$stdSrno.'</td>
                                                        <th>'.$valClass['class_name'].'</th>
                                                        <td width="100" class="center">
                                                            <input type="number" class="form-control" required name="tuition_fee[]" id="tuition_fee'.$stdSrno.'" value="'.$valFee['amount'].'" readonly/>
                                                        </td>
                                                        <td width="100" class="center">
                                                            <input type="number" class="form-control stds" required name="stds[]" id="stds'.$stdSrno.'" value="'.$valStd['students'].'"/>
                                                        </td>
                                                        <td width="100" class="center"> 
                                                            <input type="number" class="form-control amount" required name="amount[]" id="amount'.$stdSrno.'"  placeholder="Amount" value="'.$amount.'"/>
                                                        </div>
                                                        <td width="100" class="center">
                                                            <input type="hidden" name="detail_id[]" id="detail_id"  value="'.$detail_id.'">
                                                            <input type="hidden" name="id_particular[]" id="id_particular"  value="'.$valPart['part_id'].'">
                                                            <input type="hidden" name="id_class[]" id="id_class"  value="'.$valClass['class_id'].'">
                                                            <input type="number" class="form-control totalAmount" required name="totalAmount[]" id="totalAmount'.$stdSrno.'" value="'.$total_amount.'" readonly/>
                                                        </td>
                                                    </tr>
                                                    
                                                    <script type="text/javascript">
                                                        //Calculate Total Amount
                                                        $(document).on("input", "#amount'.$stdSrno.'", function() {
                                                            var stds = document.getElementById("stds'.$stdSrno.'").value;
                                                            var amount = document.getElementById("amount'.$stdSrno.'").value;
                                                            totalAmount = stds *  amount;
                                                            $("#totalAmount'.$stdSrno.'").val(totalAmount);
                                            
                                                            //Grand Total
                                                            var grandTotal = 0;
                                                            $(".totalAmount").each(function(){
                                                                grandTotal += +$(this).val();
                                                            });
                                                            $("#grandTotal").val(grandTotal);
                                                        });
                                                        
                                                        //Calculate Total Amount
                                                        $(document).on("input", "#stds'.$stdSrno.'", function() {
                                                            var stds = document.getElementById("stds'.$stdSrno.'").value;
                                                            var amount = document.getElementById("amount'.$stdSrno.'").value;
                                                            totalAmount = stds *  amount;
                                                            $("#totalAmount'.$stdSrno.'").val(totalAmount);
                                            
                                                            //Grand Total
                                                            var grandTotal = 0;
                                                            $(".totalAmount").each(function(){
                                                                grandTotal += +$(this).val();
                                                            });
                                                            $("#grandTotal").val(grandTotal);
                                                        });
                                                    </script>';
                                                }
                                                echo'
                                            <tbody>
                                        </table>
                                    <td>
                                </tr>';
                                
                            }
                            else if($valPart['part_id'] == 2) {
                                //Total Students
                                $sqllmsTotalStd = $dblms->querylms("SELECT COUNT(std_id) as toatal_students
                                                                    FROM ".STUDENTS." 
                                                                    WHERE id_campus = '".$campus."'
                                                                    AND std_status = '1' AND is_deleted != '1'");
                                $valTotalstd = mysqli_fetch_array($sqllmsTotalStd);

                                $students =  $valTotalstd['toatal_students'];
                                echo'
                                <tr>
                                    <td class="center" width="50">'.$srno.'</td>
                                    <th>'.$valPart['part_name'].'</th>
                                    <td class="center">
                                        <input type="number" class="form-control std" required name="stds[]" id="stds" value="'.$students.'"/>
                                    </td>
                                    <td class="center">
                                        <input type="number" class="form-control amount" required name="amount[]" id="amount" value="'.$valRoyalty['amount_per_std'].'"/>
                                    </td>
                                    <td class="center">';
                                        if(mysqli_num_rows($sqllmsRoyalty) > 0){
                                            echo'<input type="hidden" name="detail_id[]" id="detail_id"  value="'.$valRoyalty['detail_id'].'">'; 
                                        }
                                        echo'
                                        <input type="hidden" name="id_particular[]" id="id_particular"  value="'.$valPart['part_id'].'">
                                        <input type="number" class="form-control totalAmount" required name="totalAmount[]" id="totalAmount" value="'.$valRoyalty['total_amount'].'" readonly/>
                                    </td>
                                </tr>

                                <script type="text/javascript">
                                    //Calculate Total Amount
                                    $(document).on("input", ".amount", function() {
                                        var stds = document.getElementById("stds").value;
                                        var amount = document.getElementById("amount").value;
                                        totalAmount = stds *  amount;
                                        $("#totalAmount").val(totalAmount);
                                        
                                        //Grand Total
                                        var grandTotal = 0;
                                        $(".totalAmount").each(function(){
                                            grandTotal += +$(this).val();
                                        });
                                        $("#grandTotal").val(grandTotal);
                                    });
                                    
                                    //Calculate Total Amount
                                    $(document).on("input", ".stds", function() {
                                        var stds = document.getElementById("stds").value;
                                        var amount = document.getElementById("amount").value;
                                        totalAmount = stds *  amount;
                                        $("#totalAmount").val(totalAmount);
                                        
                                        //Grand Total
                                        var grandTotal = 0;
                                        $(".totalAmount").each(function(){
                                            grandTotal += +$(this).val();
                                        });
                                        $("#grandTotal").val(grandTotal);
                                    });
                                </script>';
                            }	
                            else{
                                echo'
                                <tr>
                                    <td class="center" width="50">'.$srno.'</td>
                                    <th colspan="3">'.$valPart['part_name'].'</th>
                                    <td class="center">';
                                        if(mysqli_num_rows($sqllmsRoyalty) > 0){
                                            echo'<input type="hidden" name="detail_id[]" id="detail_id"  value="'.$valRoyalty['detail_id'].'">'; }
                                        echo'
                                        <input type="hidden" name="id_particular[]" id="id_particular"  value="'.$valPart['part_id'].'">
                                        <input type="number" class="form-control totalAmount" required name="totalAmount[]" id="totalAmount[]" value="'.$valRoyalty['total_amount'].'"/>
                                    </td>
                                </tr>';
                            }
                        }
                        echo'
                    </tbody>
                </table>
                <h5><b>Grand Total</b></h5>	
                <div class="center">
                    <input class="form-control" type="number" class="form-control" name="grandTotal" id="grandTotal" value="'.$valRoyalty['grand_total'].'" readonly/>
                </div>
            </div>';
        }
        else{
            echo'<h4 class="text text-danger center">No Royalty Particular Added!</h4>';
        }
        // echo'<input type="number" class="form-control amount" required name="amount[]" placeholder="Amount" id="amount'.$stdSrno.'" value=""/>';
    } elseif($type == 3){
        //--------------- Royalty Particulars ----------------
        $sqllmsParticulars = $dblms->querylms("SELECT part_id, part_name
                                                    FROM ".ROYALTY_PARTICULARS."
                                                    WHERE part_status = '1' AND is_deleted != '1'
                                                    ORDER BY part_id DESC ");
        //-----------------------------------------------------
        if(mysqli_num_rows($sqllmsParticulars) > 0){
            echo'
            <div class="table-responsive">
                <table class="table table-bordered table-condensed table-striped mb-none">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Title</th>
                            <th class="center">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>';
                    $srno = 0;
                    $totalAmount = 0;
                    while($valPart = mysqli_fetch_array($sqllmsParticulars)){
                        // //------------------ Campus Royalty -------------------
                        $sqllmsRoyalty	= $dblms->querylms("SELECT r.id, r.grand_total, d.detail_id, d.id_particular, d.id_class, d.no_of_std, d.amount_per_std, d.tuitionfee_percentage, d.total_amount,
                                                                c.class_id, c.class_name
                                                            FROM ".ROYALTY_SETTING." r
                                                            INNER JOIN ".ROYALTY_SETTING_DET." d ON d.id_setup = r.id
                                                            LEFT JOIN ".CLASSES." c ON c.class_id = d.id_class
                                                            WHERE r.id_campus = '".$campus."'
                                                            AND r.royalty_type = '".$type."'
                                                            AND d.id_particular = '".$valPart['part_id']."' 
                                                            AND r.is_deleted != '1'
                                                            ORDER BY d.detail_id ASC");
                        
                        $valRoyalty = mysqli_fetch_array($sqllmsRoyalty);
                        //-----------------------------------------------------
                        $srno++;
                            echo'
                            <tr>
                                <td class="center" width="50">'.$srno.'</td>
                                <th>'.$valPart['part_name'].'</th>
                                <td width="250" class="center">';
                                    if(mysqli_num_rows($sqllmsRoyalty) > 0){
                                        echo'<input type="hidden" name="detail_id[]" id="detail_id"  value="'.$valRoyalty['detail_id'].'">'; }
                                    echo'
                                    <input type="hidden" name="id_particular[]" id="id_particular"  value="'.$valPart['part_id'].'">
                                    <input type="number" class="form-control totalAmount" required name="totalAmount[]" id="totalAmount[]" value="'.$valRoyalty['total_amount'].'"/>
                                </td>
                            </tr>';
                        }
                        echo'
                    </tbody>
                </table>
                <h5><b>Grand Total</b></h5>	
                <div class="center">
                    <input class="form-control" type="number" class="form-control" name="grandTotal" id="grandTotal" value="'.$valRoyalty['grand_total'].'" readonly/>
                </div>
            </div>';
        }
        else{
            echo'<h4 class="text text-danger center">No Royalty Particular Added!</h4>';
        }
    } else{
    }       
//---------------------------------------
}
?>