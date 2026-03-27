<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AuditsAdminActions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    use AuditsAdminActions;

    public function editAccount()
    {
        Gate::authorize('access-admin');

        $admin = auth()->user();
        return view('admin.settings', compact('admin'));
    }

    public function updateAccount(Request $request)
    {
        Gate::authorize('access-admin');

        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'old_password' => 'nullable|required_with:new_password|string|current_password',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $validated['name'],
            'mobile' => $validated['mobile'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['new_password'])) {
            $user->update([
                'password' => Hash::make($validated['new_password']),
            ]);
        }

        $this->auditAdminAction('admin.account.updated', get_class($user), $user->id, ['email' => $user->email]);

        return back()->with('success', 'Account updated successfully.');
    }
}
