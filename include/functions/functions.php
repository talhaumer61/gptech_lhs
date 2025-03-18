<?php 
function get_scheme_type($id = "") {
	$scheme_type= array (
							  '1' => 'Monthly Assessment-1'
							, '2' => 'Monthly Assessment-2'
							, '3' => 'Monthly Assessment-3'
							, '4' => 'Term Assessment'
							, '5' => 'Term Paper'
						);
	return (!empty($id)? $scheme_type[$id]: $scheme_type);
}
//--------------- Status ------------------
$admstatus = array (
						array('id'=>1, 'name'=>'Active')		, array('id'=>2, 'name'=>'Inactive')
				   );

function get_admstatus($id) {
	$listadmstatus= array (
							'1' => '<span class="label label-primary">Active</span>', 
							'2' => '<span class="label label-warning">Inactive</span>');
	return $listadmstatus[$id];
}
//--------------- Notification Status ------------------
$status = array (
	array('id'=>1, 'name'=>'Yes'), array('id'=>2, 'name'=>'No')
);

function get_notification($id) {
	$listnote= array (
			'1' => '<span class="label label-success">Yes</span>', 
			'2' => '<span class="label label-warning">No</span>'
		);
	return $listnote[$id];
}
//--------------- Status ------------------
$status = array (
						array('id'=>1, 'name'=>'Active'), array('id'=>2, 'name'=>'Inactive')
				   );

function get_status($id) {
	$liststatus= array (
							'1' => '<span class="label label-primary">Active</span>', 
							'2' => '<span class="label label-warning">Inactive</span>');
	return $liststatus[$id];
}
//--------------- Inquiry Status ------------------
$inqStatus = array (
						array('id'=>1, 'name'=>'Active'), array('id'=>2, 'name'=>'Inactive'), array('id'=>3, 'name'=>'Admitted')
				   );

function get_inqStatus($id) {
	$listInqStatus= array (
							'1' => '<span class="label label-primary">Active</span>', 
							'2' => '<span class="label label-warning">Inactive</span>', 
							'3' => '<span class="label label-success">Admitted</span>');
	return $listInqStatus[$id];
}
//--------------- Leave Status ------------------
$status = array (
						array('id'=>1, 'name'=>'Approved'), array('id'=>2, 'name'=>'Pending'), array('id'=>3, 'name'=>'Rejected')
				   );

function get_leave($id) {
	$liststatus= array (
							'1' => '<span class="label label-success">Approved</span>', 
							'2' => '<span class="label label-warning">Pending</span>', 
							'3' => '<span class="label label-danger">Rejected</span>');
	return $liststatus[$id];
}
//--------------- Payments Status ------------------
$payments = array (
						array('id'=>1, 'name'=>'Paid')		, 
						array('id'=>2, 'name'=>'Pending')	, 
						array('id'=>3, 'name'=>'Unpaid')
				   );

function get_payments($id) {
	$listpayments = array (
							'1' => '<span class="label label-success" id="bns-status-badge">Paid</span>'		, 
							'2' => '<span class="label label-warning" id="bns-status-badge">Pending</span>'	,
							'3' => '<span class="label label-danger" id="bns-status-badge">Unpaid</span>'
						  );
	return $listpayments[$id];
}

function get_payments1($id) {
	$listpayments = array (
							'1' => 'Paid'		, 
							'2' => 'Pending'	,
							'3' => 'Unpaid'
						  );
	return $listpayments[$id];
}

//-------------- Royalty Types --------------------
$rolyaltyType = array (
	array('id'=>1, 'name'=>'Fixed')
  , array('id'=>2, 'name'=>'Percentage')
  , array('id'=>3, 'name'=>'Certain Amount')
);

function get_royaltyType($id) {
  $listRoyaltyType = array (
			'1' => 'Fixed'
		  , '2' => 'Percentage'
		  , '3' => 'Certain Amount'
	  );
  return $listRoyaltyType[$id];
}

//--------------- Fund Types -------------------
$fundType = array (
	array('id'=>1, 'name'=>'Royalty')
  , array('id'=>2, 'name'=>'Exam Demand')
);

