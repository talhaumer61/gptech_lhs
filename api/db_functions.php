<?php
class main { 

    // API
	public function api($form_data) {
	
		$str = json_encode($form_data);

		$curl = curl_init();

		curl_setopt_array($curl, array(
											  CURLOPT_URL 				=> API_URL
											, CURLOPT_RETURNTRANSFER 	=> true
											, CURLOPT_REFERER 			=> $_SERVER['SERVER_NAME']
											, CURLOPT_ENCODING 			=> ''
											, CURLOPT_MAXREDIRS 		=> 10
											, CURLOPT_TIMEOUT 			=> 0
											, CURLOPT_FOLLOWLOCATION 	=> true
											, CURLOPT_HTTP_VERSION 		=> CURL_HTTP_VERSION_1_1
											, CURLOPT_CUSTOMREQUEST		=> 'POST'
											, CURLOPT_POSTFIELDS 		=> $str
											, CURLOPT_HTTPHEADER 		=> array(
																				  "appId: ".API_ID
																				, "appKey: ".API_KEY
																				, 'Content-Type: application/json'
																	  		)
									)
						 );
		$response 	= curl_exec($curl);
		$data 		= json_decode($response, true);

		curl_close($curl);
		return $data;

	}
    // GET ITEMS
    public function get_items($search_word = '', $id_cls = '', $id_cat = '') { 
		$formData = array(
            'method_name' => 'get_items'
        );
        if(!empty($search_word)){
            $formData['search_word'] = $search_word;
        }
        if(!empty($id_cls)){
            $formData['id_cls'] = $id_cls;
        }
        if(!empty($id_cat)){
            $formData['id_cat'] = $id_cat;
        }
        $items	 = $this->api($formData);
		return $items;
	}

    // GET ITEM CATEGORIES
    public function get_item_categories() { 
		$formData = array(
            				'method_name' => 'get_item_categories'
        				 );
        $categories	 = $this->api($formData);
		return $categories;
	}

    // GET CLASSES
    public function get_classes() { 
		$formData = array(
            				'method_name' => 'get_classes'
						 );
        $classes = $this->api($formData);
		return $classes;
	}
	
	// GET My Ledger
    public function get_myledger() { 
		$formData = array(
							  'method_name' 	=> 'get_myledger'
							, 'customer_code' 	=> $_SESSION['userlogininfo']['CAMPUSCODE']
						);
        $myledger = $this->api($formData);
		return $myledger;
	}
	// GET My Returns
    public function get_myreturns() { 
		$formData = array(
							  'method_name' 	=> 'get_myreturns'
							, 'customer_code' 	=> $_SESSION['userlogininfo']['CAMPUSCODE']
						);
        $myorders = $this->api($formData);
		return $myorders;
	}
	
	// GET Return Detail
    public function get_returndetail($id) { 
		$formData = array(
							  'method_name' 	=> 'get_returndetail'
							, 'orderid' 		=> $id
							, 'customer_code' 	=> $_SESSION['userlogininfo']['CAMPUSCODE']
						);
        $orderdetail = $this->api($formData);
		return $orderdetail;
	}
	// GET My Estimate
    public function get_myestimae($id) { 
		$formData = array(
							  'method_name' 	=> 'get_myestimae'
							, 'estimateid' 		=> $id
							, 'customer_code' 	=> $_SESSION['userlogininfo']['CAMPUSCODE']
						);
        $myEstimate = $this->api($formData);
		return $myEstimate;
	}

