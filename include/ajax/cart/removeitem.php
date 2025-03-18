<?php
require_once("../../../include/dbsetting/lms_vars_config.php");
require_once("../../../api/db_functions.php");
$api 	= new main();
if(isset($_POST['item_id']) ){

    session_start(); 

    unset($_SESSION['cart'][$_POST['item_id']]);

    if(count($_SESSION['cart']) > 0){
        $srno 	= 0;
        $gtotal = 0;
        $tbody  = '';
        $balance = $api->get_customerbalance($_SESSION['userlogininfo']['CAMPUSCODE']);
        foreach($_SESSION['cart'] as $item){
            $srno++;
            $gtotal = $gtotal + ($item['pricesale'] * $item['quantity']);
            $itemstock = $api->get_itemstock($item['id']);
            $tbody .= '
            <tr>
                <td class="center">
                    <a onclick="removeitem(\''.$item['id'].'\')" class="btn btn-primary btn-xs"><i class="fa fa-trash"></i></a>
                </td>
                <td class="center">'.$srno.'</td>
                <td>
                    '.$item['code'].'-'.$item['name'].'
                    <input type="hidden" name="id_item['.$srno.']" id="id_item_'.$srno.'" class="form-control" value="'.$item['id'].'" >
                </td>
                <td class="center">
                    <span style="color:#00f; font-size:16px; font-weight:600; text-align:center;">'.$itemstock['stock'].'</span>
                    <input type="hidden" name="is_available['.$srno.']" id="is_available_'.$srno.'" value="'.($itemstock['stock'] > 0 ? '1' : '0').'">
                </td>
                <td class="center">
                    <input type="number" name="total_qty['.$srno.']" id="total_qty_'.$srno.'"  onchange="updatequantity(\''.$item['id'].'\',this.value)" style="color:#00f; font-weight:600; text-align:center;" class="form-control jQinv_item_qty" value="'.$item['quantity'].'" >
                </td>
                <td class="center">
                    <input type="number" name="unit_price['.$srno.']" id="unit_price_'.$srno.'" style="color:#00f; font-weight:600; text-align:center;" readonly="" class="form-control jQinv_item_rate" value="'.$item['pricesale'].'" >
                </td>
                <td class="center">
                    <input type="number" name="total_price['.$srno.']" id="total_price_'.$srno.'" style="color:orangered; font-weight:600; text-align:center;" readonly="" class="form-control jQinv_item_total" value="'.($item['pricesale'] * $item['quantity']).'.00" >
                </td>
            </tr>';
        }
        $tbody .= '
        <tr>
            <td colspan="7" style="text-align:right; padding: 11px 11px 0px 0px;">
                <p class="clearfix"><b> Total Bill Amount: <span class="inV_grandtotal">Rs.<span>'.$gtotal.'.00</span></span></b></p>
                <p class="clearfix"><b> Opening Balance: <span>Rs.<span>'.$balance['balance'].'.00</span></span></b></p>
                <p class="clearfix"><b> Total Receiveable: <span class="inV_receiveable">Rs.<span>'.($gtotal + $balance['balance']).'.00</span></span></b></p>
                <input type="hidden" id="inV_grandtotal" name="inV_grandtotal" value="'.$gtotal.'">
                <input type="hidden" id="opening_balance" name="opening_balance" value="'.$balance['balance'].'">
                <input type="hidden" id="inV_receiveable" name="inV_receiveable" value="'.($gtotal+ $balance['balance']).'">
            </td>
        </tr>';

        $data['status_code'] = '00';
        $data['tbody'] = $tbody;
    }else{
        $pbody = '
        <div class="text-center">
            <h2 class="text text-center text-danger mt-lg">No item found in the cart!</h2>
            <a href="items.php" class="btn btn-primary">Go to items!</a>
        </div>';
        $data['status_code'] = '11';
        $data['pbody'] = $pbody;
    }

    
    $data['title'] = 'Error';
    $data['text']  = 'Item Removed Successfully.';
    $data['type']  = 'error';
    echo json_encode($data);

}
?>