function get_fundType($id) {
  $listfundType = array (
			'1' => 'Royalty'
		  , '2' => 'Exam Demand'
	  );
  return $listfundType[$id];
}


//--------------- Complaint Status ------------------
$status = array (
	array('id'=>1, 'name'=>'Resolved'), array('id'=>2, 'name'=>'Pending'), array('id'=>3, 'name'=>'Rejected')
);

function get_complaint($id) {
$listcomplaint= array (
		'1' => '<span class="label label-success">Resolved</span>', 
		'2' => '<span class="label label-warning">Pending</span>', 
		'3' => '<span class="label label-danger">Rejected</span>');
return $listcomplaint[$id];
}

function get_complaint1($id) {
$listcomplaint= array (
		'1' => 'Resolved', 
		'2' => 'Pending', 
		'3' => 'Rejected');
return $listcomplaint[$id];
}
//--------------- Delivery Status ------------------
$status = array (
						array('id'=>1, 'name'=>'Pending'), array('id'=>2, 'name'=>'Onhold'), array('id'=>3, 'name'=>'Accepted'), array('id'=>4, 'name'=>'Dispatched'), array('id'=>5, 'name'=>'Delivered'), array('id'=>6, 'name'=>'Rejected')
				   );

function get_delivery($id) {
	$listdelivery= array (
							'1' => '<span class="label label-dark">Pending</span>'	, 
							'2' => '<span class="label label-warning">Onhold</span>'	, 
							'3' => '<span class="label label-primary">Accepted</span>'	, 
							'4' => '<span class="label label-info">Dispatched</span>'	, 
							'5' => '<span class="label label-success">Delivered</span>'	, 
							'6' => '<span class="label label-danger">Rejected</span>');
	return $listdelivery[$id];
}
//--------------- Guardian ---------------
$guardian = array (
	array('id'=>1, 'name'=>'Father'),
	array('id'=>2, 'name'=>'Mother'),
	array('id'=>3, 'name'=>'Brother'),
	array('id'=>4, 'name'=>'Sister'),
	array('id'=>5, 'name'=>'Uncle'),
	array('id'=>6, 'name'=>'Other')
   );
   function get_guardian($id) {
	$listdelivery= array (
							'1' => 'Father'	, 
							'2' => 'Mother'	, 
							'3' => 'Brother', 
							'4' => 'Sister'	, 
							'5' => 'Uncle'	, 
							'6' => 'Other');
	return $listdelivery[$id];
}
//--------------- Admins Rights ----------
$admtypes = array (
					array('id'=>1, 'name'=>'Super Admin'),
					array('id'=>2, 'name'=>'Campus Head'),
					array('id'=>3, 'name'=>'Administrator'),
					array('id'=>4, 'name'=>'Accountant'),
					array('id'=>5, 'name'=>'Designer'),
					array('id'=>6, 'name'=>'Simple')
				   );

function get_admtypes($id) {
	$listadmrights = array (
							'1'	=> 'Super Admin',
							'2'	=> 'Campus Head',
							'3'	=> 'Administrator',
							'4'	=> 'Accountant',
							'5'	=> 'Designer',
							'6'	=> 'Simple'
							);
	return $listadmrights[$id];
}
//--------------- Status Yes No ----------
$statusyesno = array (
	array('id'=>1, 'name'=>'Yes'), array('id'=>2, 'name'=>'No')
);

function get_statusyesno($id) {

$liststatusyesno = array (
			'1' => '<span class="label label-success">Yes</span>'	, 
			'2' => '<span class="label label-danger">No</span>'
		 );
return $liststatusyesno[$id];
}

//--------------- Hostel Type ----------
$hostelype = array (
						array('id'=>1, 'name'=>'Boys'), array('id'=>2, 'name'=>'Girls')
				   );

