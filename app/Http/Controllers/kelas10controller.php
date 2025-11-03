<?php

namespace App\Http\Controllers;
use App\Models\isikelas10;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
class kelas10controller extends Controller
{
    //


    public function index() : View
    {
        //get all kelas
        $kelas10 = isikelas10::latest()->paginate(10);

        //render view with kelas
        return view('dashboard.kelas.kelas10.index', compact('kelas10'));
    }

    public function create(): View
    {
        return view('dashboard.kelas.kelas10.create');
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        //validate form
        $request->validate([

            'title'         => 'required|min:5',
            'description'   => 'required|min:10'

        ]);
        //create product
        isikelas10::create([

            'title'         => $request->title,
            'description'   => $request->description


        ]);

        //redirect to index
        return redirect()->route('kelas10.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }


    public function destroy($id): RedirectResponse
    {
        //get product by ID
        $kelas10 = isikelas10::findOrFail($id);

        //delete product
        $kelas10->delete();

        //redirect to index
        return redirect()->route('kelas10.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
