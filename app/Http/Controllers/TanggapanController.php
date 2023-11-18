<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Masyarakat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pengaduan_masyarakat;

class TanggapanController extends Controller
{
    public function index()
    {
        $dataPengaduan = DB::table('pengaduan_masyarakat')
        ->join('masyarakat', 'masyarakat.id', '=', 'pengaduan_masyarakat.masyarakat_id')
        ->join('users', 'users.id', '=', 'masyarakat.user_id')
        ->select('users.nama', 'masyarakat.user_id', 'pengaduan_masyarakat.*')
        ->get();
        return view('Admin.data_pengaduan', compact('dataPengaduan'));
    }
    public function data_user()
    {
        $data_user = DB::table('masyarakat')
        ->join('users', 'users.id', '=', 'masyarakat.user_id')
        ->select('users.nama', 'users.email','masyarakat.*')
        ->get();
        return view('Admin.data_user', compact('data_user'));
    }
    public function show(string $id)
    {
        $rs = Pengaduan_masyarakat::find($id);
        $dataPengaduan = DB::table('pengaduan_masyarakat')
        ->join('masyarakat', 'masyarakat.id', '=', 'pengaduan_masyarakat.masyarakat_id')
        ->join('users', 'users.id', '=', 'masyarakat.user_id')
        ->select('users.nama', 'masyarakat.user_id', 'pengaduan_masyarakat.*')
        ->where('pengaduan_masyarakat.id', $id)
        ->first();
        // var_dump($dataPengaduan);
        // die;
        return view('Admin.form_tanggapan',compact('rs','dataPengaduan'));
    }

    public function update(Request $request, string $id){
      
        DB::table('pengaduan_masyarakat')
        ->where('id', $id)
        ->update(['status' => $request->input('status')]);
        // Ngambial user_id
        $dataPengaduan = DB::table('pengaduan_masyarakat')
        ->join('masyarakat', 'masyarakat.id', '=', 'pengaduan_masyarakat.masyarakat_id')
        ->join('users', 'users.id', '=', 'masyarakat.user_id')
        ->select('masyarakat.user_id')
        ->where('pengaduan_masyarakat.id', $id)
        ->first();
         $user_id = $dataPengaduan->user_id;
        // Ngambil pengaduan_id
        $rs = Pengaduan_masyarakat::find($id);
        $pengaduan_id = $rs['id'];
        if ($request->input('keterangan')) {
        DB::table('tanggapan')->insert(
            [    
                 'user_id'=>$user_id,
                 'pengaduan_id'=>$pengaduan_id,
                 'tgl_tanggapan'=> now(),
                 'keterangan'=> $request->input('keterangan'),
            ]);
        }
        // var_dump($data);
        // die;
        return redirect('/data_pengaduan')->with('success','Status Pengaduan Berhasil Diubah');
    }
}