function get_hostelype($id) {
	
	$listhostelype = array (
								'1'	=> 'Boys',	'2'	=> 'Girls'
							 );
	return $listhostelype[$id];
}
//-------Rupees in Word-------------------------------
function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'Zero',
        1                   => 'One',
        2                   => 'Two',
        3                   => 'Three',
        4                   => 'Four',
        5                   => 'Five',
        6                   => 'Six',
        7                   => 'Seven',
        8                   => 'Eight',
        9                   => 'Nine',
        10                  => 'Ten',
        11                  => 'Eleven',
        12                  => 'Twelve',
        13                  => 'Thirteen',
        14                  => 'Fourteen',
        15                  => 'Fifteen',
        16                  => 'Sixteen',
        17                  => 'Seventeen',
        18                  => 'Eighteen',
        19                  => 'Nineteen',
        20                  => 'Twenty',
        30                  => 'Thirty',
        40                  => 'Fourty',
        50                  => 'Fifty',
        60                  => 'Sixty',
        70                  => 'Seventy',
        80                  => 'Eighty',
        90                  => 'Ninety',
        100                 => 'Hundred',
        1000                => 'Thousand',
        1000000             => 'Million',
        1000000000          => 'Billion',
        1000000000000       => 'Trillion',
        1000000000000000    => 'Quadrillion',
        1000000000000000000 => 'Quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}

function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}
//--------------- Subject Type ----------
$subjecttype = array (
						array('id'=>1, 'name'=>'Optional'), array('id'=>2, 'name'=>'Mandatory')
				   );

function get_subjecttype($id) {
	
	$listsubjecttype= array (
							'1' => '<span class="label label-primary">Optional</span>', 
							'2' => '<span class="label label-warning">Mandatory</span>');
	return $listsubjecttype[$id];
}
//--------------- Subject Cat ----------
$subjectcat = array (
						  array('id'=>1, 'name'=>'English')
						, array('id'=>2, 'name'=>'English Grammar')
						, array('id'=>3, 'name'=>'Urdu')
						, array('id'=>4, 'name'=>'Urdu Grammar')
						, array('id'=>5, 'name'=>'Science')
						, array('id'=>6, 'name'=>'Math')
						, array('id'=>7, 'name'=>'Islamic Studies')
						, array('id'=>8, 'name'=>'General Knowledge')
						, array('id'=>9, 'name'=>'Social Studies')
						, array('id'=>10, 'name'=>'Nazra')
						, array('id'=>11, 'name'=>'Urdu Phonics')
						, array('id'=>12, 'name'=>'English Phonics')
						, array('id'=>13, 'name'=>'Art & Drawing')
						, array('id'=>14, 'name'=>'Computer Science')
						, array('id'=>15, 'name'=>'History')
						, array('id'=>16, 'name'=>'Geography')
					);

function get_subjectcat($id) {

$listsubjectcat= array (
							  '1' => 'English'		 
							, '2' => 'English Grammar' 
							, '3' => 'Urdu'			
							, '4' => 'Urdu Grammar'	
							, '5' => 'Science'		
							, '6' => 'Math'			
							, '7' => 'Islamic Studies'
							, '8' => 'General Knowledge'
							, '9' => 'Social Studies'	
							, '10' => 'Nazra'			
							, '11' => 'Urdu Phonics'	
							, '12' => 'English Phonics'
							, '13' => 'Art & Drawing'	
							, '14' => 'Computer Science'
							, '15' => 'History'
							, '16' => 'Geography'
						);
	return $listsubjectcat[$id];
}
//--------------- Instruction Medium ----------
$instrmedium = array (
	array('id'=>1, 'name'=>'English Medium'),
	array('id'=>2, 'name'=>'Urdu Medium')
);

function get_instrmedium($id) {

$listinstrmedium= array (
		'1' => '<span class="label label-primary">English Medium</span>', 
		'2' => '<span class="label label-warning">Urdu Medium</span>');
return $listinstrmedium[$id];
}
//--------------- Employee Type ------------------
$emply_type = array (
						array('id'=>1, 'name'=>'Teaching'), array('id'=>2, 'name'=>'Non Teaching')
				   );

function get_emplytype($id) {
	$listemply= array (
							'1' => 'Teaching', 
							'2' => 'Non Teacheing');
	return $listemply[$id];
}
//--------------- Inquiry Type ------------------
$inquirysrc = array (
						array('id'=>1, 'name'=>'Online')		,
						array('id'=>2, 'name'=>'Broacher')		,
						array('id'=>3, 'name'=>'Cable Add')		,
						array('id'=>4, 'name'=>'Facebook Add')	,
						array('id'=>5, 'name'=>'Walk-in')		,
						array('id'=>6, 'name'=>'Friend')		
				   );

