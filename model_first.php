<?php

session_start();

$memberID = $_SESSION['memberID'];
$powerkey = $_SESSION['powerkey'];


//載入公用函數
@include_once '/website/include/pub_function.php';

@include_once("/website/class/".$site_db."_info_class.php");


$m_location		= "/website/smarty/templates/".$site_db."/".$templates;
$m_pub_modal	= "/website/smarty/templates/".$site_db."/pub_modal";

$sid = "";
if (isset($_GET['sid']))
	$sid = $_GET['sid'];


//程式分類
$ch = empty($_GET['ch']) ? 'default' : $_GET['ch'];
switch($ch) {
	case 'vendorreport_01':
		$sid = "view01";
		$modal = $m_location."/sub_modal/project/func01/vendorreport_ms/vendorreport_01.php";
		include $modal;
		$smarty->assign('show_center',$show_center);
		break;
	case 'vendorreport_02':
		$sid = "view01";
		$modal = $m_location."/sub_modal/project/func01/vendorreport_ms/vendorreport_02.php";
		include $modal;
		$smarty->assign('show_center',$show_center);
		break;
	case 'vendorreport_03':
		$sid = "view01";
		$modal = $m_location."/sub_modal/project/func01/vendorreport_ms/vendorreport_03.php";
		include $modal;
		$smarty->assign('show_center',$show_center);
		break;
	case 'vendorreport_04':
		$sid = "view01";
		$modal = $m_location."/sub_modal/project/func01/vendorreport_ms/vendorreport_04.php";
		include $modal;
		$smarty->assign('show_center',$show_center);
		break;
	case 'vendorreport_05':
		$sid = "view01";
		$modal = $m_location."/sub_modal/project/func01/vendorreport_ms/vendorreport_05.php";
		include $modal;
		$smarty->assign('show_center',$show_center);
		break;
	case 'vendorreport_06':
		$sid = "view01";
		$modal = $m_location."/sub_modal/project/func01/vendorreport_ms/vendorreport_06.php";
		include $modal;
		$smarty->assign('show_center',$show_center);
		break;
	case 'vendorreport_07':
		$sid = "view01";
		$modal = $m_location."/sub_modal/project/func01/vendorreport_ms/vendorreport_07.php";
		include $modal;
		$smarty->assign('show_center',$show_center);
		break;
	case 'vendorreport_08':
		$sid = "view01";
		$modal = $m_location."/sub_modal/project/func01/vendorreport_ms/vendorreport_08.php";
		include $modal;
		$smarty->assign('show_center',$show_center);
		break;
	case 'vendorreport_09':
		$sid = "view01";
		$modal = $m_location."/sub_modal/project/func01/vendorreport_ms/vendorreport_09.php";
		include $modal;
		$smarty->assign('show_center',$show_center);
		break;
	default:
		if (empty($sid))
			$sid = "mbpjitem";
		$modal = $m_location."/sub_modal/project/func01/vendorreport_ms/vendorreport.php";
		include $modal;
		break;
};

?>