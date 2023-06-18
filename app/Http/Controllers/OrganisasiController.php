<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use App\Models\User;
use App\Models\OrganisasiDetail;
use Illuminate\Http\Request;
use App\Charts\OrganisasiLineChart;

class OrganisasiController extends Controller
{
    public function index()
    {
        $title = "Data Organisasi";
        $organisasis = Organisasi::orderBy('id', 'asc')->get();
        return view('organisasis.index', compact('organisasis', 'title'));
    }

    public function create()
    {
        $title = "Tambah Data Organisasi";
        $managers = User::where('position', '1')->orderBy('id', 'asc')->get();
        return view('organisasis.create', compact('title', 'managers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_organisasi' => 'required',
        ]);

        $organisasi = new Organisasi;
        $organisasi->nama_organisasi = $request->nama_organisasi;
        $organisasi->ruangan = $request->ruangan;
        $organisasi->nama_kordinator = $request->nama_kordinator;
        $organisasi->tanggal = $request->tanggal;
        $organisasi->save();

        $organisasiId = $organisasi->id;

        for ($i = 1; $i <= $request->jml; $i++) {
            $details = new OrganisasiDetail;
            $details->id_kordinator = $organisasiId;
            $details->nim = $request['nim' . $i];
            $details->jabatan = $request['jabatan' . $i];
            $details->jumlah_mahasiswa = $request['jumlah_mahasiswa' . $i];
            $details->save();
        }

        return redirect()->route('organisasis.index')->with('success', 'Organisasi has been created successfully.');
    }

    public function show(Organisasi $organisasi)
    {
        return view('organisasis.show', compact('organisasi'));
    }

    public function edit(Organisasi $organisasi)
    {
        $title = "Edit Data Organisasi";
        $managers = User::where('position', '1')->orderBy('id', 'asc')->get();
        return view('organisasis.edit', compact('title', 'organisasi', 'managers'));
    }

    public function update(Request $request, Organisasi $organisasi)
    {
        $request->validate([
            'nama_organisasi' => 'required',
            'ruangan' => 'required',
            'nama_kordinator' => 'required',
            'tanggal' => 'required',
        ]);

        $organisasi->nama_organisasi = $request->nama_organisasi;
        $organisasi->ruangan = $request->ruangan;
        $organisasi->nama_kordinator = $request->nama_kordinator;
        $organisasi->tanggal = $request->tanggal;
        $organisasi->save();

        return redirect()->route('organisasis.index')->with('success', 'Organisasi has been updated successfully.');
    }

    public function destroy(Organisasi $organisasi)
    {
        $organisasi->delete();
        return redirect()->route('organisasis.index')->with('success', 'Organisasi has been deleted successfully.');
    }

    public function chartLine()
    {
        $api = route('organisasi.chartLineAjax');

        $chart = new OrganisasiLineChart;
        $chart->labels(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'])->load($api);
        $title = "Chart Ajax";
        return view('home', compact('chart', 'title'));
    }

    public function chartLineAjax(Request $request)
    {
        $year = $request->has('year') ? $request->year : date('Y');
        $organisasis = Organisasi::select(\DB::raw("COUNT(*) as count"))
            ->whereYear('tanggal', $year)
            ->groupBy(\DB::raw("MONTH(tanggal)"))
            ->pluck('count');

        $chart = new OrganisasiLineChart;

        $chart->dataset('Organisasi Mahasiswa Chart', 'bar', $organisasis)->options([
            'fill' => 'true',
            'borderColor' => '#51C1C0'
        ]);

        return $chart->api();
    }
}