function get_inquirysrc($id) {
	$lissrc= array (
					'1' => 'Online'			,
					'2' => 'Broacher'		,
					'3' => 'Cable Add'		,
					'4' => 'Facebook Add'	,
					'5' => 'Walk-in'		,
					'6' => 'Friend'		
					);
	return $lissrc[$id];
}
//--------------- Transport USer Type ------------------
$type = array (
						array('id'=>1, 'name'=>'Student'), array('id'=>2, 'name'=>'Employee')
				   );

function get_usertype($id) {
	$listuser= array (
							'1' => 'Student', 
							'2' => 'Employee');
	return $listuser[$id];
}

//--------------- Attendce Keywords ----------
$attendtype = array (
					array('id'=>1, 'name'=>'Present'),
					array('id'=>2, 'name'=>'Absent'),
					array('id'=>3, 'name'=>'Holiday'),
					array('id'=>4, 'name'=>'Late')
				   );

function get_attendtype($id) {
	$attendcetype = array (
							'1'	=> '<span class="label label-success">P</span>', 
							'2'	=> '<span class="label label-danger">A</span>', 
							'3'	=> '<span class="label label-primary">H</span>', 
							'4'	=> '<span class="label label-warning">L</span>'
							);
	return $attendcetype[$id];
}

function get_attendtype1($id) {
	$listpayments = array (
							'1' => 'Present'	, 
							'2' => 'Absent'		,
							'3' => 'Holiday'	,
							'4' => 'Late'
						  );
	return $listpayments[$id];
}

//------------- Digital Resources ----------
function get_digitalresource($id) {
	$listdigitalresource = array (
							'1' => 'youtube'	, 
							'2' => 'website'	,
							'3' => 'ebook'		
						  );
	return $listdigitalresource[$id];
}

//------------- Exam Terms ---------------
$termrtypes = array (
					array('id'=>1, 'name'=>'First Term'),
					array('id'=>2, 'name'=>'Second Term')
				   );

function get_term($id) {
	$listterm = array (
						'1' => 'First Term'		, 
						'2' => 'Second Term'		
						);
	return $listterm[$id];
}

//------------- Exam Assessments ---------------
function get_assessment($id) {
	$listassessment = array (
						'1' => 'Assessment Manual'	, 
						'2' => 'Assessment Policy'	, 
						'3' => 'Assessment Scheme'		
						);
	return $listassessment[$id];
}

//--------------- Months Keywords ----------
$monthtypes = array (
					array('id'=>1, 'name'=>'January'),
					array('id'=>2, 'name'=>'February'),
					array('id'=>3, 'name'=>'March'),
					array('id'=>4, 'name'=>'April'),
					array('id'=>5, 'name'=>'May'),
					array('id'=>6, 'name'=>'June'),
					array('id'=>7, 'name'=>'July'),
					array('id'=>8, 'name'=>'August'),
					array('id'=>9, 'name'=>'September'),
					array('id'=>10, 'name'=>'October'),
					array('id'=>11, 'name'=>'November'),
					array('id'=>12, 'name'=>'December')
				   );

$summermonth = array (
					array('id'=>3, 'name'=>'March'),
					array('id'=>4, 'name'=>'April'),
					array('id'=>5, 'name'=>'May')
					);

function get_monthtypes($id) {
	$month = array (
							'1'		=> 'January',
							'2'		=> 'February',
							'3'		=> 'March',
							'4'		=> 'April',
							'5'		=> 'May',
							'6'		=> 'June',
							'7'		=> 'July',
							'8'		=> 'August',
							'9'		=> 'September',
							'10'	=> 'October',
							'11'	=> 'November',
							'12'	=> 'December'
							);
	return $month[$id];
}
//--------------- Month Weeks ----------
$weeks = array (
	array('id'=>1, 'name'=>'Week 1'),
	array('id'=>2, 'name'=>'Week 2'),
	array('id'=>3, 'name'=>'Week 3'),
	array('id'=>4, 'name'=>'Week 4')
   );

