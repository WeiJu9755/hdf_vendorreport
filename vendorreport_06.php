<?php

//error_reporting(E_ALL); 
//ini_set('display_errors', '1');

session_start();

$memberID = $_SESSION['memberID'];
$powerkey = $_SESSION['powerkey'];


require_once '/website/os/Mobile-Detect-2.8.34/Mobile_Detect.php';
$detect = new Mobile_Detect;

if (!($detect->isMobile() && !$detect->isTablet())) {
	$isMobile = "0";
} else {
	$isMobile = "1";
}


$m_location		= "/website/smarty/templates/".$site_db."/".$templates;
$m_pub_modal	= "/website/smarty/templates/".$site_db."/pub_modal";


//載入公用函數
@include_once '/website/include/pub_function.php';

@include_once("/website/class/".$site_db."_info_class.php");


//檢查是否為管理員及進階會員
$super_admin = "N";
$super_advanced = "N";
$mem_row = getkeyvalue2('memberinfo','member',"member_no = '$memberID'",'admin,advanced');
$super_admin = $mem_row['admin'];
$super_advanced = $mem_row['advanced'];

// 初評中明細表格

$mDB1 = "";
$mDB1 = new MywebDB();


$Qry1="SELECT a.*,b.engineering_name,c.builder_name,d.contractor_name,e.employee_name,f.company_name,f.short_name FROM CaseManagement a
LEFT JOIN construction b ON b.construction_id = a.construction_id
LEFT JOIN builder c ON c.builder_id = a.builder_id
LEFT JOIN contractor d ON d.contractor_id = a.contractor_id
LEFT JOIN employee e ON e.employee_id = a.Handler
LEFT JOIN company f ON f.company_id = a.company_id
WHERE a.status1 = '未簽約' AND a.status2 = '評估中'
AND a.status1 <> '已完工'
ORDER BY a.auto_seq";

$mDB1->query($Qry1);
$casereport_list_1_1 = "";

$casereport_list_1.=<<<EOT
<div class="w-100 m-auto px-3" style="min-height:300px;margin-bottom: 100px;">
	<div class="w-100" style="overflow-x: auto;">
		<div class="w-100" style="min-width:1760px;">
EOT;


$total_1 = $mDB1->rowCount();
if ($total_1 > 0) {

$casereport_list_1.=<<<EOT
	<table class="table table-bordered border-dark w-100">
		<thead class="table-light border-dark">
			<tr style="border-bottom: 1px solid #000;">
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">狀態(1)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">狀態(2)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">區域</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">案件編號</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">工程名稱</th>
				<!-- <th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">上包-建商名稱</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">上包-營造廠名稱</th> -->
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">案場位置</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">承攬模式</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">所屬公司</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">經辦人員</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">建物棟數</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">初評發送日期</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">預計回饋日期</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">初評狀態</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">備註</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">報價日期</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">預計進場日期</th>
			</tr>
		</thead>
		<tbody class="table-group-divider">
EOT;

    while ($row_1=$mDB1->fetchRow(2)) {
		$auto_seq = $row_1['auto_seq'];

		$status1 = $row_1['status1'];
		$status2 = $row_1['status2'];
		$region = $row_1['region'];
		$case_id = $row_1['case_id'];
		$construction_id = $row_1['construction_id'];
		$engineering_name = $row_1['engineering_name'];
		$builder_id = $row_1['builder_id'];
		$builder_name = $row_1['builder_name'];
		$contractor_id = $row_1['contractor_id'];
		$contractor_name = $row_1['contractor_name'];
		$contact = $row_1['contact'];
		//$site_location = $row_1['site_location'];
		$county = $row_1['county'];
		$town = $row_1['town'];
		$zipcode = $row_1['zipcode'];
		$site_location = $county.$town;
		$ContractingModel = $row_1['ContractingModel'];
		$Handler = $row_1['Handler'];
		$employee_name = $row_1['employee_name'];
		$buildings = $row_1['buildings'];
		$first_review_date = $row_1['first_review_date'];

		if ($first_review_date == "0000-00-00")
			$first_review_date = "";

		$estimated_return_date = $row_1['estimated_return_date'];

		if ($estimated_return_date == "0000-00-00")
			$estimated_return_date = "";

		$preliminary_status = $row_1['preliminary_status'];
		$remark = $row_1['remark'];
		$quotation_date = $row_1['quotation_date'];

		if ($quotation_date == "0000-00-00")
			$quotation_date = "";

		$estimated_arrival_date = $row_1['estimated_arrival_date'];

		if ($estimated_arrival_date == "0000-00-00")
			$estimated_arrival_date = "";

		$company_id = $row_1['company_id'];
		$company_name = $row_1['short_name'];
		if (empty($company_name))
			$company_name = $row_1['company_name'];
		

$casereport_list_1.=<<<EOT
			<tr>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$status1</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$status2</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$region</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$case_id</th>
				<th class="text-center" style="width:5%;padding: 10px;">$construction_id</th>
				<!-- <th class="text-center" style="width:5%;padding: 10px;">$builder_name<br>$builder_id</th> -->

				<th class="text-center" style="width:5%;padding: 10px;">$contact</th>
				<th class="text-center" style="width:5%;padding: 10px;">$site_location</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$ContractingModel</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$company_name<br>$company_id</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$employee_name</th>
				<th class="text-center" style="width:5%;padding: 10px;">$buildings</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$first_review_date</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$estimated_return_date</th>
				<th class="text-center" style="width:5%;padding: 10px;">$preliminary_status</th>
				<th class="text-center" style="width:5%;padding: 10px;">$remark</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$quotation_date</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$estimated_arrival_date</th>
			</tr>

EOT;

	}

	$fmt_total = number_format($total_1);

$casereport_list_1.=<<<EOT
		</tbody>
	</table>
	<div class="size14 weight">案件合計：{$fmt_total}件</div>
EOT;


} else {

$casereport_list_1.=<<<EOT
	<div class="size16 weight p-5 text-center">無任何符合查詢的資料</div>
EOT;

}

