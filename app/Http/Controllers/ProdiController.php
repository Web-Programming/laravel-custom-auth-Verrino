<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdiController extends Controller
{
    //
    public function index()
    {
        // $kampus = "Universitas Multi Data Palembang";
        // return view('prodi.index')->with('kampus', $kampus);

        $prodis = Prodi::all();
        return view("prodi.index")->with('prodis', $prodis);
    }

    public function show(Prodi $prodi) {
        return view('prodi.show', ['prodi' => $prodi]);
    }

    public function edit(Prodi $prodi) {
        $this->authorize('update', $prodi);

        return view('prodi.edit', ['prodi'=> $prodi]);
    }

    public function update(Request $request, Prodi $prodi) {
        $validateData = $request->validate([
            'nama'=> 'required|min:5|max:20',
        ]);

        Prodi::where('id', $prodi->id)->update($validateData);
        session()->flash('info', "Data prodi $prodi->nama berhasil diubah");
        return redirect()->route('prodi.index');
    }

    public function destroy(Prodi $prodi) {
        $this->authorize('delete', $prodi);

        $prodi->delete();
        return redirect()->route('prodi.index')->with('info', "Prodi $prodi->nama berhasil dihapus");
    }

    public function allJoinFacade() {
        $kampus = 'Universitas Multi Data Palembang';
        $result = DB::select('select mahasiswas.*, prodis.nama as nama_prodi from mahasiswas, prodis where prodis.id = mahasiswas.prodi_id');
        return view('prodi.index', ['allmahasiswaprodi' => $result, 'kampus' => $kampus]);
        //
    }

    public function allJoinElq() {
        $prodis = Prodi::with('mahasiswas')->get();
        foreach ($prodis as $prodi) {
            echo "<h3>" . $prodi->nama . "</h3>";
            echo "<hr>Mahasiswa: ";
            foreach ($prodi->mahasiswas as $mhs) {
                echo $mhs->nama . ", ";
            }
            echo "<hr>";
        }
    }

    public function create() {
        return view("prodi.create");
    }

    public function store(Request $request) {
        // dump($request);
        // echo $request->nama;

        $this->authorize('create', Prodi::class);

        $validateData = $request->validate([
            'nama' => 'required|min:5|max:20',
            'foto' => 'required|file|image|max:5000',
        ]);
        // dump($validateData);
        // echo $validateData['nama'];

        $ext = $request->foto->getClientOriginalExtension();

        $nama_file = "foto-" . time() . "." . $ext;
        $path = $request->foto->storeAs('public', $nama_file);
        $prodi = new Prodi();
        $prodi->nama = $validateData['nama'];
        $prodi->foto = $nama_file;
        $prodi->save();

        session()->flash('info',"Data prodi $prodi->nama berhasil disimpan ke database");
        return redirect()->route('prodi.create');
    }
}