function get_week($id) {
$week = array (
			'1'		=> 'Week 1',
			'2'		=> 'Week 2',
			'3'		=> 'Week 3',
			'4'		=> 'Week 4'
			);
return $week[$id];
}
//--------------- Days Keywords ----------
$daytypes = array (
					array('id'=>1, 'name'=>'Monday')	,
					array('id'=>2, 'name'=>'Tuesday')	,
					array('id'=>3, 'name'=>'Wednesday')	,
					array('id'=>4, 'name'=>'Thursday')	,
					array('id'=>5, 'name'=>'Friday')	,
					array('id'=>6, 'name'=>'Saturday')	,
					array('id'=>7, 'name'=>'Sunday')
				   );

function get_daytypes($id) {
	$day = array (
							'1'		=> 'Monday'		,
							'2'		=> 'Tuesday'	,
							'3'		=> 'Wednesday'	,
							'4'		=> 'Thursday'	,
							'5'		=> 'Friday'		,
							'6'		=> 'Saturday'	,
							'7'		=> 'Sunday'
							);
	return $day[$id];
} 

//--------------- Qualifications ----------
$qualtypes = array (
					array('id'=>1, 'name'=>'Bachelors')	,
					array('id'=>2, 'name'=>'Master')	,
					array('id'=>3, 'name'=>'Docrate')	,
					array('id'=>4, 'name'=>'Others')	
				   );

function get_qualtypes($id) {
	$qual = array (
							'1'		=> 'Bachelors'	,
							'2'		=> 'Master'		,
							'3'		=> 'Docrate'	,
							'4'		=> 'Others'
							);
	return $qual[$id];
} 
//--------------- Building ----------
$buildings = array (
					array('id'=>1, 'name'=>'Owned')				,
					array('id'=>2, 'name'=>'Rented')			,
					array('id'=>3, 'name'=>'To be arranged')	
					);
function get_buildings($id) {
	$build = array (
							'1'		=> 'Owned'				,
							'2'		=> 'Rented'				,
							'3'		=> 'To be arranged'		
							);
	return $build[$id];
} 
//--------------- Building Type ----------
$buildingtypes = array (
					array('id'=>1, 'name'=>'Resdential') ,
					array('id'=>2, 'name'=>'Commercial')		
				   );

function get_buildingtypes($id) {
	$building = array (
							'1'		=> 'Resdential'	,
							'2'		=> 'Commercial'		
							);
	return $building[$id];
} 
//--------------- Mediums ----------
$mediumtypes = array (
					array('id'=>1, 'name'=>'Resdential') ,
					array('id'=>2, 'name'=>'Commercial')		
				   );

function get_mediumtypes($id) {
	$medium = array (
							'1'		=> 'English'	,
							'2'		=> 'Urdu'		
							);
	return $medium[$id];
} 
//--------------- Investment Type ----------
$investypes = array (
					array('id'=>1, 'name'=>'Personal') 	  ,
					array('id'=>2, 'name'=>'Partnership') ,
					array('id'=>3, 'name'=>'Bank loan') 		
				   );

function get_investypes($id) {
	$investment = array (
							'1'		=> 'Personal'		,
							'2'		=> 'Partnership'	,
							'3'		=> 'Bank loan'		
							);
	return $investment[$id];
} 
//--------------- Calls ----------
$calltypes = array (
					array('id'=>1, 'name'=>'Incoming') ,
					array('id'=>2, 'name'=>'Out Going')		
				   );

function get_calltypes($id) {
	$calls = array (
							'1'		=> 'Incoming'	,
							'2'		=> 'Out Going'		
							);
	return $calls[$id];
} 

//--------------- Roles ----------
$rolefor = array (
	array('id'=>1,  'name'=>'Head Office')	,
	array('id'=>2,  'name'=>'Campus')		,
	array('id'=>3,  'name'=>'Both')		
);
function get_rolefor($id) {
	$role = array (
							'1'		=> 'Head Office'	,
							'2'		=> 'Campus'			,
							'3'		=> 'Both'		
							);
	return $role[$id];
}

