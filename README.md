# SportFlow POS

ระบบขายหน้าร้าน (Point of Sale) สำหรับร้านจำหน่ายอุปกรณ์กีฬา พัฒนาด้วย Laravel Framework และ Blade Template เชื่อมต่อฐานข้อมูล MySQL/MariaDB รองรับตั้งแต่การจัดการสินค้าและสต็อก ไปจนถึงการขาย ชำระเงิน และสรุปรายงาน

## ผู้จัดทำ

- รหัสนักศึกษา: 6814120014
- ชื่อ: ธนดล วันประโคน

## ความสามารถของระบบ

- เข้าสู่ระบบและออกจากระบบ
- Dashboard สรุปยอดขาย จำนวนบิล สมาชิก และสินค้าใกล้หมด
- จัดการสินค้า หมวดหมู่ รูปสินค้า ราคา และจำนวนสต็อก
- จัดการข้อมูลสมาชิก คะแนนสะสม ยอดซื้อรวม และระดับสมาชิก
- จัดการข้อมูลพนักงาน ตำแหน่ง และสถานะการทำงาน
- หน้าจุดขายสินค้า POS พร้อมตะกร้าสินค้าและส่วนลดสมาชิก
- ชำระเงินด้วยเงินสด QR Code หรือบัตร พร้อมคำนวณเงินทอน
- ออกใบเสร็จและตรวจสอบประวัติการขาย
- รายงานยอดขาย กำไรขั้นต้น ช่องทางชำระเงิน และสินค้าขายดี
- ตั้งค่าข้อมูลร้าน เบอร์โทร ที่อยู่ เลขประจำตัวผู้เสียภาษี และข้อความท้ายใบเสร็จ
- ป้องกันการขายเกินสต็อกด้วย Database Transaction และ Row Lock

## เทคโนโลยีที่ใช้

- PHP 8.2 ขึ้นไป
- Laravel 12
- Blade Template
- MySQL หรือ MariaDB
- HTML, CSS และ JavaScript
- PHPUnit 11
- XAMPP และ phpMyAdmin สำหรับสภาพแวดล้อมพัฒนาในเครื่อง

## ความต้องการของระบบ

ก่อนติดตั้งควรมีโปรแกรมต่อไปนี้

- PHP 8.2 ขึ้นไป หรือ XAMPP
- Composer
- MySQL/MariaDB
- Git
- Node.js และ npm เฉพาะกรณีที่ต้องการ build frontend assets ใหม่

## วิธีติดตั้ง

### 1. ดาวน์โหลดโปรเจกต์

```bash
git clone https://github.com/thanadolwanprakhon-hash/sportflow-pos.git
cd sportflow-pos
```

หากดาวน์โหลดเป็น ZIP ให้แตกไฟล์และเปิด Terminal ภายในโฟลเดอร์ `sportflow-pos`

### 2. ติดตั้ง PHP dependencies

```bash
composer install
```

### 3. สร้างไฟล์ Environment

Windows:

```powershell
Copy-Item .env.example .env
```

macOS/Linux:

```bash
cp .env.example .env
```

จากนั้นสร้าง Application Key

```bash
php artisan key:generate
```

### 4. สร้างฐานข้อมูล

เปิด XAMPP แล้ว Start `Apache` และ `MySQL` จากนั้นเข้า phpMyAdmin และสร้างฐานข้อมูลชื่อ

```text
sportflow_pos
```

ตรวจสอบค่าฐานข้อมูลในไฟล์ `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sportflow_pos
DB_USERNAME=root
DB_PASSWORD=
```

หาก MySQL ของเครื่องมีรหัสผ่าน ให้ใส่รหัสผ่านใน `DB_PASSWORD`

### 5. สร้างตารางและข้อมูลสาธิต

```bash
php artisan migrate:fresh --seed
```

คำสั่งนี้จะสร้างตาราง ผู้ใช้งาน สมาชิก สินค้า 40 รายการ ตัวอย่างการขาย และข้อมูลตั้งต้นของร้าน

> คำเตือน: `migrate:fresh` จะลบตารางเดิมทั้งหมดก่อนสร้างใหม่ จึงควรใช้กับฐานข้อมูลสำหรับสาธิตเท่านั้น

### 6. เปิดใช้งานระบบ

```bash
php artisan serve
```

เปิดเว็บเบราว์เซอร์ที่

```text
http://127.0.0.1:8000
```

## บัญชีสำหรับทดลอง

```text
อีเมล: admin@sportflow.test
รหัสผ่าน: 123456
```

บัญชีนี้มีตำแหน่งผู้จัดการและสามารถเปิดใช้งานทุกโมดูลของระบบได้

## การทดสอบระบบ

โปรเจกต์มี Unit Test และ Feature Test ครอบคลุมการเข้าสู่ระบบ การเปิดโมดูลหลัก การชำระเงิน การตัดสต็อก และการเพิ่มคะแนนสมาชิก

```bash
php artisan test --do-not-cache-result
```

ผลการทดสอบล่าสุด:

```text
7 tests passed
29 assertions
```

## โครงสร้างส่วนสำคัญ

```text
app/Http/Controllers/Web/   Controller ของหน้าเว็บ
app/Models/                 Eloquent Models
database/migrations/        โครงสร้างฐานข้อมูล
database/seeders/           ข้อมูลสาธิต
public/css/                 CSS ของระบบ
public/images/              โลโก้และรูปสินค้า
resources/views/            Blade Templates
routes/web.php              Web Routes
tests/                      Unit และ Feature Tests
report/                     ต้นฉบับรายงาน Assignment
```

## หมายเหตุเกี่ยวกับ GitHub

- ห้ามอัปโหลดไฟล์ `.env` เพราะมีข้อมูลการเชื่อมต่อฐานข้อมูลและ Application Key
- ไม่ต้องอัปโหลด `vendor` และ `node_modules` เนื่องจากติดตั้งใหม่ได้ด้วย Composer และ npm
- ควรตั้ง Repository เป็น Public เพื่อให้อาจารย์ตรวจสอบได้
- หลัง Clone โปรเจกต์ ให้ทำตามขั้นตอนติดตั้งด้านบนก่อนใช้งาน

## แหล่งข้อมูลสินค้า

ชื่อสินค้า ราคา รูปภาพ และลิงก์อ้างอิงบางส่วนจัดทำเพื่อใช้ประกอบ Assignment โดยอ้างอิงข้อมูลจากเว็บไซต์ Supersports ข้อมูลในระบบเป็นข้อมูลสาธิตและโครงการนี้ไม่มีความเกี่ยวข้องอย่างเป็นทางการกับ Supersports หรือเจ้าของเครื่องหมายการค้าแต่ละราย

## License

โปรเจกต์นี้จัดทำเพื่อการศึกษาและใช้ประกอบ Assignment เท่านั้น
