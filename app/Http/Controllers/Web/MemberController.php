<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $members = Member::query()
            ->when($request->search, fn ($q, $search) => $q->where(fn ($sub) => $sub->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%")->orWhere('member_code', 'like', "%{$search}%")))
            ->latest()->paginate(12)->withQueryString();
        return view('members.index', compact('members'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['member_code'] = 'MBR-'.str_pad((string) (Member::withTrashed()->count() + 1), 4, '0', STR_PAD_LEFT);
        $data['joined_at'] = today();
        Member::create($data);
        return back()->with('success', 'สมัครสมาชิกเรียบร้อยแล้ว');
    }

    public function edit(Member $member) { return redirect()->route('members.index', ['edit' => $member->id]); }

    public function update(Request $request, Member $member)
    {
        $member->update($this->validated($request, $member));
        return back()->with('success', 'แก้ไขสมาชิกเรียบร้อยแล้ว');
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return back()->with('success', 'ลบสมาชิกเรียบร้อยแล้ว');
    }

    private function validated(Request $request, ?Member $member = null): array
    {
        return $request->validate([
            'name' => ['required', 'max:255'],
            'phone' => ['required', 'max:20', Rule::unique('members')->ignore($member)],
            'email' => ['nullable', 'email'],
            'tier' => ['required', Rule::in(['member', 'silver', 'gold'])],
            'points' => ['required', 'integer', 'min:0'],
        ]);
    }
}
