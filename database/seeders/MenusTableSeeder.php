<?php

namespace Database\Seeders;

use App\Menu;
use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    public function run()
    {
        $items = [
            ['label' => 'Trang chủ', 'url' => '/#intro', 'parent_id' => null, 'sort_order' => 1],
            ['label' => 'Dịch vụ', 'url' => '/#services', 'parent_id' => null, 'sort_order' => 3],
            ['label' => 'Freshwork', 'url' => '/freshwork', 'parent_id' => null, 'sort_order' => 1],
            ['label' => 'Sự kiện', 'url' => '/event', 'parent_id' => null, 'sort_order' => 4],
            ['label' => 'Dự án', 'url' => '/du-an', 'parent_id' => null, 'sort_order' => 5],
            ['label' => 'Tin tức', 'url' => '/posts', 'parent_id' => null, 'sort_order' => 6],
            ['label' => 'Chia sẻ tài liệu', 'url' => '/chia-se', 'parent_id' => null, 'sort_order' => 7],
            ['label' => 'Liên hệ', 'url' => '/#contact', 'parent_id' => null, 'sort_order' => 8],
        ];

        foreach ($items as $item) {
            Menu::create($item);
        }

        $services = Menu::where('label', 'Dịch vụ')->first();
        $freshwork = Menu::where('label', 'Freshwork')->first();

        if ($services && $freshwork) {
            $freshwork->parent_id = $services->id;
            $freshwork->save();
        }
    }
}
