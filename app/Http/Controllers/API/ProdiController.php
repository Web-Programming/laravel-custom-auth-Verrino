<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prodis = Prodi::all();
        return $this->sendResponse($prodis, 'Data Prodi');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validasi = $request->validate([
            'nama' => 'required|min:5|max:20',
            'foto' => 'required|file|mimes:jpeg,png,jpg|max:5000',
        ]);

        $ext = $request->foto->getClientOriginalExtension();
        $nama_file = "foto-" . time() . "." . $ext;

        $path = $request->foto->storeAs('public', $nama_file);

        $prodi = new Prodi();
        $prodi->nama = $validasi['nama'];
        $prodi->foto = $nama_file;

        if ($prodi->save()) {
            $success['data'] = $prodi;
            return $this->sendResponse($success, 'Data Prodi Berhasil Disimpan.');
        } else {
            return $this->sendError('Error.', ['error' =>'Data Prodi Gagal Disimpan.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validasi = $request->validate([
            'nama' => 'required|min:5|max:20',
            'foto' => 'required|file|mimes:jpeg,png,jpg|max:5000',
        ]);

        $ext = $request->foto->getClientOriginalExtension();
        $nama_file = "foto-" . time() . "." . $ext;

        $path = $request->foto->storeAs('public', $nama_file);

        $prodi = Prodi::find($id);
        $prodi->nama = $validasi['nama'];
        $prodi->foto = $nama_file;

        if ($prodi->save()) {
            $success['data'] = $prodi;
            return $this->sendResponse($success, 'Data Prodi Berhasil Diperbarui.');
        } else {
            return $this->sendError('Error.', ['error' =>'Data Prodi Gagal Diperbarui.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $prodi = Prodi::findOrFail($id);

        if ($prodi->delete()) {
            $success['data'] = $prodi;
            return $this->sendResponse($success, 'Data Prodi Berhasil Dihapus.');
        } else {
            return $this->sendError('Error.', ['error' =>'Data Prodi Gagal Dihapus.']);
        }
    }
}
