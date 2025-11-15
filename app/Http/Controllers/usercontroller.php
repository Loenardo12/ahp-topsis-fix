<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class usercontroller extends Controller
{
    public function index()
    {

        $users = User::with('role')->get();
        $roles = Role::all();

        return view('dashboard.user.index', compact('users','roles'));
    }

    public function updateRole(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id', // <-- Tambahkan tabel dan kolom
        'role_id' => 'required|exists:roles,id',
    ]);

    $userId = $request->user_id;
    $roleId = $request->role_id; // Perbaikan typo: $roleid -> $roleId

    $user = User::find($userId);
    $user->role_id = $roleId; // Perbaikan typo
    $user->save();

     return redirect()->route('users.index')->with('success', 'Role user berhasil diubah');
}


public function store(Request $request)
{


    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);



        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);


        return redirect()->route('users.index')->with('success', 'User baru berhasil ditambahkan.');

    } catch (\Exception $e) {
        \Log::error('Error creating user: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
        ]);

        // Tampilkan error ke user untuk debugging
        return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
}
public function destroy(Request $request, $id)
{
    $user = User::findOrFail($id);

    // Opsional: Cegah penghapusan diri sendiri
    if ($user->id === auth()->id()) {
        return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
    }

    $user->delete();

    return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
}
}
