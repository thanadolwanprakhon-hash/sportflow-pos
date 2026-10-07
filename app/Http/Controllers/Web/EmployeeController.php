<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = User::query()
            ->when($request->search, fn ($q, $search) => $q->where(fn ($sub) => $sub->where('name', 'like', "%{$search}%")->orWhere('employee_code', 'like', "%{$search}%")))
            ->when($request->role, fn ($q, $role) => $q->where('role', $role))
            ->latest()->paginate(12)->withQueryString();
        return view('employees.index', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['employee_code'] = 'EMP-'.str_pad((string) (User::count() + 1), 3, '0', STR_PAD_LEFT);
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        return back()->with('success', 'เพิ่มพนักงานเรียบร้อยแล้ว');
    }

    public function edit(User $employee) { return redirect()->route('employees.index', ['edit' => $employee->id]); }

    public function update(Request $request, User $employee)
    {
        $data = $this->validated($request, $employee);
        if (empty($data['password'])) unset($data['password']);
        else $data['password'] = Hash::make($data['password']);
        $employee->update($data);
        return back()->with('success', 'แก้ไขพนักงานเรียบร้อยแล้ว');
    }

    public function destroy(User $employee)
    {
        abort_if($employee->id === auth()->id(), 422, 'ไม่สามารถลบบัญชีที่กำลังใช้งาน');
        $employee->delete();
        return back()->with('success', 'ลบพนักงานเรียบร้อยแล้ว');
    }

    private function validated(Request $request, ?User $employee = null): array
    {
        return $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($employee)],
            'phone' => ['nullable', 'max:20'],
            'role' => ['required', Rule::in(['admin', 'manager', 'cashier', 'stock'])],
            'status' => ['required', Rule::in(['active', 'on_leave', 'suspended'])],
            'password' => [$employee ? 'nullable' : 'required', 'min:6'],
        ]);
    }
}
