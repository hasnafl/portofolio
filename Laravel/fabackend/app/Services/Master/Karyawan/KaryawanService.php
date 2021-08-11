<?php

namespace App\Services\Master\Karyawan;
use App\Models\Master\Karyawan;
use Att\Responisme\Exceptions\StarterKitException;

class KaryawanService
{
    public function save($data, Karyawan $karyawan = null)
    {
        $user_code = auth()->user()->code;

        $validated_data = validate($data, Karyawan::rules($karyawan->idk_kode_karyawan ?? null));

        if (!$karyawan) {
            $validated_data['activated_at'] = now();
            // $validated_data['activated_by'] = $user_code;
            $validated_data['created_by'] = $user_code;
        }

        $validated_data['updated_at'] = $karyawan ? now() : null;
        $validated_data['updated_by'] = $karyawan ? $user_code : null;
        
        try {
            if ($karyawan) {
                $karyawan->update($validated_data);
            } else {
                $karyawan = Karyawan::create($validated_data);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $karyawan;
    }

    public function create(array $data)
    {
        return $this->save($data);
    }

    public function update(Karyawan $karyawan, array $data)
    {
        return $this->save($data, $karyawan);
    }

    public function delete(Karyawan $karyawan)
    {
        $user_code = auth()->user()->code;
        return $karyawan->update([
            'deleted_at' => now(),
            'is_active' => 0,
            'deleted_by' => $user_code,
        ]);
    }

    public function destroy(Karyawan $karyawan, $code)
    {
        $karyawan = Karyawan::where('idk_kode_karyawan', $code)->delete();
        $karyawan->where('idk_kode_karyawan', $code)->delete();

        return $karyawan;
    }

    public function changeActive(Karyawan $karyawan, array $data)
    {
        try {
            $karyawan->changeActive([
            ]);
        }
        catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $karyawan;
    }

    public function changeDeleted(Karyawan $karyawan, array $data)
    {
        try {
            $karyawan->changeDeleted([
            ]);
        }
        catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $karyawan;
    }

    public function saveExport($data)
    {
        $user_code = auth()->user()->code;

        foreach($data as $row){
            $karyawan['idk_kode_karyawan'] = $row['0'];
            $karyawan['idk_nama_karyawan'] = $row['1'];
            $karyawan['idk_alamat'] = $row['2'];
            $karyawan['idk_jenis_kelamin'] = $row['3'];
            $karyawan['idk_telpon1_karyawan'] = $row['4'];
            $karyawan['activated_at'] = now();
            // $karyawan['activated_by'] = $user_code;
            $karyawan['created_by'] = $user_code;

            $karyawan = Karyawan::create($karyawan);
        }

        return true;

    }
}