$casereport_list_1.=<<<EOT
		</div>
	</div>
</div>
EOT;


$mDB1->remove();

// 未訂約明細表格


$mDB2 = "";
$mDB2 = new MywebDB();


$Qry2="SELECT a.*,b.engineering_name,c.builder_name,d.contractor_name,e.employee_name,f.company_name,f.short_name FROM CaseManagement a
LEFT JOIN construction b ON b.construction_id = a.construction_id
LEFT JOIN builder c ON c.builder_id = a.builder_id
LEFT JOIN contractor d ON d.contractor_id = a.contractor_id
LEFT JOIN employee e ON e.employee_id = a.Handler
LEFT JOIN company f ON f.company_id = a.company_id
WHERE a.status1 = '未簽約' AND a.status2 = '已報價'
AND a.status1 <> '已完工'
ORDER BY a.auto_seq";

$mDB2->query($Qry2);
$casereport_list_2 = "";

$casereport_list_2.=<<<EOT
<div class="w-100 m-auto px-3" style="min-height:300px;margin-bottom: 100px;">
	<div class="w-100" style="overflow-x: auto;">
		<div class="w-100" style="min-width:1760px;">
EOT;


$total2 = $mDB2->rowCount();
if ($total2 > 0) {

$casereport_list_2.=<<<EOT
	<table class="table table-bordered border-dark w-100">
		<thead class="table-light border-dark">
			<tr style="border-bottom: 1px solid #000;">
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">狀態(1)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">狀態(2)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">區域</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">案件編號</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">工程名稱</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">上包-建商名稱</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">上包-營造廠名稱</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">案場位置</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">承攬模式</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">所屬公司</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">經辦人員</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">建物棟數</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">工程量(M2)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">標準層模板數量(M2)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">屋突層模板數量(M2)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">報價單是否送出</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">報價日期</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">預計進場日期</th>
			</tr>
		</thead>
		<tbody class="table-group-divider">
EOT;

    while ($row2=$mDB2->fetchRow(2)) {
		$auto_seq = $row2['auto_seq'];

		$status1 = $row2['status1'];
		$status2 = $row2['status2'];
		$region = $row2['region'];
		$case_id = $row2['case_id'];
		$construction_id = $row2['construction_id'];
		$engineering_name = $row2['engineering_name'];
		$builder_id = $row2['builder_id'];
		$builder_name = $row2['builder_name'];
		$contractor_id = $row2['contractor_id'];
		$contractor_name = $row2['contractor_name'];
		$contact = $row2['contact'];
		//$site_location = $row2['site_location'];
		$county = $row2['county'];
		$town = $row2['town'];
		$zipcode = $row2['zipcode'];
		$site_location = $county.$town;
		$ContractingModel = $row2['ContractingModel'];
		$Handler = $row2['Handler'];
		$employee_name = $row2['employee_name'];
		
		$company_id = $row2['company_id'];
		$company_name = $row2['short_name'];
		if (empty($company_name))
			$company_name = $row2['company_name'];

		//建物棟數
		$buildings = $row2['buildings'];
		//工程量(M2)
		$engineering_qty = $row2['engineering_qty'];
		$fmt_engineering_qty = number_format($engineering_qty);
		//標準層模板數量(M2)
		$std_layer_template_qty = $row2['std_layer_template_qty'];
		$fmt_std_layer_template_qty = number_format($std_layer_template_qty);
		//屋突層模板數量(M2)
		$roof_protrusion_template_qty = $row2['roof_protrusion_template_qty'];
		$fmt_roof_protrusion_template_qty = number_format($roof_protrusion_template_qty);
		//材料金額
		$material_amt = $row2['material_amt'];
		$fmt_material_amt = number_format($material_amt);
		//代工費用
		$OEM_cost = $row2['OEM_cost'];
		$fmt_OEM_cost = number_format($OEM_cost);
		//報價金額(未稅)
		$quotation_amt = $row2['quotation_amt'];
		$fmt_quotation_amt = number_format($quotation_amt);
		//報價單是否送出
		$quotation_sended = $row2['quotation_sended'];

		//報價日期
		$quotation_date = $row2['quotation_date'];
		if ($quotation_date == "0000-00-00")
			$quotation_date = "";

		//預計進場日期
		$estimated_arrival_date = $row2['estimated_arrival_date'];
		if ($estimated_arrival_date == "0000-00-00")
			$estimated_arrival_date = "";


		//依報價日期的時間做顏色區分，未滿14天原白色，已滿14天黃色，已滿30天藍色
		//$bgcolor = "background-color:#FFE599;color:red;";
		$bgcolor = "";

		// 取得今天的日期
		$today = new DateTime("now");

		//定義目標日期
		if ($estimated_arrival_date <> "0000-00-00") {
			$targetDate = new DateTime($quotation_date);

			//計算差異
			$interval = $today->diff($targetDate);

			//獲取差異天數
			$days = $interval->days;

			//判斷日期是未來還是過去
			if ($interval->invert) {
				if ($days >= 14) {
					$bgcolor = "background-color:#FFE599;color:red;";
					if ($days >= 30) {
						$bgcolor = "background-color:#A4C2F4;color:red;";
					}
				}

			}

		}






		//$makeby = $row2['makeby'];
		//$content = nl2br_skip_html(htmlspecialchars_decode($row2['content']));


$casereport_list_2.=<<<EOT
			<tr>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$status1</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$status2</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$region</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$case_id</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$construction_id</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$builder_name<br>$builder_id</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$contact</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$site_location</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$ContractingModel</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$company_name<br>$company_id</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$employee_name</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$buildings</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$fmt_engineering_qty</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$fmt_std_layer_template_qty</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$fmt_roof_protrusion_template_qty</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$quotation_sended</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$quotation_date</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$estimated_arrival_date</th>
			</tr>

EOT;

	}

	$fmt_total = number_format($total2);

$casereport_list_2.=<<<EOT
		</tbody>
	</table>
	<div class="size14 weight">案件合計：{$fmt_total}件</div>
EOT;


} else {

$casereport_list_2.=<<<EOT
	<div class="size16 weight p-5 text-center">無任何符合查詢的資料</div>
EOT;

}

