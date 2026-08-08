<?php

namespace Database\Seeders;

use App\Post;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        $posts = [
            [
                'title'        => 'Chúng tôi đã triển khai hệ thống quản lý sự kiện cho khách hàng',
                'slug'         => 'he-thong-quan-ly-su-kien',
                'tag'          => 'Case Study',
                'excerpt'      => 'Một câu chuyện thành công về việc xây dựng nền tảng quản lý hội nghị cho khách hàng trong lĩnh vực tài chính.',
                'content'      => '<p>' . $faker->paragraphs(4, true) . '</p><p>' . $faker->paragraphs(3, true) . '</p>',
                'is_published' => 1,
            ],
            [
                'title'        => 'Thiết kế website giới thiệu thương hiệu cho chuỗi nhà hàng',
                'slug'         => 'website-gioi-thieu-thuong-hieu',
                'tag'          => 'Website',
                'excerpt'      => 'Giới thiệu quy trình tư vấn, thiết kế và bàn giao website cho chuỗi nhà hàng với hơn 20 chi nhánh.',
                'content'      => '<p>' . $faker->paragraphs(4, true) . '</p><p>' . $faker->paragraphs(3, true) . '</p>',
                'is_published' => 1,
            ],
            [
                'title'        => 'Ứng dụng đặt vé sự kiện trực tuyến',
                'slug'         => 'ung-dung-dat-ve-su-kien',
                'tag'          => 'Ứng dụng',
                'excerpt'      => 'Dự án xây dựng ứng dụng đặt vé, quản lý chỗ ngồi và thanh toán trực tuyến cho một đơn vị tổ chức sự kiện.',
                'content'      => '<p>' . $faker->paragraphs(4, true) . '</p><p>' . $faker->paragraphs(3, true) . '</p>',
                'is_published' => 1,
            ],
            [
                'title'        => 'Nâng cấp hạ tầng công nghệ cho doanh nghiệp sản xuất',
                'slug'         => 'nang-cap-ha-tang-cong-nghe',
                'tag'          => 'Hạ tầng',
                'excerpt'      => 'Tư vấn và triển khai giải pháp số hóa quy trình vận hành cho doanh nghiệp sản xuất.',
                'content'      => '<p>' . $faker->paragraphs(4, true) . '</p><p>' . $faker->paragraphs(3, true) . '</p>',
                'is_published' => 0,
            ],
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}