	// GET Estimates
    public function get_estimates() { 
		$formData = array(
							  'method_name' 	=> 'get_estimates'
							, 'customer_code' 	=> $_SESSION['userlogininfo']['CAMPUSCODE']
						);
        $estimates = $this->api($formData);
		return $estimates;
	}
	// GET Order Detail
    public function get_estimatedetail($id) { 
		$formData = array(
							  'method_name' 	=> 'get_estimatedetail'
							, 'estimateid' 		=> $id
							, 'customer_code' 	=> $_SESSION['userlogininfo']['CAMPUSCODE']
						);
        $estimatedetail = $this->api($formData);
		return $estimatedetail;
	}
	// GET My Orders
    public function get_myorders() { 
		$formData = array(
							  'method_name' 	=> 'get_myorders'
							, 'customer_code' 	=> $_SESSION['userlogininfo']['CAMPUSCODE']
						);
        $myorders = $this->api($formData);
		return $myorders;
	}
	// GET Order Detail
    public function get_orderdetail($id) { 
		$formData = array(
							  'method_name' 	=> 'get_orderdetail'
							, 'orderid' 		=> $id
							, 'customer_code' 	=> $_SESSION['userlogininfo']['CAMPUSCODE']
						);
        $orderdetail = $this->api($formData);
		return $orderdetail;
	}
	// GET My invoices
    public function get_myinvoices() { 
		$formData = array(
							  'method_name' 	=> 'get_myinvoices'
							, 'customer_code' 	=> $_SESSION['userlogininfo']['CAMPUSCODE']
						);
        $myinvoices = $this->api($formData);
		return $myinvoices;
	}
	// GET Invoice Detail
    public function get_invoicedetail($id) { 
		$formData = array(
							  'method_name' 	=> 'get_invoicedetail'
							, 'invoiceid' 		=> $id
							, 'customer_code' 	=> $_SESSION['userlogininfo']['CAMPUSCODE']
						);
        $invoicedetail = $this->api($formData);
		return $invoicedetail;
	}
	
	// GET Item Stock
    public function get_itemstock($id) { 
		$formData = array(
							  'method_name' => 'get_itemstock'
							, 'itemid' 		=> $id
						);
        $stock = $this->api($formData);
		return $stock;
	}
	
		// GET Customer Balance
    public function get_customerbalance($code) { 
		$formData = array(
							  'method_name' 	=> 'get_customerbalance'
							, 'customer_code' 	=> $code
						);
        $record = $this->api($formData);
		return $record;
	}
	
    // ADD Job Order
    public function add_joborder($post_data) { 
		$formData = array(
							 'method_name'  => 'add_joborder'
							,'form_data'    =>  $post_data
						);
        $result	 = $this->api($formData);
		return $result;
	}
    // Edit Job Order
    public function edit_joborder($post_data) { 
		$formData = array(
							 'method_name'  => 'edit_joborder'
							,'form_data'    =>  $post_data
						);
        $result	 = $this->api($formData);
		return $result;
	}
    // ADD ESTIMATE
    public function add_estimate($post_data) { 
		$formData = array(
							 'method_name'  => 'add_estimate'
							,'form_data'    =>  $post_data
						);
        $result	 = $this->api($formData);
		return $result;
	}
  // ADD Customer
    public function add_customer($name, $cellno, $email, $address, $contactperson, $personcell, $personwhatsapp, $codelhsmes) { 
		$formData = array(
							  'method_name'  			 => 'add_customer'
							, 'idbrand'    	 			 => 2
							, 'idtype'    	 			 => 1
							, 'idcompany'    		     => 8
							, 'id_city'    				 => 0
							, 'id_ade'    				 => 0
							, 'invoicelogo'  			 => 2
							, 'customer_name'  			 => $name
							, 'customer_cellno'  		 => $cellno
							, 'customer_email'  		 => $email
							, 'customer_address'  		 => $address
							, 'customer_contactperson'   => $contactperson
							, 'customer_personcell'  	 => $personcell
							, 'customer_personwhatsapp'  => $personwhatsapp
							, 'customer_codelhsmes'  	 => $codelhsmes
						);
        $result	 = $this->api($formData);
		return $result;
	}

	// GET My invoices
    public function msd_login() { 
		$formData = array(
							  'method_name' 	=> 'msd_login'
							, 'customer_code' 	=> $_SESSION['userlogininfo']['CAMPUSCODE']
						);
        $msdlogin = $this->api($formData);
		return $msdlogin;
	}
		
	
}