$casereport_list_2.=<<<EOT
		</div>
	</div>
</div>
EOT;



$mDB2->remove();	

// 已回簽未用印明細表格

$mDB3 = "";
$mDB3 = new MywebDB();


$Qry3="SELECT a.*,b.engineering_name,c.builder_name,d.contractor_name,e.employee_name,f.company_name,f.short_name FROM CaseManagement a
LEFT JOIN construction b ON b.construction_id = a.construction_id
LEFT JOIN builder c ON c.builder_id = a.builder_id
LEFT JOIN contractor d ON d.contractor_id = a.contractor_id
LEFT JOIN employee e ON e.employee_id = a.Handler
LEFT JOIN company f ON f.company_id = a.company_id
WHERE a.status1 = '未簽約' AND a.status2 = '已回簽'
AND a.status1 <> '已完工'
ORDER BY a.auto_seq";

$mDB3->query($Qry3);
$casereport_list_3 = "";

$casereport_list_3.=<<<EOT
<div class="w-100 m-auto px-3" style="min-height:300px;margin-bottom: 100px;">
	<div class="w-100" style="overflow-x: auto;">
		<div class="w-100" style="min-width:1760px;">
EOT;


$total3 = $mDB3->rowCount();
if ($total3 > 0) {

$casereport_list_3.=<<<EOT
	<table class="table table-bordered border-dark w-100">
		<thead class="table-light border-dark">
			<tr style="border-bottom: 1px solid #000;">
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">狀態(1)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">狀態(2)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">區域</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">案件編號</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">工程名稱</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">上包-建商名稱</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">上包-營造廠名稱</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">案場位置</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">承攬模式</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">所屬公司</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">經辦人員</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">預計進場日期</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">鋁模材料<br>利舊/新購</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">建物棟數</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">工程量(M2)</th>
			</tr>
		</thead>
		<tbody class="table-group-divider">
EOT;

    while ($row3=$mDB3->fetchRow(2)) {
		$auto_seq = $row3['auto_seq'];

		$status1 = $row3['status1'];
		$status2 = $row3['status2'];
		$region = $row3['region'];
		$case_id = $row3['case_id'];
		$construction_id = $row3['construction_id'];
		$engineering_name = $row3['engineering_name'];
		$builder_id = $row3['builder_id'];
		$builder_name = $row3['builder_name'];
		$contractor_id = $row3['contractor_id'];
		$contractor_name = $row3['contractor_name'];
		$contact = $row3['contact'];
		//$site_location = $row3['site_location'];
		$county = $row3['county'];
		$town = $row3['town'];
		$zipcode = $row3['zipcode'];
		$site_location = $county.$town;
		$ContractingModel = $row3['ContractingModel'];
		$Handler = $row3['Handler'];
		$employee_name = $row3['employee_name'];
		
		//預計進場日期
		$estimated_arrival_date = $row3['estimated_arrival_date'];
		if ($estimated_arrival_date == "0000-00-00")
			$estimated_arrival_date = "";

		//鋁模材料
		$geto_formwork = $row3['geto_formwork'];
		//建物棟數
		$buildings = $row3['buildings'];
		//工程量(M2)
		$engineering_qty = $row3['engineering_qty'];
		$fmt_engineering_qty = number_format($engineering_qty);

		//上包合約簽訂時間
		$contract_date = $row3['contract_date'];
		if ($contract_date == "0000-00-00")
			$contract_date = "";

		//報價金額(未稅)
		$quotation_amt = $row3['quotation_amt'];
		$fmt_quotation_amt = number_format($quotation_amt);


		//$makeby = $row3['makeby'];
		//$content = nl2br_skip_html(htmlspecialchars_decode($row3['content']));

		$company_id = $row3['company_id'];
		$company_name = $row3['short_name'];
		if (empty($company_name))
			$company_name = $row3['company_name'];


$casereport_list_3.=<<<EOT
			<tr>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$status1</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$status2</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$region</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$case_id</th>
				<th class="text-center" style="width:5%;padding: 10px;">$construction_id</th>
				<th class="text-center" style="width:5%;padding: 10px;">$builder_name<br>$builder_id</th>
				<th class="text-center" style="width:5%;padding: 10px;">$contact</th>
				<th class="text-center" style="width:5%;padding: 10px;">$site_location</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$ContractingModel</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$company_name<br>$company_id</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$employee_name<br>$Handler</th>
				<th class="text-center" style="width:5%;padding: 10px;">$estimated_arrival_date</th>
				<th class="text-center" style="width:5%;padding: 10px;">$geto_formwork</th>
				<th class="text-center" style="width:5%;padding: 10px;">$buildings</th>
				<th class="text-center" style="width:5%;padding: 10px;">$fmt_engineering_qty</th>
			</tr>

EOT;

	}

	$fmt_total = number_format($total3);

$casereport_list_3.=<<<EOT
		</tbody>
	</table>
	<div class="size14 weight">案件合計：{$fmt_total}件</div>
EOT;


} else {

$casereport_list_3.=<<<EOT
	<div class="size16 weight p-5 text-center">無任何符合查詢的資料</div>
EOT;

}

