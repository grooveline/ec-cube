<?php
/*
 * Copyright(c) 2000-2007 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 */

// {{{ requires
require_once("../../require.php");
require_once(CLASS_EX_PATH . "page_extends/admin/order/LC_Page_Admin_Order_MailView_Ex.php");

// }}}
// {{{ generate page

$objPage = new LC_Page_Admin_Order_MailView_Ex();
register_shutdown_function(array($objPage, "destroy"));
$objPage->init();
$objPage->process();
?>