//--------------- Roles ----------
$roletypes = array (
					array('id'=>1,  'name'=>'Admission')	,
					array('id'=>2,  'name'=>'Academic')		,
					array('id'=>3,  'name'=>'Attendance')	,
					array('id'=>4,  'name'=>'Exams')		,
					array('id'=>5,  'name'=>'HR')			,
					array('id'=>6,  'name'=>'Frenchies')	,
					array('id'=>7,  'name'=>'Complaints')	,
					array('id'=>8,  'name'=>'Accounts')		,
					array('id'=>9,  'name'=>'HR')			,
					array('id'=>10, 'name'=>'Frenchies')	,
					array('id'=>11, 'name'=>'Accounts')		,
					array('id'=>12, 'name'=>'Hostel')		,
					array('id'=>13, 'name'=>'Stationary')	,
					array('id'=>14, 'name'=>'Front Office')	,
					array('id'=>15, 'name'=>'Library')		,
					array('id'=>16, 'name'=>'Awards')		,
					array('id'=>17, 'name'=>'Events')		,
					array('id'=>18, 'name'=>'Admins')		,
					array('id'=>19, 'name'=>'Syllabus')		,
					array('id'=>20, 'name'=>'Montessori')
				   );

function get_roletypes($id) {
	$role = array (
							'1'		=> 'Admission'		,
							'2'		=> 'Academic'		,
							'3'		=> 'Attendance'		,
							'4'		=> 'Exams'			,
							'5'		=> 'HR'				,
							'6'		=> 'Frenchies'		,
							'7'		=> 'Complaints' 	,
							'8'		=> 'Accounts'		,
							'9'		=> 'HR'				,
							'10'	=> 'Frenchies'		,
							'11'	=> 'Accounts'		,
							'12'	=> 'Hostel'			,
							'13'	=> 'Stationary'		,
							'14'	=> 'Front Office'	,
							'15'	=> 'Library'		,
							'16'	=> 'Awards'			,
							'17'	=> 'Events'			,
							'18'	=> 'Admins'			,
							'19'	=> 'Syllabus'		,
							'20'	=> 'Montessori'		
							);
	return $role[$id];
}

//--------------- Transcation Type ----------
$transtype = array (
						array('id'=>1, 'name'=>'Credit'), array('id'=>2, 'name'=>'Debit')
				   );

function get_transtype($id) {
	
	$listtranstype = array (
								'1'	=> 'Credit',	'2'	=> 'Debit'
							 );
	return $listtranstype[$id];
}
//--------------- Transcation Method ------------------
$paymethod = array (
						array('id'=>1, 'name'=>'Cash')		, array('id'=>2, 'name'=>'Check')       , array('id'=>3, 'name'=>'Online')
				   );

function get_paymethod($id) {
	$listpaymethod= array (
							'1' => '<span class="label label-primary">Cash</span>', 
							'2' => '<span class="label label-warning">Check</span>', 
							'3' => '<span class="label label-warning">Online</span>');
	return $listpaymethod[$id];
}
$country = array('Bangladaish', 'China', 'India', 'Iran', 'Pakistan');
//--------------- Fee Duration ----------
// $feeduration = array('Yearly', 'Half', 'Quatar', 'Monthly');
$feeduration = array (
	array('id'=>1, 'name'=>'Yearly')	,
	array('id'=>2, 'name'=>'Half')      ,
	array('id'=>3, 'name'=>'Quatar')	,
	array('id'=>4, 'name'=>'Monthly')	,
	array('id'=>5, 'name'=>'Once')
);

function get_feeduration($id) {
	$listfeeduration= array (
			'1' => 'Yearly', 
			'2' => 'Half', 
			'3' => 'Quatar',
			'4' => 'Monthly',
			'5' => 'Once');
	return $listfeeduration[$id];
}
//--------------- Fee Type ----------
// $feetype = array('Refundable', 'Nonrefundable');

$feetype = array (
	array('id'=>1, 'name'=>'Refundable'),
	array('id'=>2, 'name'=>'Nonrefundable')
);

function get_feetype($id) {
$listfeetype= array (
		'1' => 'Refundable', 
		'2' => 'Nonrefundable'
	);
return $listfeetype[$id];
}

