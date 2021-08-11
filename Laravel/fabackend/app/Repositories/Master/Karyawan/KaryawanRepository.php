<?php

namespace App\Repositories\Master\Karyawan;

use App\Models\Master\Karyawan as Karyawan;
use Illuminate\Support\Facades\DB;

class KaryawanRepository
{
    public function get($params = NULL)
    {
        $model = new Karyawan();
        
        $model->setPerPage($params['show'] ?? $model->getPerPage());

        $data = $model->select('idk_kode_karyawan',
            'idk_nama_karyawan',
            'idk_alamat',
            'idk_telpon1_karyawan',
            'idk_departement',
            'idk_kantor_lokasi',
            'idk_cabang',
            'idk_jenis_kelamin',
            'idk_telpon1_karyawan',
            'idk_telpon2_karyawan',
            'idk_email_karyawan', 
            'idk_tipe_identitas',
            'idk_nomor_identitas',
            'idk_npwp', 'idk_jabatan', 'idk_departement', 'idk_cabang', 'idk_kantor_lokasi',
            'idk_status_karyawan', 'idk_keterangan',
            'ibk_nama_bank', 'ibk_nomor_rek', 'ibk_nama_pada_rek', 'ibk_kantor_cabang_rek_dibuat',
            'iln_tempat_lahir', 'iln_tanggal_lahir', 'iln_tanggal_mulai_kerja', 'iln_agama',
            'iln_pendidikan_terakhir', 'iln_status_pernikahan',
            'is_active')
            ->when(!empty($params['keyword']), function ($q) use ($params) {
                $keyword = $params['keyword'];

                $q->orWhere('idk_kode_karyawan', 'like', "%{$keyword}%")
                    ->orWhere('idk_nama_karyawan', 'like', "%{$keyword}%");
            })
            ->when(isset($params['is_active']), function ($q) use ($params) {
                $is_active = (boolean) $params['is_active'];
    
                $q->where('is_active', $is_active);
            })
            ->paginate();
        
        return [
            'tableState'=>$params,'data'=>$data
        ];
    }

    public function find($value, $key = 'idk_kode_karyawan')
    {
        $karyawan = Karyawan::where($key, $value)->firstOrFail();

        return $karyawan;
    }

    public function show($code)
    {
        $data = $this->find($code, 'idk_kode_karyawan');
        
        return $data;
    }

    public function list($filter = [])
    {
        $model = new Karyawan();

        return $model->select('idk_kode_karyawan',
            'idk_nama_karyawan',
            'idk_alamat',
            'idk_telpon1_karyawan',
            'idk_departement',
            'idk_kantor_lokasi',
            'idk_cabang',
            'idk_jenis_kelamin',
            'idk_telpon1_karyawan',
            'idk_telpon2_karyawan',
            'idk_email_karyawan', 
            'idk_tipe_identitas',
            'idk_nomor_identitas',
            'idk_npwp', 'idk_jabatan', 'idk_departement', 'idk_cabang', 'idk_kantor_lokasi',
            'idk_status_karyawan', 'idk_keterangan',
            'ibk_nama_bank', 'ibk_nomor_rek', 'ibk_nama_pada_rek', 'ibk_kantor_cabang_rek_dibuat',
            'iln_tempat_lahir', 'iln_tanggal_lahir', 'iln_tanggal_mulai_kerja', 'iln_agama',
            'iln_pendidikan_terakhir', 'iln_status_pernikahan',
            'is_active')
            ->whereIsActive(true)
            ->when(!empty($filter['keyword']), function ($q) use ($filter) {
                $keyword = $filter['keyword'];

                $q->where(function ($q) use ($keyword) {
                    $q->orWhere('idk_kode_karyawan', 'like', "%{$keyword}%")
                        ->orWhere('idk_nama_karyawan', 'like', "%{$keyword}%");
                });
            })
            //->limit($model->getPerPage())
            ->get();
    }
}