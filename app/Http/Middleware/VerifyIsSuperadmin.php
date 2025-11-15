<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role; // Pastikan model diimpor

class VerifyIsSuperadmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Cek apakah role 'superadmin' ada
        $superAdminRole = Role::where('role_name', 'superadmin')->first();

        // Jika role 'superadmin' tidak ditemukan di database
        if (!$superAdminRole) {
            // Anda bisa memilih untuk abort(500) atau redirect
            // abort(500, 'Role superadmin tidak ditemukan di database.');
            // Atau redirect dengan error
            return redirect()->back()->with('error', 'Konfigurasi role tidak valid.');
        }

        $roleId = $request->user()->role_id;
        $superAdminId = $superAdminRole->id;

        if ($roleId !== $superAdminId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
