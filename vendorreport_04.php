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

$region = $_GET['region'];
$builder_id = $_GET['builder_id'];
$contractor_id = $_GET['contractor_id'];
$ContractingModel = $_GET['ContractingModel'];
$Handler = $_GET['Handler'];



$mDB = "";
$mDB = new MywebDB();

// 取得月份

$annual_mooth = isset($_GET['annual_mooth']) ? $_GET['annual_mooth'] : '';

// 建立起訖日期
$start = $annual_mooth . "-01";
$end = date("Y-m-t", strtotime($start)); // 當月最後一天

//載入區域
$Qry = "SELECT region
FROM CaseManagement
GROUP BY region
ORDER BY FIELD(region, '北部', '中部', '南部');
			";

$mDB->query($Qry);


$get_region_dropdown = isset($_GET['region']) ? $_GET['region'] : '';

$region_dropdown = "<select class=\"inline form-select\" name=\"region\" id=\"region\" style=\"width:auto;\">";
$region_dropdown .= "<option></option>";

if ($mDB->rowCount() > 0) {
    while ($row = $mDB->fetchRow(2)) {
        $select_region = $row['region'];
        $selected = ($get_region_dropdown == $select_region) ? "selected" : "";

        $region_dropdown .= "<option value='$select_region' $selected>$select_region</option>";
    }
}

$region_dropdown .= "</select>";

//載入上包-建商
$Qry = "SELECT builder_id,builder_name FROM builder ";

$mDB->query($Qry);


$get_builder_id_dropdown = isset($_GET['builder_id']) ? $_GET['builder_id'] : '';

$builder_id_dropdown = "<select class=\"inline form-select\" name=\"builder_id\" id=\"builder_id\" style=\"width:auto;\">";
$builder_id_dropdown .= "<option></option>";

if ($mDB->rowCount() > 0) {
    while ($row = $mDB->fetchRow(2)) {
        $select_builder_id = $row['builder_id'];
        $select_builder_name = $row['builder_name'];
        $selected = ($get_builder_id_dropdown == $select_builder_id) ? "selected" : "";

        $builder_id_dropdown .= "<option value='$select_builder_id' $selected>$select_builder_name</option>";
    }
}

$builder_id_dropdown .= "</select>";

//載入上包-營造廠
$Qry = "SELECT contractor_id,contractor_name FROM contractor ";

$mDB->query($Qry);


$get_contractor_id_dropdown = isset($_GET['contractor_id']) ? $_GET['contractor_id'] : '';

$contractor_id_dropdown = "<select class=\"inline form-select\" name=\"contractor_id\" id=\"contractor_id\" style=\"width:auto;\">";
$contractor_id_dropdown .= "<option></option>";

if ($mDB->rowCount() > 0) {
    while ($row = $mDB->fetchRow(2)) {
        $select_contractor_id = $row['contractor_id'];
        $select_contractor_name = $row['contractor_name'];
        $selected = ($get_contractor_id_dropdown == $select_contractor_id) ? "selected" : "";

        $contractor_id_dropdown .= "<option value='$select_contractor_id' $selected>$select_contractor_name</option>";
    }
}

$contractor_id_dropdown .= "</select>";

//承攬模式
$Qry = "SELECT caption AS ContractingModel FROM items 
WHERE pro_id = 'ContractingModel'";

$mDB->query($Qry);


$get_ContractingModel_dropdown = isset($_GET['ContractingModel']) ? $_GET['ContractingModel'] : '';

$ContractingModel_dropdown = "<select class=\"inline form-select\" name=\"ContractingModel\" id=\"ContractingModel\" style=\"width:auto;\">";
$ContractingModel_dropdown .= "<option></option>";

if ($mDB->rowCount() > 0) {
    while ($row = $mDB->fetchRow(2)) {
        $select_ContractingModel = $row['ContractingModel'];
        $selected = ($get_ContractingModel_dropdown == $select_ContractingModel) ? "selected" : "";

        $ContractingModel_dropdown .= "<option value='$select_ContractingModel' $selected>$select_ContractingModel</option>";
    }
}

$ContractingModel_dropdown .= "</select>";


//經辦人
$Qry = "SELECT a.Handler,e.employee_name
FROM CaseManagement a
LEFT JOIN employee e ON e.employee_id = a.Handler
WHERE a.Handler IS NOT NULL AND a.Handler != ''
GROUP BY a.Handler";

$mDB->query($Qry);


$get_Handler_dropdown = isset($_GET['Handler']) ? $_GET['Handler'] : '';