$casereport_list_3.=<<<EOT
		</div>
	</div>
</div>
EOT;


$mDB3->remove();

// 已訂約「未進場」明細表格

$mDB4 = "";
$mDB4 = new MywebDB();


$Qry4="SELECT a.*,b.engineering_name,c.builder_name,d.contractor_name,e.employee_name,f.company_name,f.short_name FROM CaseManagement a
LEFT JOIN construction b ON b.construction_id = a.construction_id
LEFT JOIN builder c ON c.builder_id = a.builder_id
LEFT JOIN contractor d ON d.contractor_id = a.contractor_id
LEFT JOIN employee e ON e.employee_id = a.Handler
LEFT JOIN company f ON f.company_id = a.company_id
WHERE a.status1 = '已簽約' AND a.status2 = '未進場'
AND a.status1 <> '已完工'
ORDER BY a.auto_seq";

$mDB4->query($Qry4);
$casereport_list_4 = "";

$casereport_list_4.=<<<EOT
<div class="w-100 m-auto p-3" style="min-height:300px;margin-bottom: 100px;">
	<div class="w-100">
		<div class="w-100" style="min-width:1760px;">
EOT;


$total4 = $mDB4->rowCount();
if ($total4 > 0) {

$casereport_list_4.=<<<EOT
	<table class="table table-bordered border-dark w-100">
		<thead class="table-light border-dark">
			<tr style="border-bottom: 1px solid #000;">
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">狀態(1)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">狀態(2)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">區域</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">案件編號</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">工程名稱</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">上包-建商名稱</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">上包-營造廠名稱</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">案場位置</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">承攬模式</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">所屬公司</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">經辦人員</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">預計進場日期</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">建物棟數</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">工程量(M2)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">上包合約<br>簽訂時間</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">志特編號</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">志特合約<br>簽訂日期</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">鋁模材料<br>利舊/新購</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">材料進口日期</th>
			</tr>
		</thead>
		<tbody class="table-group-divider">
EOT;

    while ($row4=$mDB4->fetchRow(2)) {
		$auto_seq = $row4['auto_seq'];

		$status1 = $row4['status1'];
		$status2 = $row4['status2'];
		$region = $row4['region'];
		$case_id = $row4['case_id'];
		$construction_id = $row4['construction_id'];
		$engineering_name = $row4['engineering_name'];
		$builder_id = $row4['builder_id'];
		$builder_name = $row4['builder_name'];
		$contractor_id = $row4['contractor_id'];
		$contractor_name = $row4['contractor_name'];
		$contact = $row4['contact'];
		//$site_location = $row4['site_location'];
		$county = $row4['county'];
		$town = $row4['town'];
		$zipcode = $row4['zipcode'];
		$site_location = $county.$town;
		$ContractingModel = $row4['ContractingModel'];
		$Handler = $row4['Handler'];
		$employee_name = $row4['employee_name'];
		
		//預計進場日期
		$estimated_arrival_date = $row4['estimated_arrival_date'];
		if ($estimated_arrival_date == "0000-00-00")
			$estimated_arrival_date = "";

		//建物棟數
		$buildings = $row4['buildings'];
		//工程量(M2)
		$engineering_qty = $row4['engineering_qty'];
		$fmt_engineering_qty = number_format($engineering_qty);
		//上包合約簽訂時間
		$contract_date = $row4['contract_date'];
		if ($contract_date == "0000-00-00")
			$contract_date = "";
		//合約總價(含稅)
		$total_contract_amt = $row4['total_contract_amt'];
		$fmt_total_contract_amt = number_format($total_contract_amt);
		//第一期預收款請款方式
		$advance_payment1 = $row4['advance_payment1'];
		//第一期請款日期
		$request_date1 = $row4['request_date1'];
		if ($request_date1 == "0000-00-00")
			$request_date1 = "";
		//第二期預收款請款方式
		$advance_payment2 = $row4['advance_payment2'];
		//第二期請款日期
		$request_date2 = $row4['request_date2'];
		if ($request_date2 == "0000-00-00")
			$request_date2 = "";
		//第三期預收款請款方式
		$advance_payment3 = $row4['advance_payment3'];
		//第工期請款日期
		$request_date3 = $row4['request_date3'];
		if ($request_date3 == "0000-00-00")
			$request_date3 = "";
		//志特編號
		$geto_no = $row4['geto_no'];
		//志特合約簽訂日期
		$geto_contract_date = $row4['geto_contract_date'];
		//鋁模材料
		$geto_formwork = $row4['geto_formwork'];
		//材料進口日期
		$material_import_date = $row4['material_import_date'];

		//$makeby = $row4['makeby'];
		//$content = nl2br_skip_html(htmlspecialchars_decode($row4['content']));

		$company_id = $row4['company_id'];
		$company_name = $row4['short_name'];
		if (empty($company_name))
			$company_name = $row4['company_name'];


$casereport_list_4.=<<<EOT
			<tr>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$status1</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$status2</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$region</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$case_id</th>
				<th class="text-center" style="width:5%;padding: 10px;">$construction_id</th>
				<th class="text-center" style="width:5%;padding: 10px;">$builder_name<br>$builder_id</th>
				<th class="text-center" style="width:5%;padding: 10px;">$contact</th>
				<th class="text-center" style="width:5%;padding: 10px;">$site_location</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$ContractingModel</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$company_name<br>$company_id</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$employee_name<br>$Handler</th>
				<th class="text-center" style="width:5%;padding: 10px;">$estimated_arrival_date</th>
				<th class="text-center" style="width:5%;padding: 10px;">$buildings</th>
				<th class="text-center" style="width:5%;padding: 10px;">$fmt_engineering_qty</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$contract_date</th>
				<th class="text-center" style="width:5%;padding: 10px;">$geto_no</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$geto_contract_date</th>
				<th class="text-center" style="width:5%;padding: 10px;">$geto_formwork</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$material_import_date</th>
			</tr>

EOT;

	}

	$fmt_total = number_format($total4);

$casereport_list_4.=<<<EOT
		</tbody>
	</table>
	<div class="size14 weight">案件合計：{$fmt_total}件</div>
EOT;


} else {

$casereport_list_4.=<<<EOT
	<div class="size16 weight p-5 text-center">無任何符合查詢的資料</div>
EOT;

}