// Fee Summary Report Types
$summarytype = array (
    array('id'=>1, 'name'=>'Class-wise Challans Details'),
    array('id'=>2, 'name'=>'Month Wise Challans Summary'),
    array('id'=>3, 'name'=>'Individual Student Ledger'),
    array('id'=>4, 'name'=>'Class Wise Fees collection Report'),
    array('id'=>5, 'name'=>'Accumulative Fees Collection Report'),
    array('id'=>6, 'name'=>'Class-wise fee Receipt report'),
    array('id'=>7, 'name'=>'Student Fee receipt details')
   );
function get_summarytype($id) {
$summary = array (
            '1'     => 'Class-wise Challans Details',
            '2'     => 'Month Wise Challans Summary',
            '3'     => 'Individual Student Ledger',
            '4'     => 'Class Wise Fees collection Report',
            '5'     => 'Accumulative Fees Collection Report',
            '6'     => 'Class-wise fee Receipt report',
            '7'     => 'Student Fee receipt details'
            );
return $summary[$id];
}

//--------------- Gender ----------
$gender = array('Female', 'Male');
//--------------- Religion ----------
$religion = array('Islam', 'Christan', 'Hindu', 'Sikeh', 'Any other');
//--------------- Marital Status ----------
$marital = array('Married', 'Single');
//----------------Blood Groups------------------------------
$bloodgroup = array('A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-');
//---------------------------------------
/*function cleanvars($str) {
		$str = trim($str);
		$str = mysql_escape_string($str);

	return($str);
}
*/
function cleanvars($str){ 
	return is_array($str) ? array_map('cleanvars', $str) : str_replace("\\", "\\\\", htmlspecialchars( stripslashes($str), ENT_QUOTES)); 
}
//----------------------------------------
function to_seo_url($str){
   // if($str !== mb_convert_encoding( mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
      //  $str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
    $str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
    $str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $str);
    $str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
    $str = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $str);
    $str = trim($str, '-');
    return $str;
}
//--------------- Login Types ------------------
$logintypes = array (
	array('id'=>1, 'name'=>'headoffice')	,
	array('id'=>2, 'name'=>'campus')		,
	array('id'=>3, 'name'=>'teacher')		,
	array('id'=>4, 'name'=>'parent')		,
	array('id'=>5, 'name'=>'student')
   );

function get_logintypes($id) {
$listlogintypes = array (

			'1'	=> 'headoffice'				,
			'2'	=> 'campus'					,
			'3'	=> 'teacher'				,
			'4'	=> 'parent'					,
			'5'	=> 'student'
			);
	return $listlogintypes[$id];
}
//--------------- Log File Action----------
function get_logfile($id) {

	$listlogfile = array (
							'1' => 'Add'		, 
							'2' => 'Update'		, 
							'3' => 'Delete'		,
							'4' => 'Login'	
						  );
	return $listlogfile[$id];

}

//--------------- Arrary Search ------------------
function arrayKeyValueSearch($array, $key, $value)
{
    $results = array();
    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }
        foreach ($array as $subArray) {
            $results = array_merge($results, arrayKeyValueSearch($subArray, $key, $value));
        }
    }
    return $results;
}

//----------Get Current Url------------------------------
function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
 return $pageURL;
}
//---------Return Grade--------------
function getGrade($percentage){

	if ($percentage >= 80)
    	$grade = "A+";
	else if ($percentage >= 70 && $percentage < 80)
		$grade = "A";
	else if ($percentage >= 60 && $percentage < 70)
		$grade = "B";
	else if ($percentage >= 50 && $percentage < 60)
		$grade = "C";
	else if ($percentage >= 40 && $percentage < 50)
		$grade = "D";
	else if ($percentage >= 33 && $percentage < 40)
		$grade = "E";
	else
		$grade = "Fail";

	return $grade;
}
//----------Days Name------------------------------
$days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

//--------------- Class Level List ------------------
$classlevel = array(	
	  array('id'=>1, 'name'=>'Pre')
	, array('id'=>2, 'name'=>'Primary')
	, array('id'=>3, 'name'=>'Middle')
	, array('id'=>4, 'name'=>'Comprehensive')
);

function get_classlevel($id=''){
	$listclasslevel = array (
		  '1'	=> 'Pre'
		, '2'	=> 'Primary'
		, '3'	=> 'Middle'
		, '4'	=> 'Comprehensive'
	);
	return (!empty($id)?$listclasslevel[$id]:$listclasslevel);
}