$Handler_dropdown = "<select class=\"inline form-select\" name=\"Handler\" id=\"Handler\" style=\"width:auto;\">";
$Handler_dropdown .= "<option></option>";

if ($mDB->rowCount() > 0) {
    while ($row = $mDB->fetchRow(2)) {
        $select_Handler = $row['Handler'];
        $select_employee_name = $row['employee_name'];
        $selected = ($get_Handler_dropdown == $select_Handler) ? "selected" : "";

        $Handler_dropdown .= "<option value='$select_Handler' $selected>$select_employee_name</option>";
    }
}

$Handler_dropdown .= "</select>";


$Qry="SELECT a.*,b.engineering_name,c.builder_name,d.contractor_name,e.employee_name,f.company_name,f.short_name FROM CaseManagement a
LEFT JOIN construction b ON b.construction_id = a.construction_id
LEFT JOIN builder c ON c.builder_id = a.builder_id
LEFT JOIN contractor d ON d.contractor_id = a.contractor_id
LEFT JOIN employee e ON e.employee_id = a.Handler
LEFT JOIN company f ON f.company_id = a.company_id
WHERE a.status1 = '已簽約' AND a.status2 = '未進場'
AND a.status1 <> '已完工'";

if (!empty($get_region_dropdown)) {
        $Qry .= " AND a.region = '$get_region_dropdown'";
    }
if (!empty($get_builder_id_dropdown)) {
        $Qry .= " AND a.builder_id = '$get_builder_id_dropdown'";
    }
if (!empty($get_contractor_id_dropdown)) {
        $Qry .= " AND a.contractor_id = '$get_contractor_id_dropdown'";
    }
if (!empty($get_ContractingModel_dropdown)) {
        $Qry .= " AND a.ContractingModel = '$get_ContractingModel_dropdown'";
    }
if (!empty($get_Handler_dropdown)) {
	$Qry .= " AND a.Handler = '$get_Handler_dropdown'";
	}
if (!empty($annual_mooth)) {	
	$Qry .= " AND a.estimated_arrival_date BETWEEN '$start' AND '$end'";
	}
$Qry .="ORDER BY a.auto_seq";

$mDB->query($Qry);
$casereport_list = "";

$casereport_list.=<<<EOT
<div class="w-100 m-auto p-3" style="min-height:300px;margin-bottom: 100px;">
	<div class="w-100">
		<div class="w-100" style="min-width:1760px;">
EOT;


