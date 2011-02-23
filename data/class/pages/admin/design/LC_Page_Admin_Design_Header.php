<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2010 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

// {{{ requires
require_once(CLASS_EX_REALDIR . "page_extends/admin/LC_Page_Admin_Ex.php");

/**
 * ヘッダ, フッタ編集 のページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id$
 */
class LC_Page_Admin_Design_Header extends LC_Page_Admin_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = 'design/header.tpl';
        $this->tpl_subnavi  = 'design/subnavi.tpl';
        $this->header_row = 13;
        $this->footer_row = 13;
        $this->tpl_subno = "header";
        $this->tpl_mainno = "design";
        $this->tpl_subtitle = 'ヘッダー/フッター設定';
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * FIXME テンプレートの取得方法を要修正
     *
     * @return void
     */
    function action() {
        // 端末種別IDを取得
        if (isset($_REQUEST['device_type_id'])
            && is_numeric($_REQUEST['device_type_id'])) {
            $device_type_id = $_REQUEST['device_type_id'];
        } else {
            $device_type_id = DEVICE_TYPE_PC;
        }
        $this->device_type_id = $device_type_id;

        // テンプレートのパス
        $template_path = $this->lfGetTemplatePath($device_type_id);
        $preview_template_path = $this->lfGetPreviewTemplatePath();

        // データ更新処理
        if (isset($_POST['division']) && $_POST['division'] != '') {
            $division = $_POST['division'];
            $content = $_POST[$division]; // TODO no checked?
            // プレビュー用のテンプレートに書き込む
            $preview_template = $preview_template_path.'/'.$division.'.tpl';
            $this->lfUpdateTemplate($preview_template, $content);

            switch ($this->getMode()) {
            case 'regist':
                // 正規のテンプレートに書き込む
                $template = $template_path . '/' . $division . '.tpl';
                $this->lfUpdateTemplate($template, $content);
                $this->tpl_onload="alert('登録が完了しました。');";
                break;
            case 'preview':
                if ($division == "header") $this->header_prev = "on";
                if ($division == "footer") $this->footer_prev = "on";
                $this->header_row = isset($_POST['header_row']) ? $_POST['header_row'] : $this->header_row;
                $this->footer_row = isset($_POST['footer_row']) ? $_POST['footer_row'] : $this->footer_row;
                break;
            default:
                // なにもしない
                break;
            }
        }else{
            // postでデータが渡されなければ新規読み込みと判断をし、
            // プレビュー用テンプレートに正規のテンプレートをロードする
            $templates = array(
                'header.tpl',
                'footer.tpl'
            );
            $this->lfLoadPreviewTemplates($preview_template_path, $template_path, $templates);
        }

        // テキストエリアに表示
        $this->header_data = file_get_contents($preview_template_path . '/header.tpl');
        $this->footer_data = file_get_contents($preview_template_path . '/footer.tpl');

        // ブラウザタイプ
        $this->browser_type = isset($_POST['browser_type']) ? $_POST['browser_type'] : "";
    }

    protected function lfLoadPreviewTemplates($preview_template_path, $template_path, $templates) {
        if (!is_dir($preview_template_path)) {
            mkdir($preview_template_path);
        }
        foreach($templates as $template) {
            $source = $template_path . '/' . $template;
            $dest = $preview_template_path . '/' . $template;
            copy($source, $dest);
        }
    }

    protected function lfUpdateTemplate($template, $content) {
        $fp = fopen($template,"w");
        fwrite($fp, $content);
        fclose($fp);
    }

    protected function lfGetTemplatePath($device_type_id) {
        $objLayout = new SC_Helper_PageLayout_Ex();
        return $objLayout->getTemplatePath($device_type_id);
    }

    protected function lfGetPreviewTemplatePath() {
        return USER_INC_REALDIR . 'preview';
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }
}
