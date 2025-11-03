<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class usercontroller extends Controller
{
    public function index()
    {
        $judul = "User Management";



        return view('dashboard.user.index', [
            "judul" => $judul,

        ]);
    }

}
