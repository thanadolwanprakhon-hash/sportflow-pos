<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Member;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            ['employee_code' => 'EMP-001', 'name' => 'อธิป จันทร์เพ็ญ', 'email' => 'admin@sportflow.test', 'role' => 'manager', 'phone' => '081-234-5678', 'status' => 'active'],
            ['employee_code' => 'EMP-002', 'name' => 'พิมพ์ชนก แสงดี', 'email' => 'pimchanok@sportflow.test', 'role' => 'cashier', 'phone' => '089-112-3344', 'status' => 'active'],
            ['employee_code' => 'EMP-003', 'name' => 'วิชญ์พล คงมั่น', 'email' => 'witchapon@sportflow.test', 'role' => 'stock', 'phone' => '086-450-7821', 'status' => 'active'],
            ['employee_code' => 'EMP-004', 'name' => 'ชลธิชา ภักดี', 'email' => 'chonticha@sportflow.test', 'role' => 'cashier', 'phone' => '095-320-6641', 'status' => 'on_leave'],
        ];

        foreach ($employees as $employee) {
            User::updateOrCreate(
                ['email' => $employee['email']],
                $employee + ['password' => Hash::make('123456')],
            );
        }

        $categoryData = [
            'วิ่ง' => 'running',
            'ฟุตบอล' => 'football',
            'แบดมินตัน' => 'badminton',
            'เทนนิส' => 'tennis',
            'บาสเกตบอล' => 'basketball',
            'ว่ายน้ำ' => 'swimming',
            'ฟิตเนส' => 'fitness',
            'โยคะและพิลาทิส' => 'yoga-pilates',
            'มวย' => 'boxing',
            'เอาต์ดอร์' => 'outdoor',
            'กอล์ฟ' => 'golf',
        ];

        $categories = collect($categoryData)->mapWithKeys(function ($slug, $name) {
            $category = Category::updateOrCreate(
                ['name' => $name],
                ['slug' => $slug, 'is_active' => true],
            );

            return [$name => $category->id];
        });

        Category::whereNotIn('name', array_keys($categoryData))->update(['is_active' => false]);

        // ข้อมูลสำหรับ Assignment เท่านั้น: ชื่อ ราคา รูป และลิงก์อ้างอิงจาก Supersports ณ วันที่ 5 ต.ค. 2026
        $products = [
            ['sku' => 'SHO-NK-001', 'name' => 'NIKE Pegasus 42 รองเท้าวิ่งผู้ชาย', 'category' => 'วิ่ง', 'price' => 4160, 'cost' => 2850, 'stock' => 12, 'minimum_stock' => 5, 'image' => 'run-nike-pegasus-42.jpg', 'source' => 'nike-running-compete-ni083sh117exth-ib1873-001'],
            ['sku' => 'RUN-AS-002', 'name' => 'ASICS Novablast 6 Wide รองเท้าวิ่งผู้หญิง', 'category' => 'วิ่ง', 'price' => 5500, 'cost' => 3800, 'stock' => 15, 'minimum_stock' => 5, 'image' => 'run-asics-novablast-6.jpg', 'source' => 'asics-running-road-as206sh146ffth-1012c007-101'],
            ['sku' => 'RUN-AD-003', 'name' => 'ADIDAS Adizero Adios Pro 5 รองเท้าวิ่งผู้ชาย', 'category' => 'วิ่ง', 'price' => 9200, 'cost' => 6500, 'stock' => 7, 'minimum_stock' => 4, 'image' => 'run-adidas-adios-pro-5.jpg', 'source' => 'adidas-running-compete-ad001sh625ffth-ki8294'],
            ['sku' => 'RUN-AS-004', 'name' => 'ASICS Superblast 3 รองเท้าวิ่งผู้ใหญ่', 'category' => 'วิ่ง', 'price' => 7700, 'cost' => 5400, 'stock' => 9, 'minimum_stock' => 4, 'image' => 'run-asics-superblast-3.jpg', 'source' => 'asics-running-road-as206sh353fgth-1013a177-101'],

            ['sku' => 'FTB-PU-005', 'name' => 'PUMA เสื้อทีมชาติโปรตุเกส ชุดเยือน 2026', 'category' => 'ฟุตบอล', 'price' => 2030, 'cost' => 1200, 'stock' => 17, 'minimum_stock' => 5, 'image' => 'football-puma-portugal-jersey.jpg', 'source' => 'puma-apparel-club-jersey-pu097ap518eyth-78328802'],
            ['sku' => 'FTB-NK-006', 'name' => 'NIKE เสื้อทีมชาติอังกฤษ Stadium ชุดเหย้า 2026', 'category' => 'ฟุตบอล', 'price' => 2320, 'cost' => 1400, 'stock' => 14, 'minimum_stock' => 5, 'image' => 'football-nike-england-jersey.jpg', 'source' => 'nike-apparel-club-jersey-ni083ap725eyth-ib5290-100'],
            ['sku' => 'FTB-AD-007', 'name' => 'ADIDAS เสื้อทีมชาติญี่ปุ่น ชุดเหย้า 2026/27', 'category' => 'ฟุตบอล', 'price' => 2320, 'cost' => 1400, 'stock' => 11, 'minimum_stock' => 5, 'image' => 'football-adidas-japan-jersey.jpg', 'source' => 'adidas-apparel-club-jersey-ad001ap334esth-kd3345'],
            ['sku' => 'FTB-AD-008', 'name' => 'ADIDAS FIFA World Cup 26 Trionda Pro ลูกฟุตบอล', 'category' => 'ฟุตบอล', 'price' => 5000, 'cost' => 3200, 'stock' => 6, 'minimum_stock' => 4, 'image' => 'football-adidas-trionda-pro.jpg', 'source' => 'adidas-soccer-accessories-ball-4-5-ad001ac101erth-jd8021'],

            ['sku' => 'BDM-YO-009', 'name' => 'YONEX GR Alpha ไม้แบดมินตันแพ็คคู่', 'category' => 'แบดมินตัน', 'price' => 590, 'cost' => 300, 'stock' => 25, 'minimum_stock' => 7, 'image' => 'badminton-yonex-gr-alpha.jpg', 'source' => 'yonex-gr-alpha-2-pack-badminton-racket-yo136sp015gcth-gr-alpha'],
            ['sku' => 'BDM-AS-010', 'name' => 'ASICS Upcourt 6 รองเท้า Indoor Court ผู้ชาย', 'category' => 'แบดมินตัน', 'price' => 1100, 'cost' => 650, 'stock' => 12, 'minimum_stock' => 5, 'image' => 'badminton-asics-upcourt-6.jpg', 'source' => 'asics-other-team-sports-men-as206sh246elth-1071a104-104'],
            ['sku' => 'BDM-YO-011', 'name' => 'YONEX MV350 ลูกแบดมินตัน 6 ลูก', 'category' => 'แบดมินตัน', 'price' => 345, 'cost' => 180, 'stock' => 35, 'minimum_stock' => 10, 'image' => 'badminton-yonex-mv350.jpg', 'source' => 'yonex-mv350-6-pieces-shuttlecock-yo136sp036fmth-mv-350-ye-1x6'],
            ['sku' => 'BDM-VE-012', 'name' => 'VENSON Bullet Diamond Speed 76 ลูกแบดมินตัน 12 ลูก', 'category' => 'แบดมินตัน', 'price' => 730, 'cost' => 420, 'stock' => 18, 'minimum_stock' => 6, 'image' => 'badminton-venson-bullet.jpg', 'source' => 'venson-badminton-shuttlecok-ve718ac405enth-sht-bullets-76'],

            ['sku' => 'TEN-AS-013', 'name' => 'ASICS Court FF 3 รองเท้าเทนนิสผู้ชาย', 'category' => 'เทนนิส', 'price' => 5200, 'cost' => 3500, 'stock' => 10, 'minimum_stock' => 4, 'image' => 'tennis-asics-court-ff-3.jpg', 'source' => 'asics-tennis-men-as206sh887fbth-1041a370-107'],
            ['sku' => 'TEN-NK-014', 'name' => 'NIKE GP Challenge 1.5 Hard Court รองเท้าเทนนิส', 'category' => 'เทนนิส', 'price' => 4560, 'cost' => 2900, 'stock' => 8, 'minimum_stock' => 4, 'image' => 'tennis-nike-gp-challenge.jpg', 'source' => 'nike-tennis-men-ni083sh821ezth-iq5180-005'],
            ['sku' => 'TEN-WI-015', 'name' => 'WILSON Clash 100L V3 RG 2026 ไม้เทนนิส', 'category' => 'เทนนิส', 'price' => 9690, 'cost' => 6800, 'stock' => 5, 'minimum_stock' => 5, 'image' => 'tennis-wilson-clash-100l.jpg', 'source' => 'wilson-tennis-perfomance-wi134sp253fath-wr204411u2'],
            ['sku' => 'TEN-HE-016', 'name' => 'HEAD Tour XT ลูกเทนนิสแพ็ค 3 ลูก', 'category' => 'เทนนิส', 'price' => 280, 'cost' => 130, 'stock' => 30, 'minimum_stock' => 10, 'image' => 'tennis-head-tour-xt.jpg', 'source' => 'head-tennis-ball-he208ac025ewth-570823'],

            ['sku' => 'BAS-NK-017', 'name' => 'NIKE KD19 EP รองเท้าบาสเกตบอลผู้ชาย', 'category' => 'บาสเกตบอล', 'price' => 4640, 'cost' => 3000, 'stock' => 10, 'minimum_stock' => 4, 'image' => 'basketball-nike-kd19.jpg', 'source' => 'nike-basketball-advanced-ni083sh755eyth-ih1120-600'],
            ['sku' => 'BAS-NK-018', 'name' => 'NIKE Ja 4 Dark Mode รองเท้าบาสเกตบอลผู้ชาย', 'category' => 'บาสเกตบอล', 'price' => 4900, 'cost' => 3200, 'stock' => 9, 'minimum_stock' => 4, 'image' => 'basketball-nike-ja4.jpg', 'source' => 'nike-basketball-advanced-ni083sh193fgth-im4135-001'],
            ['sku' => 'BAS-NK-019', 'name' => 'NIKE Los Angeles Lakers Icon Edition เสื้อบาสเกตบอล', 'category' => 'บาสเกตบอล', 'price' => 2040, 'cost' => 1150, 'stock' => 13, 'minimum_stock' => 5, 'image' => 'basketball-lakers-jersey.jpg', 'source' => 'nike-los-angeles-lakers-icon-edition-202223-mens-basketball-tank-ni083ap482dpth-dn2009-733'],
            ['sku' => 'BAS-MO-020', 'name' => 'MOLTEN B7G4550 ลูกบาสเกตบอล เบอร์ 7', 'category' => 'บาสเกตบอล', 'price' => 2500, 'cost' => 1600, 'stock' => 3, 'minimum_stock' => 5, 'image' => 'basketball-molten-b7g4550.png', 'source' => 'molten-บาสเกตบอล-b7g4550-หนัง-pu-มีมอก-ลูกบาส-เบอร์7-รุ่นแข่งขัน-รุ่นใหม่-ลายหนังใกล้เคียง-gg7x-สีส้ม-orange'],

            ['sku' => 'SWM-SP-021', 'name' => 'SPEEDO 2XT Essentials เสื้อรัชการ์ดผู้ชาย', 'category' => 'ว่ายน้ำ', 'price' => 538, 'cost' => 300, 'stock' => 22, 'minimum_stock' => 6, 'image' => 'swim-speedo-rashguard.jpg', 'source' => 'speedo-2xt-essentials-men-rashguard-sp111sp795dith-soj22013-ab00'],
            ['sku' => 'SWM-SP-022', 'name' => 'SPEEDO Sunny G Mariner Mirrored แว่นตาว่ายน้ำ', 'category' => 'ว่ายน้ำ', 'price' => 1032, 'cost' => 620, 'stock' => 15, 'minimum_stock' => 5, 'image' => 'swim-speedo-sunny-g.jpg', 'source' => 'speedo-sunny-g-mariner-mirrored-mens-goggles-sp111ac225eeth-8-00412614621'],
            ['sku' => 'SWM-SP-023', 'name' => 'SPEEDO Fastskin Speedsocket 2 Mirror แว่นตาว่ายน้ำ', 'category' => 'ว่ายน้ำ', 'price' => 636, 'cost' => 350, 'stock' => 20, 'minimum_stock' => 6, 'image' => 'swim-speedo-fastskin.jpg', 'source' => 'speedo-fastskin-speedsocket-2-mirror-men-goggles-sp111ac868dsth-8-1089717109'],
            ['sku' => 'SWM-SP-024', 'name' => 'SPEEDO Biofuse ที่อุดหูว่ายน้ำ', 'category' => 'ว่ายน้ำ', 'price' => 232, 'cost' => 90, 'stock' => 40, 'minimum_stock' => 10, 'image' => 'swim-speedo-earplug.jpg', 'source' => 'speedo-biofuse-8-00237414491-unisex-earplug-sp111ac075cwth-8-00237414491'],

            ['sku' => 'FIT-SS-025', 'name' => 'เคตเทิลเบลออกกำลังกาย 4 กก.', 'category' => 'ฟิตเนส', 'price' => 244, 'cost' => 120, 'stock' => 18, 'minimum_stock' => 5, 'image' => 'fitness-kettlebell.png', 'source' => 'เคตเทิลเบล-ขนาด-4-12kg-หลากสี-multi-color'],
            ['sku' => 'FIT-SS-026', 'name' => 'S SPORTS DM02 ดัมเบล 6 เหลี่ยม 2 กก.', 'category' => 'ฟิตเนส', 'price' => 150, 'cost' => 70, 'stock' => 28, 'minimum_stock' => 8, 'image' => 'fitness-ssports-dumbbell-2kg.jpg', 'source' => 's-sports-dm02-shiny-2kg-hexagon-dumbbell-sb146sp330cath-sc-db02hs'],
            ['sku' => 'FIT-PT-027', 'name' => 'PTP Resistance System Mini ยางยืด 9 ชิ้น', 'category' => 'ฟิตเนส', 'price' => 199, 'cost' => 90, 'stock' => 24, 'minimum_stock' => 7, 'image' => 'fitness-ptp-resistance-mini.jpg', 'source' => 'ptp-resistance-system-9-pieces-resistance-band-pt260ac514bvth-rs-mini'],
            ['sku' => 'FIT-SS-028', 'name' => 'S SPORTS DB05HS ดัมเบล 6 เหลี่ยม 5 กก.', 'category' => 'ฟิตเนส', 'price' => 416, 'cost' => 220, 'stock' => 16, 'minimum_stock' => 5, 'image' => 'fitness-ssports-dumbbell-5kg.jpg', 'source' => 's-sports-db05hs-5kg-hexagon-dumbbell-sb146ac461byth-sc-db05hs'],

            ['sku' => 'YOG-EN-029', 'name' => 'ENERGETICS NBA Mat 183 เสื่อโยคะ', 'category' => 'โยคะและพิลาทิส', 'price' => 621, 'cost' => 340, 'stock' => 14, 'minimum_stock' => 5, 'image' => 'yoga-energetics-nba-mat.jpg', 'source' => 'energetics-fitness-accessories-others-en714ac430fcth-183003051'],
            ['sku' => 'YOG-SS-030', 'name' => 'S SPORTS TPE 8MM เสื่อโยคะ', 'category' => 'โยคะและพิลาทิส', 'price' => 501, 'cost' => 260, 'stock' => 20, 'minimum_stock' => 6, 'image' => 'yoga-ssports-tpe-8mm.jpg', 'source' => 's-sports-fitness-accessories-yoga-mat-sb146ac174fbth-sc-ym810'],
            ['sku' => 'YOG-EN-031', 'name' => 'ENERGETICS Pink 22cm ลูกบอลพิลาทิส', 'category' => 'โยคะและพิลาทิส', 'price' => 162, 'cost' => 75, 'stock' => 32, 'minimum_stock' => 10, 'image' => 'yoga-energetics-pilates-ball.jpg', 'source' => 'energetics-fitness-accessories-gym-ball-en714ac009fdth-185014336'],

            ['sku' => 'BOX-HF-032', 'name' => 'HOMEFITTOOLS นวมชกมวย MMA', 'category' => 'มวย', 'price' => 390, 'cost' => 210, 'stock' => 20, 'minimum_stock' => 6, 'image' => 'boxing-mma-gloves.png', 'source' => 'นวมชกมวย-mma-homefittools'],
            ['sku' => 'BOX-FB-033', 'name' => 'FBT นวมฝึกซ้อม หนังเทียม PVC No.007', 'category' => 'มวย', 'price' => 870, 'cost' => 480, 'stock' => 12, 'minimum_stock' => 5, 'image' => 'boxing-fbt-training-gloves.jpg', 'source' => 'fbt-นวมฝึกซ้อม-หนังเทียม-pvc-no-007-6-18-ออนซ์-43330-6-black-สีดำ-black'],
            ['sku' => 'BOX-FP-034', 'name' => 'FIGHT PRO กระสอบทรายตั้งพื้น', 'category' => 'มวย', 'price' => 6990, 'cost' => 4300, 'stock' => 4, 'minimum_stock' => 5, 'image' => 'boxing-fight-pro.png', 'source' => 'กระสอบทราย-ตั้งพื้น-fight-pro-สีดำ-black'],

            ['sku' => 'OUT-CO-035', 'name' => 'COLUMBIA Konos TRS OutDry รองเท้าเดินป่าผู้ชาย', 'category' => 'เอาต์ดอร์', 'price' => 3983, 'cost' => 2700, 'stock' => 9, 'minimum_stock' => 4, 'image' => 'outdoor-columbia-konos.jpg', 'source' => 'columbia-footwear-hiking-co399sh817eoth-2099811089'],
            ['sku' => 'OUT-ME-036', 'name' => 'MERRELL Maipo Explorer Sieve รองเท้าเดินป่าผู้หญิง', 'category' => 'เอาต์ดอร์', 'price' => 3990, 'cost' => 2700, 'stock' => 8, 'minimum_stock' => 4, 'image' => 'outdoor-merrell-maipo.jpg', 'source' => 'merrell-footwear-hiking-me189sh495ejth-j038480'],
            ['sku' => 'OUT-ME-037', 'name' => 'MERRELL Moab 3 Mid GORE-TEX รองเท้าปีนเขาผู้ชาย', 'category' => 'เอาต์ดอร์', 'price' => 6790, 'cost' => 4800, 'stock' => 6, 'minimum_stock' => 4, 'image' => 'outdoor-merrell-moab3.jpg', 'source' => 'merrell-moab-3-mid-gore-tex-men-hiking-shoes-me189sh285ecth-j035785'],

            ['sku' => 'GLF-SK-038', 'name' => 'SKECHERS GO GOLF Prestige SL รองเท้ากอล์ฟ', 'category' => 'กอล์ฟ', 'price' => 4230, 'cost' => 2800, 'stock' => 7, 'minimum_stock' => 4, 'image' => 'golf-skechers-prestige.jpg', 'source' => 'skechers-golf-shoes-spiked-shoes-sk108sh324ezth-214126-wht'],
            ['sku' => 'GLF-OA-039', 'name' => 'OAKLEY Edge Icon รองเท้ากอล์ฟผู้ชาย', 'category' => 'กอล์ฟ', 'price' => 3810, 'cost' => 2500, 'stock' => 8, 'minimum_stock' => 4, 'image' => 'golf-oakley-edge-icon.jpg', 'source' => 'fof100539-02e-oakley-oakley-edge-icon-men'],
            ['sku' => 'GLF-NI-040', 'name' => '9NINE N25GOPOM07 เสื้อโปโลกอล์ฟผู้ชาย', 'category' => 'กอล์ฟ', 'price' => 756, 'cost' => 350, 'stock' => 18, 'minimum_stock' => 6, 'image' => 'golf-9nine-polo.jpg', 'source' => '9nine-apparel-top-9n358ap404epth-n25gopom07nv'],
        ];

        foreach ($products as $productData) {
            $product = Product::withTrashed()->updateOrCreate(
                ['sku' => $productData['sku']],
                [
                    'category_id' => $categories[$productData['category']],
                    'name' => $productData['name'],
                    'image_url' => 'images/products/'.$productData['image'],
                    'source_url' => 'https://www.supersports.co.th/products/'.$productData['source'],
                    'price' => $productData['price'],
                    'cost' => $productData['cost'],
                    'stock' => $productData['stock'],
                    'minimum_stock' => $productData['minimum_stock'],
                    'is_active' => true,
                ],
            );

            if ($product->trashed()) {
                $product->restore();
            }
        }

        Product::whereNotIn('sku', collect($products)->pluck('sku'))->delete();

        $members = [
            ['MBR-0001', 'ธนกฤต วัฒนชัย', '089-245-7810', 'thanakrit@email.com', 1240, 32450, 'gold'],
            ['MBR-0002', 'พิชญา สุขสันต์', '081-442-1902', 'pitchaya@email.com', 680, 16780, 'silver'],
            ['MBR-0003', 'ณัฐพงษ์ ใจดี', '086-725-4481', 'nattapong@email.com', 210, 5290, 'member'],
            ['MBR-0004', 'กมลชนก ศรีทอง', '095-661-8834', 'kamonchanok@email.com', 1950, 48220, 'gold'],
            ['MBR-0005', 'ศุภกร มั่นคง', '092-348-2201', 'supakorn@email.com', 420, 10290, 'silver'],
        ];

        foreach ($members as [$code, $name, $phone, $email, $points, $spent, $tier]) {
            Member::updateOrCreate(
                ['member_code' => $code],
                ['name' => $name, 'phone' => $phone, 'email' => $email, 'points' => $points, 'total_spent' => $spent, 'tier' => $tier, 'joined_at' => now()->subMonths(random_int(1, 10))->toDateString()],
            );
        }

        $settings = [
            'shop_name' => 'SportFlow Sports',
            'shop_phone' => '02-123-4567',
            'shop_address' => '99 ถนนสุขุมวิท กรุงเทพมหานคร 10110',
            'tax_id' => '0105569123456',
            'receipt_footer' => 'ขอบคุณที่เลือกซื้ออุปกรณ์กีฬากับ SportFlow',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $demoSales = [
            ['INV-DEMO-001', 2, 2, 'cash', 0, [['FTB-NK-006', 1]]],
            ['INV-DEMO-002', 3, 2, 'qr', 0, [['SHO-NK-001', 1], ['TEN-HE-016', 1]]],
            ['INV-DEMO-003', null, 1, 'card', 1, [['FIT-SS-026', 2], ['SWM-SP-024', 1]]],
            ['INV-DEMO-004', 4, 2, 'cash', 2, [['FTB-AD-008', 1]]],
            ['INV-DEMO-005', 5, 1, 'qr', 4, [['BOX-FB-033', 1], ['YOG-SS-030', 1]]],
        ];

        foreach ($demoSales as [$invoice, $memberId, $employeeId, $method, $daysAgo, $lines]) {
            if (Sale::where('invoice_number', $invoice)->exists()) {
                continue;
            }

            $items = collect($lines)->map(function ($line) {
                $product = Product::where('sku', $line[0])->firstOrFail();

                return ['product' => $product, 'quantity' => $line[1], 'line_total' => (float) $product->price * $line[1]];
            });
            $subtotal = $items->sum('line_total');
            $discount = $memberId ? round($subtotal * .05, 2) : 0;
            $total = $subtotal - $discount;
            $soldAt = now()->subDays($daysAgo)->setTime(10 + $daysAgo, 15);
            $sale = Sale::create([
                'invoice_number' => $invoice,
                'member_id' => $memberId,
                'employee_id' => $employeeId,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'status' => 'completed',
                'sold_at' => $soldAt,
            ]);

            foreach ($items as $item) {
                $sale->items()->create([
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'unit_price' => $item['product']->price,
                    'unit_cost' => $item['product']->cost,
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                ]);
            }

            $sale->payment()->create([
                'method' => $method,
                'amount' => $total,
                'received_amount' => $total,
                'change_amount' => 0,
                'status' => 'paid',
                'paid_at' => $soldAt,
            ]);
        }
    }
}
