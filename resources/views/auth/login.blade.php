<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เข้าสู่ระบบ · SportFlow POS</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sportflow-logo-transparent.png') }}">
    <link rel="stylesheet" href="{{ asset('css/sportflow.css') }}?v={{ filemtime(public_path('css/sportflow.css')) }}">
</head>
<body>
<main class="login-page">
    <section class="login-visual">
        <div class="login-brand"><img class="login-brand-logo" src="{{ asset('images/sportflow-logo-transparent.png') }}" alt="SportFlow Smart Retail POS"></div>
        <div class="login-copy">
            <span class="eyebrow">PLAY · SELL · GROW</span>
            <h1>ระบบขายที่พร้อม<br>ขยับไปกับธุรกิจ</h1>
            <p>จัดการหน้าร้าน สต็อก สมาชิก และดูยอดขายของร้านอุปกรณ์กีฬาได้ครบในที่เดียว</p>
        </div>
        <div class="sport-chips"><span>⚽ ฟุตบอล</span><span>🏸 แบดมินตัน</span><span>🏃 วิ่ง</span><span>🏋 ฟิตเนส</span></div>
    </section>
    <section class="login-panel">
        <form class="login-form" method="POST" action="{{ route('login.store') }}">
            @csrf
            <h2>ยินดีต้อนรับ</h2>
            <p>เข้าสู่ระบบเพื่อเริ่มงานหน้าร้าน</p>
            @if($errors->any())<div class="flash flash-error">{{ $errors->first() }}</div>@endif
            <div class="field">
                <label for="email">อีเมล</label>
                <input id="email" type="email" name="email" value="{{ old('email', 'admin@sportflow.test') }}" required autofocus>
            </div>
            <div class="field">
                <label for="password">รหัสผ่าน</label>
                <input id="password" type="password" name="password" value="123456" required>
            </div>
            <label class="check-row"><input type="checkbox" name="remember" value="1"> จดจำการเข้าสู่ระบบ</label>
            <button class="btn btn-primary" type="submit">เข้าสู่ระบบ →</button>
            <div class="demo-box"><strong>บัญชีสำหรับสาธิต</strong><br>admin@sportflow.test &nbsp;·&nbsp; รหัสผ่าน 123456</div>
        </form>
    </section>
</main>
</body>
</html>