$casereport_list_4.=<<<EOT
		</div>
	</div>
</div>
EOT;


$mDB4->remove();

$mDB5 = "";
$mDB5 = new MywebDB();


$Qry5="SELECT a.*,b.engineering_name,c.builder_name,d.contractor_name,e.employee_name,f.company_name,f.short_name FROM CaseManagement a
LEFT JOIN construction b ON b.construction_id = a.construction_id
LEFT JOIN builder c ON c.builder_id = a.builder_id
LEFT JOIN contractor d ON d.contractor_id = a.contractor_id
LEFT JOIN employee e ON e.employee_id = a.Handler
LEFT JOIN company f ON f.company_id = a.company_id
WHERE a.status1 = '已簽約' AND a.status2 = '進行中'
AND a.status1 <> '已完工'
ORDER BY a.auto_seq";

$mDB5->query($Qry5);
$casereport_list_5 = "";

$casereport_list_5.=<<<EOT
<div class="w-100 m-auto px-3" style="min-height:300px;margin-bottom: 100px;">
	<div class="w-100">
		<div class="w-100" style="min-width:1760px;">
EOT;


$total5 = $mDB5->rowCount();
if ($total5 > 0) {

$casereport_list_5.=<<<EOT
	<table class="table table-bordered border-dark w-100">
		<thead class="table-light border-dark">
			<tr style="border-bottom: 1px solid #000;">
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">狀態(1)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">狀態(2)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">區域</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">案件編號</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">工程名稱</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">上包-建商名稱</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">上包-營造廠名稱</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">案場位置</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">承攬模式</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">所屬公司</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">經辦人員</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">預計進場日期</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">實際進場日期</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">建物棟數</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">工程量(M2)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">上包合約<br>簽訂時間</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">志特編號</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">志特合約<br>簽訂日期</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">鋁模材料<br>利舊/新購</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #CBF3FC;">材料進口日期</th>
			</tr>
		</thead>
		<tbody class="table-group-divider">
EOT;

    while ($row5=$mDB5->fetchRow(2)) {
		$auto_seq = $row5['auto_seq'];

		$status1 = $row5['status1'];
		$status2 = $row5['status2'];
		$region = $row5['region'];
		$case_id = $row5['case_id'];
		$construction_id = $row5['construction_id'];
		$engineering_name = $row5['engineering_name'];
		$builder_id = $row5['builder_id'];
		$builder_name = $row5['builder_name'];
		$contractor_id = $row5['contractor_id'];
		$contractor_name = $row5['contractor_name'];
		$contact = $row5['contact'];
		//$site_location = $row5['site_location'];
		$county = $row5['county'];
		$town = $row5['town'];
		$zipcode = $row5['zipcode'];
		$site_location = $county.$town;
		$ContractingModel = $row5['ContractingModel'];
		$Handler = $row5['Handler'];
		$employee_name = $row5['employee_name'];
		
		//預計進場日期
		$estimated_arrival_date = $row5['estimated_arrival_date'];
		if ($estimated_arrival_date == "0000-00-00")
			$estimated_arrival_date = "";

		//實際進場日期
		$actual_entry_date = $row5['actual_entry_date'];
		if ($actual_entry_date == "0000-00-00")
			$actual_entry_date = "";

		//建物棟數
		$buildings = $row5['buildings'];
		//工程量(M2)
		$engineering_qty = $row5['engineering_qty'];
		$fmt_engineering_qty = number_format($engineering_qty);
		//上包合約簽訂時間
		$contract_date = $row5['contract_date'];
		if ($contract_date == "0000-00-00")
			$contract_date = "";
		//合約總價(含稅)
		$total_contract_amt = $row5['total_contract_amt'];
		$fmt_total_contract_amt = number_format($total_contract_amt);
		//第一期預收款請款方式
		$advance_payment1 = $row5['advance_payment1'];
		//第一期請款日期
		$request_date1 = $row5['request_date1'];
		if ($request_date1 == "0000-00-00")
			$request_date1 = "";
		//第二期預收款請款方式
		$advance_payment2 = $row5['advance_payment2'];
		//第二期請款日期
		$request_date2 = $row5['request_date2'];
		if ($request_date2 == "0000-00-00")
			$request_date2 = "";
		//第三期預收款請款方式
		$advance_payment3 = $row5['advance_payment3'];
		//第工期請款日期
		$request_date3 = $row5['request_date3'];
		if ($request_date3 == "0000-00-00")
			$request_date3 = "";
		//志特編號
		$geto_no = $row5['geto_no'];
		//志特合約簽訂日期
		$geto_contract_date = $row5['geto_contract_date'];
		//鋁模材料利舊/新購
		$geto_formwork = $row5['geto_formwork'];
		//材料進口日期
		$material_import_date = $row5['material_import_date'];

		//$makeby = $row5['makeby'];
		//$content = nl2br_skip_html(htmlspecialchars_decode($row5['content']));

		$company_id = $row5['company_id'];
		$company_name = $row5['short_name'];
		if (empty($company_name))
			$company_name = $row5['company_name'];


$casereport_list_5.=<<<EOT
			<tr>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$status1</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$status2</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$region</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$case_id</th>
				<th class="text-center" style="width:5%;padding: 10px;">$construction_id</th>
				<th class="text-center" style="width:5%;padding: 10px;">$builder_name<br>$builder_id</th>

				<th class="text-center" style="width:5%;padding: 10px;">$contact</th>
				<th class="text-center" style="width:5%;padding: 10px;">$site_location</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$ContractingModel</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$company_name<br>$company_id</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$employee_name<br>$Handler</th>
				<th class="text-center" style="width:5%;padding: 10px;">$estimated_arrival_date</th>
				<th class="text-center" style="width:5%;padding: 10px;">$actual_entry_date</th>
				<th class="text-center" style="width:5%;padding: 10px;">$buildings</th>
				<th class="text-center" style="width:5%;padding: 10px;">$fmt_engineering_qty</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$contract_date</th>
				<th class="text-center" style="width:5%;padding: 10px;">$geto_no</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$geto_contract_date</th>
				<th class="text-center" style="width:5%;padding: 10px;">$geto_formwork</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$material_import_date</th>
			</tr>

EOT;

	}

	$fmt_total = number_format($total5);

$casereport_list_5.=<<<EOT
		</tbody>
	</table>
	<div class="size14 weight">案件合計：{$fmt_total}件</div>
EOT;


} else {

$casereport_list_5.=<<<EOT
	<div class="size16 weight p-5 text-center">無任何符合查詢的資料</div>
EOT;

}

