<?php
/**
 *
 * Copyright(c) 2000-2007 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 *
 * モバイルサイト/会員情報変更完了
 */

// {{{ requires
require_once("../require.php");
require_once(CLASS_EX_PATH . "page_extends/mypage/LC_Page_Mypage_ChangeComplete_Ex.php");

// }}}
// {{{ generate page

$objPage = new LC_Page_Mypage_ChangeComplete_Ex();
$objPage->mobileInit();
$objPage->mobileProcess();
$objPage->process();
?>
