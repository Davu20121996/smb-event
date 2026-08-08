<?php

namespace Database\Seeders;

use App\CompanyProfileItem;
use App\Setting;
use Illuminate\Database\Seeder;

class CompanyProfileContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding company profile content...');

        $settings = [
            ['key' => 'company_title', 'value' => 'SMB+<br><span>Solution for Business Plus</span>'],
            ['key' => 'company_subtitle', 'value' => 'Tư vấn & Phát triển phần mềm - Đối tác chuyển đổi số tin cậy'],
            ['key' => 'company_about', 'value' => 'SMB+ là doanh nghiệp hoạt động trong lĩnh vực tư vấn và phát triển phần mềm, tập trung cung cấp giải pháp chuyển đổi số cho doanh nghiệp thông qua các dịch vụ phát triển phần mềm theo yêu cầu, tư vấn công nghệ, kiểm thử phần mềm và triển khai các nền tảng SaaS. Công ty hướng đến việc giúp doanh nghiệp tối ưu quy trình vận hành, nâng cao năng suất và kiểm soát dữ liệu hiệu quả.'],
            ['key' => 'company_slogan', 'value' => 'Digital Transformation Partner'],
            ['key' => 'company_letter', 'value' => '<p>Kính gửi Quý đối tác và khách hàng,</p><p>Trong bối cảnh chuyển đổi số đang diễn ra mạnh mẽ, SMB+ cam kết đồng hành cùng doanh nghiệp trong hành trình tối ưu vận hành và tăng trưởng bền vững. Hồ sơ năng lực này là bức tranh toàn cảnh về đội ngũ, dịch vụ, giải pháp và các dự án tiêu biểu của chúng tôi.</p><p>Chúng tôi tin rằng mỗi doanh nghiệp có một bài toán riêng, và giải pháp đúng đắn luôn bắt đầu từ sự thấu hiểu. SMB+ sẵn sàng lắng nghe và cùng Quý doanh nghiệp kiến tạo giá trị thực tiễn.</p><p>Trân trọng,<br>Ban Giám đốc SMB+</p>'],
            ['key' => 'company_vision', 'value' => 'Trở thành đối tác công nghệ tin cậy đồng hành cùng doanh nghiệp trong quá trình chuyển đổi số và phát triển bền vững.'],
            ['key' => 'company_mission', 'value' => 'Mang đến các giải pháp phần mềm phù hợp với từng doanh nghiệp, tối ưu chi phí đầu tư và tạo ra giá trị thực tiễn.'],
            ['key' => 'company_thanks', 'value' => 'Cảm ơn Quý khách hàng và đối tác đã tin tưởng đồng hành cùng SMB+. Chúng tôi luôn sẵn sàng hợp tác để kiến tạo giá trị bền vững cho doanh nghiệp của bạn.'],
            ['key' => 'contact_website', 'value' => 'https://smbplus.vn'],
        ];

        $sectionSettings = [
            'sec_letter'       => ['Thư ngỏ', 'Lời ngỏ từ Ban Giám đốc'],
            'sec_about'        => ['Giới thiệu SMB+', 'Đối tác công nghệ tin cậy của doanh nghiệp'],
            'sec_values'       => ['Giá trị cốt lõi', ''],
            'sec_why_us'       => ['Vì sao chọn SMB+', 'Lợi thế đồng hành cùng chúng tôi'],
            'sec_services'     => ['Dịch vụ', 'Giải pháp phần mềm toàn diện'],
            'sec_solutions'    => ['Giải pháp', 'Nền tảng triển khai cho doanh nghiệp'],
            'sec_process'      => ['Quy trình triển khai', '8 bước chuyên nghiệp, minh bạch'],
            'sec_roles'        => ['Năng lực đội ngũ', 'Chuyên gia giàu kinh nghiệm trong mọi vai trò'],
            'sec_models'       => ['Mô hình làm việc', 'Linh hoạt theo nhu cầu doanh nghiệp'],
            'sec_projects'     => ['Dự án tiêu biểu', 'Các dự án chúng tôi đã thực hiện cho khách hàng'],
            'sec_partners'     => ['Đối tác', 'Các đối tác công nghệ của SMB+'],
            'sec_clients'      => ['Khách hàng', 'Sự tin tưởng của khách hàng là thành công của chúng tôi'],
            'sec_commitments'  => ['Cam kết', 'Những cam kết của chúng tôi với khách hàng'],
            'sec_warranty'     => ['Quy trình bảo hành', 'Hỗ trợ nhanh chóng, đúng quy trình'],
            'sec_thanks'       => ['Lời cảm ơn', ''],
        ];

        foreach ($sectionSettings as $key => $value) {
            [$title, $subtitle] = $value;
            Setting::updateOrCreate(['key' => $key . '_title', 'event_id' => null], ['value' => $title]);
            Setting::updateOrCreate(['key' => $key . '_subtitle', 'event_id' => null], ['value' => $subtitle]);
        }

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key'], 'event_id' => null],
                ['value' => $setting['value']]
            );
        }

        $items = [
            'values' => [
                ['title' => 'Khách hàng là trung tâm'],
                ['title' => 'Đổi mới'],
                ['title' => 'Chất lượng'],
                ['title' => 'Cam kết'],
                ['title' => 'Đồng hành'],
            ],
            'why_us' => [
                ['title' => 'Đội ngũ sáng tạo và nhiệt huyết', 'description' => 'Đội ngũ chuyên gia giàu kinh nghiệm, luôn cập nhật công nghệ mới để mang lại giải pháp tối ưu.'],
                ['title' => 'Kinh nghiệm dự án phức tạp', 'description' => 'Nhiều năm triển khai các dự án lớn, phức tạp trên nhiều lĩnh vực khác nhau.'],
                ['title' => 'Chủ động hạ tầng', 'description' => 'Hạ tầng và máy chủ được quản lý tại các trung tâm dữ liệu đạt chuẩn, đảm bảo ổn định và an toàn.'],
                ['title' => 'Tối ưu chi phí', 'description' => 'Giải pháp giúp doanh nghiệp nâng cao năng suất và tối ưu chi phí đầu tư công nghệ.'],
            ],
            'services' => [
                ['title' => 'Phát triển phần mềm theo yêu cầu', 'category' => 'Software Engineering'],
                ['title' => 'Web Application', 'category' => 'Software Engineering'],
                ['title' => 'Enterprise Application', 'category' => 'Software Engineering'],
                ['title' => 'Database Application', 'category' => 'Software Engineering'],
                ['title' => 'API Integration', 'category' => 'Software Engineering'],
                ['title' => 'Distributed System', 'category' => 'Software Engineering'],
                ['title' => 'Product Development', 'category' => 'Software Engineering'],
                ['title' => 'ERP', 'category' => 'Technology Consulting'],
                ['title' => 'CRM', 'category' => 'Technology Consulting'],
                ['title' => 'HRM', 'category' => 'Technology Consulting'],
                ['title' => 'eCommerce', 'category' => 'Technology Consulting'],
                ['title' => 'Portal', 'category' => 'Technology Consulting'],
                ['title' => 'Inventory', 'category' => 'Technology Consulting'],
                ['title' => 'Payroll', 'category' => 'Technology Consulting'],
                ['title' => 'Digital Transformation', 'category' => 'Technology Consulting'],
                ['title' => 'Functional Testing', 'category' => 'Testing Services'],
                ['title' => 'Automation Testing', 'category' => 'Testing Services'],
                ['title' => 'Performance Testing', 'category' => 'Testing Services'],
                ['title' => 'Freshdesk', 'category' => 'SaaS Solutions'],
                ['title' => 'Freshsales', 'category' => 'SaaS Solutions'],
                ['title' => 'Freshservice', 'category' => 'SaaS Solutions'],
                ['title' => 'HRM+', 'category' => 'SaaS Solutions'],
            ],
            'solutions' => [
                ['title' => 'ERP'],
                ['title' => 'CRM'],
                ['title' => 'HRM'],
                ['title' => 'Quản lý bán hàng'],
                ['title' => 'Quản lý nhân sự'],
                ['title' => 'Quản trị doanh nghiệp'],
                ['title' => 'Mobile App'],
                ['title' => 'Website'],
                ['title' => 'API'],
                ['title' => 'Tích hợp hệ thống'],
            ],
            'process' => [
                ['title' => 'Khảo sát'],
                ['title' => 'Phân tích yêu cầu'],
                ['title' => 'Thiết kế giải pháp'],
                ['title' => 'Phát triển'],
                ['title' => 'Kiểm thử'],
                ['title' => 'Triển khai'],
                ['title' => 'Đào tạo'],
                ['title' => 'Bảo trì'],
            ],
            'tech' => [],
            'roles' => [
                ['title' => 'Business Analyst'],
                ['title' => 'Project Manager'],
                ['title' => 'Solution Architect'],
                ['title' => 'Backend Developer'],
                ['title' => 'Frontend Developer'],
                ['title' => 'Mobile Developer'],
                ['title' => 'QA/QC'],
                ['title' => 'DevOps'],
            ],
            'models' => [
                ['title' => 'Agile'],
                ['title' => 'Scrum'],
                ['title' => 'Waterfall'],
                ['title' => 'Outsourcing'],
                ['title' => 'Dedicated Team'],
            ],
            'partners' => [
                ['title' => 'Freshworks', 'link' => '#'],
                ['title' => 'AWS Partner', 'link' => '#'],
                ['title' => 'Google Cloud Partner', 'link' => '#'],
            ],
            'clients' => [
                ['title' => 'Client A'],
                ['title' => 'Client B'],
                ['title' => 'Client C'],
                ['title' => 'Client D'],
            ],
            'commitments' => [
                ['title' => 'Đúng tiến độ'],
                ['title' => 'Bảo mật'],
                ['title' => 'Chất lượng'],
                ['title' => 'Hỗ trợ lâu dài'],
                ['title' => 'Tối ưu chi phí'],
            ],
            'warranty' => [
                ['title' => 'Tiếp nhận'],
                ['title' => 'Phân loại'],
                ['title' => 'Xử lý'],
                ['title' => 'Kiểm thử'],
                ['title' => 'Bàn giao'],
            ],
        ];

        foreach ($items as $section => $rows) {
            $existing = CompanyProfileItem::where('section', $section)->exists();
            if ($existing) {
                continue;
            }

            foreach ($rows as $order => $row) {
                CompanyProfileItem::create(array_merge($row, [
                    'section'   => $section,
                    'sort_order'=> $order + 1,
                ]));
            }
        }

        $this->command->info('Company profile content seeded.');
    }
}