function get_downloadTypes($id = ''){
	$downloadTypes = array (
							  '1'	=> 'Printing Material'
							, '2'	=> 'Soft Files'
							, '3'	=> 'Videos'
						   );
	if(!empty($id)){
		return $downloadTypes[$id];
	}else{
		return $downloadTypes;
	}
}

function get_socialtype($id = ''){
	$socialtype = array (
							  '1'	=> 'Facebook'
							, '2'	=> 'Youtube'
							, '3'	=> 'Tiktok'
							, '4'	=> 'Twitter'
							, '5'	=> 'Location'
						   );
	if(!empty($id)){
		return $socialtype[$id];
	}else{
		return $socialtype;
	}
}

function get_MontessoriTypes($id = ''){
	$montessoriTypes = array (
							  '1'	=> 'Online Session'
							, '2'	=> 'On Site/City'
						   );
	if(!empty($id)){
		return $montessoriTypes[$id];
	}else{
		return $montessoriTypes;
	}
}
function get_duringclass($id = "") {
	$guringClass = array (
							  '1'	=> 'Passed'				
							, '2'	=> 'Failed'					
							, '3'	=> 'During Class'				
						);
	return (!empty($id))? $guringClass[$id]: $guringClass;
}
function makeCnic($str) {
	return substr($str, 0, 4) . '-' . substr($str, 5, 11) . '-' . substr($str, 12);
}
function errorMsg($title = "", $msg = "", $color = "") {
	if (!empty($title) && !empty($msg)&& !empty($color))
	{
		$_SESSION['msg']['title'] 	= ''.$title.'';
		$_SESSION['msg']['text'] 	= ''.$msg.'';
		$_SESSION['msg']['type'] 	= ''.$color.'';	
		if (!empty($_SESSION['msg']['title']) && !empty($_SESSION['msg']['text'])&& !empty($_SESSION['msg']['info']))
			return true;	
		else
			return false;	
	}
	else
		return false;	
}
function get_publish($id) {
	$liststatus= array (
							'1' => '<span class="label label-info">Yes</span>', 
							'0' => '<span class="label label-warning">No</span>');
	return $liststatus[$id];
}
function get_AssessmentType($id = "") {
	$listassessment= array (
							  '1' => '1<sup>st</sup>' 
							, '2' => '2<sup>nd</sup>'
							, '3' => '3<sup>rd</sup>'
						);
	return (!empty($id)? $listassessment[$id]: $listassessment);
}
function get_AreaZone($id = "") {
	$listAreaZone= array (
							  '1' => 'Panjab' 
							, '2' => 'Sindh'
							, '3' => 'KPK'
							, '4' => 'AJK'
						);
	return (!empty($id)? $listAreaZone[$id]: $listAreaZone);
}

function get_royaltyTypes($id = ''){
	$royaltyTypes = array (
							  '1'	=> 'Per Month'
							, '2'	=> 'Per Student'
						   );
	if(!empty($id)){
		return $royaltyTypes[$id];
	}else{
		return $royaltyTypes;
	}
}

function get_timeAgo($time) {
    $time = strtotime($time);
    $diff = time() - $time;
    
    if ($diff < 60) {
        return 'just now';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' minute' . ($mins > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 2592000) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 31536000) {
        $months = floor($diff / 2592000);
        return $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
    } else {
        $years = floor($diff / 31536000);
        return $years . ' year' . ($years > 1 ? 's' : '') . ' ago';
    }
}

function get_dataHashingOnlyExp($str = '', $flag = true) {
    if (!empty($str)) {
        $e_key     = "m^@c$&d#~l";
        $e_chiper  = "AES-128-CTR";
        $e_iv      = "4327890237234803";
        $e_option  = 0;

        if ($flag) {
            // Encrypt and then encode to base64
            $encrypted = openssl_encrypt($str, $e_chiper, $e_key, $e_option, $e_iv);
            return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($encrypted));
        } else {
            // Decode from base64 and then decrypt
            $decoded = base64_decode(str_replace(['-', '_'], ['+', '/'], $str));
            return openssl_decrypt($decoded, $e_chiper, $e_key, $e_option, $e_iv);
        }
    } else {
        return false;
    }
}
?>