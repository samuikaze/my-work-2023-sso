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
                'name' => 'Home',
                'description' => '首頁',
            ],
            [
                'id' => 2,
                'name' => 'Products',
                'description' => '作品一覽',
            ],
            [
                'id' => 3,
                'name' => 'News',
                'description' => '最新消息',
            ],
            [
                'id' => 4,
                'name' => 'Shops',
                'description' => '商品一覽 (全域)',
            ],
            [
                'id' => 5,
                'name' => 'ShopGoods',
                'description' => '瀏覽商品',
                'parent_ability' => 4,
            ],
            [
                'id' => 6,
                'name' => 'ShopCart',
                'description' => '購物車',
                'parent_ability' => 4,
            ],
            [
                'id' => 7,
                'name' => 'ShopCheckout',
                'description' => '結帳',
                'parent_ability' => 4,
            ],
            [
                'id' => 8,
                'name' => 'Forum',
                'description' => '討論板 (全域)',
            ],
            [
                'id' => 9,
                'name' => 'ForumViewBoard',
                'description' => '檢視討論板',
                'parent_ability' => 8,
            ],
            [
                'id' => 10,
                'name' => 'ForumViewPost',
                'description' => '檢視文章 (含回應)',
                'parent_ability' => 8,
            ],
            [
                'id' => 11,
                'name' => 'ForumCreatePost',
                'description' => '建立文章 (含編輯自己的文章)',
                'parent_ability' => 8,
            ],
            [
                'id' => 12,
                'name' => 'ForumCreateReply',
                'description' => '建立回應 (含編輯自己的回應)',
                'parent_ability' => 8,
            ],
            [
                'id' => 13,
                'name' => 'Backstage',
                'description' => '後臺管理 (全域)',
            ],
            [
                'id' => 14,
                'name' => 'BackstageCarouselManage',
                'description' => '輪播管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 15,
                'name' => 'BackstageNewsManage',
                'description' => '最新消息管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 16,
                'name' => 'BackstageProductManage',
                'description' => '作品管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 17,
                'name' => 'BackstageFaqManage',
                'description' => '常見問題管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 18,
                'name' => 'BackstageBoardManage',
                'description' => '討論板管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 19,
                'name' => 'BackstageUserRoleManage',
                'description' => '會員權限管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 20,
                'name' => 'BackstageUserManage',
                'description' => '會員管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 21,
                'name' => 'BackstageGoodManage',
                'description' => '商品管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 22,
                'name' => 'BackstageOrderManage',
                'description' => '訂單管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 23,
                'name' => 'BackstageNainSystemSettingManage',
                'description' => '主要系統管理',
                'parent_ability' => 13,
            ],
            [
                'id' => 24,
                'name' => 'BackstageDatabaseManage',
                'description' => '資料庫管理',
                'parent_ability' => 13,
            ],
        ];

        foreach ($data as $ability) {
            Ability::create($ability);
        }
    }
}