$total = $mDB->rowCount();
if ($total > 0) {

$casereport_list.=<<<EOT
	<table class="table table-bordered border-dark w-100">
		<thead class="table-light border-dark">
			<tr style="border-bottom: 1px solid #000;">
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">狀態(1)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">狀態(2)</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">區域</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">案件編號</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">工程名稱</th>
				<!--<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">上包-建商名稱</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">上包-營造廠名稱</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">連絡人</th>-->
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">案場位置</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">承攬模式</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">所屬公司</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">經辦人員</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">預計進場日期</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">預計完工日期</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">合約承攬建物棟數</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">合約號碼 <br>(ERP專案代號)</th>
				<!--<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">合約總價(含稅)</th>-->
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">上包合約<br>簽訂時間</th>
				<!--<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">第一期預收款<br>請款方式</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">第一期預收<br>預估日期</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">第一期<br>請款日期</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">第二期預收款<br>請款方式</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">第二期預收<br>預估日期</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">第二期<br>請款日期</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">第三期預收款<br>請款方式</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">第三期預收<br>預估日期</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">第三期<br>請款日期</th>-->
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">志特編號</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">志特合約<br>簽訂日期</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">鋁模材料<br>利舊/新購</th>
				<th class="text-center text-nowrap vmiddle" style="width:5%;padding: 10px;background-color: #D4F8D4;">材料進口日期</th>
				
			</tr>
		</thead>
		<tbody class="table-group-divider">
EOT;
	$SUM_total_contract_amt = 0; // 初始化為數字
    while ($row=$mDB->fetchRow(2)) {
		$auto_seq = $row['auto_seq'];

		$status1 = $row['status1'];
		$status2 = $row['status2'];
		$region = $row['region'];
		$case_id = $row['case_id'];
		$construction_id = $row['construction_id'];
		$engineering_name = $row['engineering_name'];
		$builder_id = $row['builder_id'];
		$builder_name = $row['builder_name'];
		$contractor_id = $row['contractor_id'];
		$contractor_name = $row['contractor_name'];
		$contact = $row['contact'];
		//$site_location = $row['site_location'];
		$county = $row['county'];
		$town = $row['town'];
		$zipcode = $row['zipcode'];
		$site_location = $county.$town;
		$ContractingModel = $row['ContractingModel'];
		$Handler = $row['Handler'];
		$employee_name = $row['employee_name'];
		
		//預計進場日期
		$estimated_arrival_date = $row['estimated_arrival_date'];
		if ($estimated_arrival_date == "0000-00-00")
			$estimated_arrival_date = "";
		//預計完工日期
		$completion_date = $row['completion_date'];
		if ($completion_date == "0000-00-00")
			$completion_date = "";

		//合約承攬建物棟數
		$buildings_contract = $row['buildings_contract'];
		//合約號碼 (ERP專案代號)
		$ERP_no = $row['ERP_no'];
		$fmt_engineering_qty = number_format($engineering_qty);
		//上包合約簽訂時間
		$contract_date = $row['contract_date'];
		if ($contract_date == "0000-00-00")
			$contract_date = "";
		//合約總價(含稅)
		$total_contract_amt = $row['total_contract_amt'];
		$fmt_total_contract_amt = number_format($total_contract_amt);
		
		//第一期預收款請款方式
		$advance_payment1 = $row['advance_payment1'];

		// 第一期預付預估日期
		$estimated_payment_date1 = $row['estimated_payment_date1'];
		if ($estimated_payment_date1 == "0000-00-00")
			$estimated_payment_date1 = "";

		//第一期請款日期
		$request_date1 = $row['request_date1'];
		if ($request_date1 == "0000-00-00")
			$request_date1 = "";
		//第二期預收款請款方式
		$advance_payment2 = $row['advance_payment2'];

		// 第二期預付預估日期
		$estimated_payment_date2 = $row['estimated_payment_date2'];
		if ($estimated_payment_date2 == "0000-00-00")
			$estimated_payment_date2 = "";

		//第二期請款日期
		$request_date2 = $row['request_date2'];
		if ($request_date2 == "0000-00-00")
			$request_date2 = "";


		//第三期預收款請款方式
		$advance_payment3 = $row['advance_payment3'];

		// 第三期預付預估日期
		$estimated_payment_date3 = $row['estimated_payment_date3'];
		if ($estimated_payment_date3 == "0000-00-00")
			$estimated_payment_date3 = "";


		//第三期請款日期
		$request_date3 = $row['request_date3'];
		if ($request_date3 == "0000-00-00")
			$request_date3 = "";
		//志特編號
		$geto_no = $row['geto_no'];
		//志特合約簽訂日期
		$geto_contract_date = $row['geto_contract_date'];
		if ($geto_contract_date == "0000-00-00")
			$geto_contract_date = "";
		//鋁模材料
		$geto_formwork = $row['geto_formwork'];
		//材料進口日期
		$material_import_date = $row['material_import_date'];
		if ($material_import_date == "0000-00-00")
			$material_import_date = "";

		//$makeby = $row['makeby'];
		//$content = nl2br_skip_html(htmlspecialchars_decode($row['content']));

		$company_id = $row['company_id'];
		$company_name = $row['short_name'];
		$SUM_total_contract_amt += $total_contract_amt; // 加總用的是原始數字
		if (empty($company_name))
			$company_name = $row['company_name'];
		


$casereport_list.=<<<EOT
			<tr>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$status1</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$status2</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$region</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$case_id</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$construction_id</th>
				<!--<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$builder_name<br>$builder_id</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$contractor_name<br>$contractor_id </th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$contact</th>-->
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$site_location</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$ContractingModel</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;">$company_name<br>$company_id</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$employee_name</th> 
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$estimated_arrival_date</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$completion_date</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$buildings_contract</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$ERP_no</th>
				<!--<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$fmt_total_contract_amt</th>-->
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$contract_date</th>
				<!--<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$advance_payment1</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$estimated_payment_date1</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$request_date1</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$advance_payment2</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$estimated_payment_date2</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$request_date2</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$advance_payment3</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$estimated_payment_date3</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$request_date3</th>-->
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$geto_no</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$geto_contract_date</th>
				<th class="text-center" style="width:5%;padding: 10px;$bgcolor">$geto_formwork</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px;$bgcolor">$material_import_date</th>
			</tr>

			

EOT;

	}
	$fmt_SUM_total_contract_amt = number_format($SUM_total_contract_amt); // 最後再格式化一次
	$casereport_list .=<<<EOT
			</tbody>
			<!--<tfoot>
			<tr>
				<th colspan="2" class="text-center text-nowrap" style="width:5%;padding: 10px; background-color: #FFF2CC; font-weight: bold; font-size: 16px;">合計:</th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				
				<th class="text-center" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px; background-color: #FFF2CC;"><br></th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center" style="width:5%;padding: 10px; background-color: #FFF2CC; font-weight: bold; font-size: 16px;"></th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				
				<th class="text-center" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				
				<th class="text-center" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
				<th class="text-center text-nowrap" style="width:5%;padding: 10px; background-color: #FFF2CC;"></th>
			</tr>
			</tfoot>-->
	
EOT;
	$fmt_total = number_format($total);

$casereport_list.=<<<EOT
		
	</table>
	<div class="size14 weight">案件合計：{$fmt_total}件</div>
EOT;


} else {

$casereport_list.=<<<EOT
	<div class="size16 weight p-5 text-center">無任何符合查詢的資料</div>
EOT;

}