$casereport_list_5.=<<<EOT
		</div>
	</div>
</div>
EOT;


$mDB5->remove();


$show_report=<<<EOT
<div class="mytable w-100 bg-white p-3 mt-3">
	<div class="myrow">
		<!-- 左上角固定的錨點按鈕區塊 -->
		<div style="position: fixed; top: 0; left: 0; width: 100%; padding: 10px; z-index: 9999;">
			<div class="mb-2 fw-bold text-dark" style="font-size: 20px;">合作廠商報表</div>
			<div class="btn-group" role="group">
				<a class="btn btn-warning btn-sm mx-1 fw-bold" href="#casereport_01">初評中</a>
				<a class="btn btn-warning btn-sm mx-1 fw-bold" href="#casereport_02">未訂約</a>
				<a class="btn btn-warning btn-sm mx-1 fw-bold" href="#casereport_03">回簽未用印</a>
				<a class="btn btn-warning btn-sm mx-1 fw-bold" href="#casereport_04">未進場</a>
				<a class="btn btn-warning btn-sm mx-1 fw-bold" href="#casereport_05">已進場</a>
			</div>
		</div>

		<!-- 右上角固定的列印與關閉 -->
		<div class="btn-group print" role="group" style="position:fixed; top: 10px; right:10px; z-index: 9999;">
			<button id="print" class="btn btn-info btn-lg" type="button" onclick="window.print();">
				<i class="bi bi-printer"></i>&nbsp;列印
			</button>
			<button id="close" class="btn btn-danger btn-lg" type="button" onclick="window.close();">
				<i class="bi bi-power"></i>&nbsp;關閉
			</button>
		</div>

		<!-- 第一個區塊 -->
		<div class="mycell weight pt-5 pb-4 text-center" id="casereport_01">
			<h3>初評中明細</h3>
		</div>
	</div>
