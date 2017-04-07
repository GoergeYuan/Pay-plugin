<?php
/**
 * FashionpayPayment
 *
 * @package Fashionpay MCP Plugin
 * @version $id$
 * @copyright Fashionpay Corporation
 * @author Nobuhiko Kimoto <info@nob-log.info>
 * @license GNU General Public License version 2 or later WITHOUT ANY WARRANTY
 */
class plugin_info {
    /** プラグインコード(必須)：プラグインを識別する為キーで、他のプラグインと重複しない一意な値である必要がありま. */
    static $PLUGIN_CODE       = "FashionpayPayment";
    /** プラグイン名(必須)：EC-CUBE上で表示されるプラグイン名. */
    static $PLUGIN_NAME       = "Fashionpay Payment Plug-in";
    /** プラグインバージョン(必須)：プラグインのバージョン. */
    static $PLUGIN_VERSION    = "2.0.4";
    /** 対応バージョン(必須)：対応するEC-CUBEバージョン. */
    static $COMPLIANT_VERSION = "2.12.6";
    /** 作者(必須)：プラグイン作者. */
    static $AUTHOR            = "Fashionpay";
    /** 説明(必須)：プラグインの説明. */
    static $DESCRIPTION       = "Fashionpay Payment Plug-in";
    /** プラグインURL：プラグイン毎に設定出来るURL（説明ページなど） */
    static $PLUGIN_SITE_URL   = "";
    /** プラグイン作者URL：プラグイン毎に設定出来るURL（説明ページなど） */
    static $AUTHOR_SITE_URL   = "";
    /** クラス名(必須)：プラグインのクラス（拡張子は含まない） */
    static $CLASS_NAME       = "FashionpayPayment";
    /** フックポイント：フックポイントとコールバック関数を定義します */
    /** ライセンス */
    static $LICENSE        = "LGPL";
}
