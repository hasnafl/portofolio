<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FilterKeyword;
use App\Traits\FormatDates;
use App\Traits\Activeable;
use App\Traits\Deleteable;

class Karyawan extends Model
{
    use Activeable, Deleteable, FilterKeyword, FormatDates;

    protected $guarded  = [];
    protected $table = 'fna_mdm_employees';
    protected $primaryKey = 'idk_kode_karyawan';
    protected $keyType = 'string';

    public static function rules()
    {
        $rules = [
            'idk_kode_karyawan'    => 'required',
            'idk_nama_karyawan'      => 'required',
            'idk_jenis_kelamin' => 'required',
            'idk_telpon1_karyawan' => 'required|nullable',
            'idk_telpon2_karyawan' => 'nullable',
            'idk_alamat' => 'nullable',
            'idk_email_karyawan' => 'nullable',
            'idk_cabang' => 'nullable',
            'idk_departement' => 'nullable',
            'idk_kantor_lokasi' => 'nullable',
            'idk_tipe_identitas' => 'nullable',
            'idk_nomor_identitas' => 'nullable',
            'idk_status_karyawan' => 'nullable',
            'idk_npwp' => 'nullable',
            'idk_jabatan' => 'nullable',
            'idk_keterangan' => 'nullable',
            'ibk_nama_bank' => 'nullable',
            'ibk_nomor_rek' => 'nullable',
            'ibk_nama_pada_rek' => 'nullable',
            'ibk_kantor_cabang_rek_dibuat' => 'nullable',
            'iln_tempat_lahir' => 'nullable',
            'iln_tanggal_lahir' => 'nullable',
            'iln_tanggal_mulai_kerja' => 'nullable',
            'iln_agama' => 'nullable',
            'iln_pendidikan_terakhir' => 'nullable',
            'iln_status_pernikahan' => 'nullable',
        ];

        return $rules;
    }

    // public function isActive()
    // {
    //     return $this->is_active;
    // }

}
