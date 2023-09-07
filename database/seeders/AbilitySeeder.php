<?php

namespace Database\Seeders;

use App\Models\Ability;
use Illuminate\Database\Seeder;

class AbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'home',
                'description' => '首頁',
            ],
            [
                'id' => 2,
                'name' => 'products',
                'description' => '作品一覽',
            ],
            [
                'id' => 3,
                'name' => 'news',
                'description' => '最新消息',
            ],
            [
                'id' => 4,
                'name' => 'shops',
                'description' => '商品一覽 (全域)',
            ],
            [
                'id' => 5,
                'name' => 'goods',
                'description' => '瀏覽商品',
                'parent_ability' => 4,
            ],
            [
                'id' => 6,
                'name' => 'cart',
                'description' => '購物車',
                'parent_ability' => 4,
            ],
            [
                'id' => 7,
                'name' => 'checkout',
                'description' => '結帳',
                'parent_ability' => 4,
            ],
            [
                'id' => 8,
                'name' => 'forum',
                'description' => '討論板 (全域)',
            ],
            [
                'id' => 9,
                'name' => 'view_board',
                'description' => '檢視討論板',
                'parent_ability' => 8,
            ],
            [
                'id' => 10,
                'name' => 'view_post',
                'description' => '檢視文章 (含回應)',
                'parent_ability' => 8,
            ],
            [
                'id' => 11,
                'name' => 'create_post',
                'description' => '建立文章 (含編輯自己的文章)',
                'parent_ability' => 8,
            ],
            [
                'id' => 12,
                'name' => 'create_reply',
                'description' => '建立回應 (含編輯自己的回應)',
                'parent_ability' => 8,
            ],
            [
                'id' => 13,
                'name' => 'backstage',
                'description' => '後臺管理 (全域)',
            ],
            [
                'id' => 14,
                'name' => 'carousel_manage',
                'description' => '輪播管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 15,
                'name' => 'news_manage',
                'description' => '最新消息管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 16,
                'name' => 'product_manage',
                'description' => '作品管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 17,
                'name' => 'faq_manage',
                'description' => '常見問題管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 18,
                'name' => 'board_manage',
                'description' => '討論板管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 19,
                'name' => 'user_role_manage',
                'description' => '會員權限管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 20,
                'name' => 'user_manage',
                'description' => '會員管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 21,
                'name' => 'good_manage',
                'description' => '商品管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 22,
                'name' => 'order_manage',
                'description' => '訂單管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 23,
                'name' => 'main_system_setting_manage',
                'description' => '主要系統管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 24,
                'name' => 'database_manage',
                'description' => '資料庫管理',
                'parent_ability' => 13,
            ],
        ];

        foreach ($data as $ability) {
            Ability::create($ability);
        }
    }
}