$casereport_list.=<<<EOT
		</div>
	</div>
</div>
EOT;


$mDB->remove();


$show_report=<<<EOT
<div class="mytable w-100 bg-white p-3 mt-3">
	<div class="myrow">
		<div class="mycell" style="width:20%;">
		</div>
		<div class="mycell weight pt-5 pb-4 text-center">
			<h3>已訂約「未進場」明細(廠商)</h3>
		</div>
		<div class="mycell text-end p-2 vbottom" style="width:20%;">
			<div class="btn-group print"  role="group" style="position:fixed;top: 10px; right:10px;z-index: 9999;">
				<button id="close" class="btn btn-info btn-lg" type="button" onclick="window.print();"><i class="bi bi-printer"></i>&nbsp;列印</button>
				<button id="close" class="btn btn-danger btn-lg" type="button" onclick="window.close();"><i class="bi bi-power"></i>&nbsp;關閉</button>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid p-3 text-center">
	<div class="row justify-content-center g-2">

		<div class="col-auto">
			<div class="form-label fw-bold">區域:</div>
			<div>$region_dropdown</div>
		</div>

		<!--<div class="col-auto">
			<div class="form-label fw-bold">上包-建商名稱：</div>
			<div>$builder_id_dropdown</div>
		</div>

		<div class="col-auto">
			<div class="form-label fw-bold">上包-營造廠名稱：</div>
			<div>$contractor_id_dropdown</div>
		</div>-->

		<div class="col-auto">
			<div class="form-label fw-bold">承攬模式：</div>
			<div>$ContractingModel_dropdown</div>
		</div>

		<div class="col-auto">
			<div class="form-label fw-bold">經辦人員：</div>
			<div>$Handler_dropdown</div>
		</div>

		<div class="col-auto">
			<div class="form-label fw-bold">預計進場月份：</div>
			<div class="input-group" id="annualyear" style="max-width: 180px;">
				<input type="text" class="form-control" id="annual_mooth" name="annual annual_mooth" placeholder="請輸入年份" value="$annual_mooth">
				<button class="btn btn-outline-secondary" type="button" data-target="#annualyear" data-toggle="datetimepicker">
					<i class="bi bi-calendar"></i>
				</button>
			</div>
		</div>

		<div class="col-auto align-self-end">
			<button type="button" class="btn btn-success mt-2" onclick="caseselect();">
				<i class="fas fa-check"></i>&nbsp;查詢
			</button>
		</div>
	</div>

	<!-- DateTime Picker Script -->
	<script type="text/javascript">
		$(function () {
			$('#annualyear').datetimepicker({
				locale: 'zh-tw',
				format: "YYYY-MM",
				allowInputToggle: true
			});
		});
	</script>

	<style>
		.bootstrap-datetimepicker-widget {
			z-index: 1050 !important;
			position: absolute !important;
		}
	</style>
</div>
<div style="margin-bottom: 150px;margin-right: 20px;">
	$casereport_list
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

<script>

function caseselect() {
	var region = $('#region').val();
	
	var ContractingModel = $('#ContractingModel').val();
	var Handler = $('#Handler').val();
	var annual_mooth = $('#annual_mooth').val();

	const newUrl = '/index.php?ch=$ch&fm=$fm'
					  + '&region=' + region
					 
					  + '&ContractingModel=' + ContractingModel
					  + '&Handler=' + Handler
					  + '&annual_mooth=' + annual_mooth;

	// 導向查詢（保留參數查資料）
	window.location.href = newUrl;

	// 接著在載入後使用 JS 清掉 input 顯示
	// 加在頁面載入後：
	// $('#annual_mooth').val('');
}

$(function() {
    // 頁面載入完就清掉
    $('#annual_mooth').val('');
});
	</script>
EOT;



?>