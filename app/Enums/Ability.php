<?php

namespace App\Enums;

enum Ability: int
{
    /**
     * 首頁
     */
    case HOME = 1;

    /**
     * 作品一覽
     */
    case PRODUCT = 2;

    /**
     * 最新消息
     */
    case NEWS = 3;

    /**
     * 商品一覽 (全域)
     */
    case SHOPS = 4;

    /**
     * 瀏覽商品
     */
    case SHOP_GOODS = 5;

    /**
     * 購物車
     */
    case SHOP_CART = 6;

    /**
     * 結帳
     */
    case SHOP_CHECKOUT = 7;

    /**
     * 討論板 (全域)
     */
    case FORUM = 8;

    /**
     * 檢視討論板
     */
    case FORUM_VIEW_BOARD = 9;

    /**
     * 檢視文章 (含回應)
     */
    case FORUM_VIEW_POST = 10;

    /**
     * 建立文章 (含編輯自己的文章)
     */
    case FORUM_CREATE_POST = 11;

    /**
     * 建立回應 (含編輯自己的回應)
     */
    case FORUM_CREATE_REPLY = 12;

    /**
     * 後臺管理 (全域)
     */
    case BACKSTAGE = 13;

    /**
     * 輪播管理
     */
    case BACKSTAGE_CAROUSEL_MANAGE = 14;

    /**
     * 最新消息管理
     */
    case BACKSTAGE_NEWS_MANAGE = 15;

    /**
     * 作品管理
     */
    case BACKSTAGE_PRODUCT_MANAGE = 16;

    /**
     * 常見問題管理
     */
    case BACKSTAGE_FAQ_MANAGE = 17;

    /**
     * 討論板管理
     */
    case BACKSTAGE_BOARD_MANAGE = 18;

    /**
     * 會員權限管理
     */
    case BACKSTAGE_USER_ROLE_MANAGE = 19;

    /**
     * 會員管理
     */
    case BACKSTAGE_USER_MANAGE = 20;

    /**
     * 商品管理
     */
    case BACKSTAGE_GOOD_MANAGE = 21;

    /**
     * 訂單管理
     */
    case BACKSTAGE_ORDER_MANAGE = 22;

    /**
     * 主要系統管理
     */
    case BACKSTAGE_MAIN_SYSTEM_SETTING_MANAGE = 23;

    /**
     * 資料庫管理
     */
    case BACKSTAGE_DATABASE_MANAGE = 24;
}