</div>

<div style="margin-bottom: 150px;">
	$casereport_list_1
</div>

<div class="mytable w-100 bg-white p-3 mt-3">
	<div class="myrow">

		<div class="mycell weight pt-5 pb-4 text-center" id="casereport_02">
			<h3>未訂約明細</h3>
			<div class="size12 weight text-center mt-3"><span class="red">紅字</span>-未滿14天、<span style="background-color:#FFE599;color:red;">黃底</span>-已滿14天、<span style="background-color:#A4C2F4;color:red;">藍底</span>-已滿30天</div>
		</div>
	</div>
</div>
<div style="margin-bottom: 150px;">
	$casereport_list_2
</div>

<div class="mytable w-100 bg-white p-3 mt-3">
	<div class="myrow">

		<div class="mycell weight pt-5 pb-4 text-center" id="casereport_03">
			<h3>已回簽未用印明細</h3>
		</div>
	</div>
</div>
<div style="margin-bottom: 150px;">
	$casereport_list_3
</div>

<div class="mytable w-100 bg-white p-3 mt-3">
	<div class="myrow">

		<div class="mycell weight pt-5 pb-4 text-center" id="casereport_04">
			<h3>已訂約「未進場」明細</h3>
		</div>
		
	</div>
</div>
<div style="margin-bottom: 150px;margin-right: 20px;">
	$casereport_list_4
</div>

<div class="mytable w-100 bg-white p-3 mt-3">
	<div class="myrow">

		<div class="mycell weight pt-5 pb-4 text-center" id="casereport_05">
			<h3>已訂約「已進場」明細</h3>
		</div>
		
	</div>
</div>
<div style="margin-bottom: 150px;">
	$casereport_list_5
</div>
EOT;

$show_center=<<<EOT
<style>

table.table-bordered {
	border:1px solid black;
}
table.table-bordered > thead > tr > th{
	border:1px solid black;
}
table.table-bordered > tbody > tr > th {
	border:1px solid black;
}
table.table-bordered > tbody > tr > td {
	border:1px solid black;
}

@media print {
	.print {
		display: none !important;
	}
}

</style>

$show_report

EOT;



?>
