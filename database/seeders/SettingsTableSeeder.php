<?php

namespace Database\Seeders;

use App\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        // Global settings (event_id = null) - used by the company home page, footer & contact
        $globalSettings = [
            [
                'key'   => 'company_title',
                'value' => '{"vi":"SMB+<br><span>Solution for Business Plus</span>","en":"SMB+<br><span>Solution for Business Plus</span>"}'
            ],
            [
                'key'   => 'company_subtitle',
                'value' => '{"vi":"Tư vấn & Phát triển phần mềm - Đối tác chuyển đổi số tin cậy","en":"Software Consulting & Development - Your trusted digital transformation partner"}'
            ],
            [
                'key'   => 'company_about',
                'value' => '{"vi":"SMB+ là doanh nghiệp hoạt động trong lĩnh vực tư vấn và phát triển phần mềm, tập trung cung cấp giải pháp chuyển đổi số cho doanh nghiệp thông qua các dịch vụ phát triển phần mềm theo yêu cầu, tư vấn công nghệ, kiểm thử phần mềm và triển khai các nền tảng SaaS. Công ty hướng đến việc giúp doanh nghiệp tối ưu quy trình vận hành, nâng cao năng suất và kiểm soát dữ liệu hiệu quả.","en":"SMB+ is a software consulting and development company focused on digital transformation for businesses through custom software development, technology consulting, software testing and SaaS platform implementation. We help businesses optimize operations, improve productivity and control data effectively."}'
            ],
            [
                'key'   => 'company_youtube_link',
                'value' => 'https://www.youtube.com/watch?v=jDDaplaOz7Q'
            ],
            [
                'key'   => 'contact_address',
                'value' => '{"vi":"105 – 107 Trần Văn Dư, Phường 13, Tân Bình, Hồ Chí Minh","en":"105 – 107 Tran Van Du, Ward 13, Tan Binh, Ho Chi Minh City"}'
            ],
            [
                'key'   => 'contact_phone',
                'value' => '028 7301 3388'
            ],
            [
                'key'   => 'contact_email',
                'value' => 'info@smbplus.vn'
            ],
            [
                'key'   => 'footer_description',
                'value' => '{"vi":"SMB+ cung cấp các giải pháp phần mềm quản lý toàn diện cho doanh nghiệp vừa và nhỏ.","en":"SMB+ delivers comprehensive business management software solutions for small and medium enterprises."}'
            ],
            [
                'key'   => 'footer_address',
                'value' => '{"vi":"105 – 107 Trần Văn Dư <br> Phường 13, Tân Bình, Hồ Chí Minh","en":"105 – 107 Tran Van Du <br> Ward 13, Tan Binh, Ho Chi Minh City"}'
            ],
            [
                'key'   => 'footer_twitter',
                'value' => '#'
            ],
            [
                'key'   => 'footer_facebook',
                'value' => '#'
            ],
            [
                'key'   => 'footer_instagram',
                'value' => '#'
            ],
            [
                'key'   => 'footer_googleplus',
                'value' => '#'
            ],
            [
                'key'   => 'footer_linkedin',
                'value' => '#'
            ],
        ];

        foreach ($globalSettings as $setting) {
            $setting['event_id'] = null;
            Setting::create($setting);
        }

        // Event-specific settings for the default event
        $eventSettings = [
            [
                'key'   => 'title',
                'value' => '{"vi":"Hội nghị<br><span>Marketing</span> thường niên","en":"The Annual<br><span>Marketing</span> Conference"}'
            ],
            [
                'key'   => 'subtitle',
                'value' => '{"vi":"10-12 tháng 12, Trung tâm Hội nghị Downtown, New York","en":"10-12 December, Downtown Conference Center, New York"}'
            ],
            [
                'key'   => 'youtube_link',
                'value' => 'https://www.youtube.com/watch?v=jDDaplaOz7Q'
            ],
            [
                'key'   => 'about_description',
                'value' => '{"vi":"Hội nghị kéo dài ba ngày, nơi các nhà lãnh đạo và chuyên gia marketing khám phá những xu hướng mới nhất về chiến lược thương hiệu, quảng cáo kỹ thuật số, content marketing và phân tích marketing thông qua các bài phát biểu chính, hội thảo thực hành và kết nối với các chuyên gia trong ngành.","en":"A three-day conference where marketing leaders and experts explore the latest trends in brand strategy, digital advertising, content marketing and marketing analytics through keynote speeches, hands-on workshops and networking with industry experts."}'
            ],
            [
                'key'   => 'about_where',
                'value' => '{"vi":"Trung tâm Hội nghị Downtown, New York","en":"Downtown Conference Center, New York"}'
            ],
            [
                'key'   => 'about_when',
                'value' => '{"vi":"Thứ Hai đến Thứ Tư<br>10-12 tháng 12","en":"Monday to Wednesday<br>December 10-12"}'
            ],
        ];

        foreach ($eventSettings as $setting) {
            $setting['event_id'] = 1;
            Setting::create($setting);
        }
    }
}
