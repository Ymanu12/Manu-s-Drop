<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AuditsAdminActions;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    use AuditsAdminActions;

    public function coupons()
    {
        $this->authorize('viewAny', Coupon::class);

        $coupons = Coupon::orderBy('expiry_date', 'DESC')->paginate(12);
        return view('admin.coupons', compact('coupons'));
    }

    public function coupon_add()
    {
        $this->authorize('create', Coupon::class);

        return view('admin.coupon-add');
    }

    public function coupon_store(Request $request)
    {
        $this->authorize('create', Coupon::class);

        $request->validate([
            'code' => 'required|unique:coupons,code|max:191',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'cart_value' => 'required|numeric|min:0',
            'expiry_date' => 'required|date',
        ]);

        $coupon = Coupon::create([
            'code' => $request->code,
            'type' => $request->type,
            'value' => $request->value,
            'cart_value' => $request->cart_value,
            'expiry_date' => $request->expiry_date,
        ]);

        $this->auditAdminAction('coupon.created', Coupon::class, $coupon->id, ['code' => $coupon->code]);

        return redirect()->route('admin.coupons')->with('success', 'Coupon created successfully!');
    }

    public function coupon_edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $this->authorize('view', $coupon);

        return view('admin.coupon-edit', compact('coupon'));
    }

    public function coupon_update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);
        $this->authorize('update', $coupon);

        $request->validate([
            'code' => 'required|max:191|unique:coupons,code,' . $id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'cart_value' => 'required|numeric|min:0',
            'expiry_date' => 'required|date',
        ]);

        $coupon->update([
            'code' => $request->code,
            'type' => $request->type,
            'value' => $request->value,
            'cart_value' => $request->cart_value,
            'expiry_date' => $request->expiry_date,
        ]);

        $this->auditAdminAction('coupon.updated', Coupon::class, $coupon->id, ['code' => $coupon->code]);

        return redirect()->route('admin.coupons')->with('success', 'Coupon updated successfully!');
    }

    public function coupon_destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $this->authorize('delete', $coupon);

        $couponId = $coupon->id;
        $coupon->delete();

        $this->auditAdminAction('coupon.deleted', Coupon::class, $couponId);

        return redirect()->route('admin.coupons')->with('success', 'Coupon deleted successfully!');
    }
}
