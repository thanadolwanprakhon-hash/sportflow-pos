<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        return view('settings.edit', [
            'settings' => Setting::pluck('value', 'key'),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'shop_name' => ['required', 'max:120'],
            'shop_phone' => ['nullable', 'max:30'],
            'shop_address' => ['nullable', 'max:500'],
            'tax_id' => ['nullable', 'max:30'],
            'receipt_footer' => ['nullable', 'max:255'],
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('success', 'บันทึกการตั้งค่าร้านเรียบร้อยแล้ว');
    }
}
