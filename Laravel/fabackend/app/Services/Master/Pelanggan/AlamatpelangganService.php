<?php

namespace App\Services\Master\Pelanggan;
use App\Models\Master\Alamatpelanggan;
use Att\Responisme\Exceptions\StarterKitException;

class AlamatpelangganService
{
    public function save($data, Alamatpelanggan $alamatpelanggan = null)
    {
        $user_code = auth()->user()->code;

        $validated_data = validate($data, Alamatpelanggan::rules($alamatpelanggan->id ?? null));

        if (!$alamatpelanggan) {
            // $validated_data['activated_at'] = now();
            // $validated_data['activated_by'] = $user_code;
            $validated_data['created_by'] = $user_code;
        }

        $validated_data['updated_at'] = $alamatpelanggan ? now() : null;
        $validated_data['updated_by'] = $alamatpelanggan ? $user_code : null;
        
        try {
            if ($alamatpelanggan) {
                $alamatpelanggan->update($validated_data);
            } else {
                $alamatpelanggan = Alamatpelanggan::create($validated_data);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $alamatpelanggan;
    }

    public function create(array $data)
    {
        return $this->save($data);
    }

    public function update(Alamatpelanggan $alamatpelanggan, array $data)
    {
        return $this->save($data, $alamatpelanggan);
    }

    public function delete(Alamatpelanggan $alamatpelanggan)
    {
        $user_code = auth()->user()->code;
        return $alamatpelanggan->update([
            'deleted_at' => now(),
            'is_active' => 0,
            'deleted_by' => $user_code,
        ]);
    }

    public function destroy(Alamatpelanggan $alamatpelanggan, $code)
    {
        $alamatpelanggan = Alamatpelanggan::where('id', $code)->delete();
        $alamatpelanggan->where('id', $code)->delete();

        return $alamatpelanggan;
    }

    public function changeActive(Alamatpelanggan $alamatpelanggan, array $data)
    {
        try {
            $alamatpelanggan->changeActive([
            ]);
        }
        catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $alamatpelanggan;
    }

    public function changeDeleted(Alamatpelanggan $alamatpelanggan, array $data)
    {
        try {
            $alamatpelanggan->changeDeleted([
            ]);
        }
        catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $alamatpelanggan;
    }

    public function saveExport($data)
    {
        $user_code = auth()->user()->code;

        foreach($data as $row){
            $alamatpelanggan['id'] = $row['0'];
            $alamatpelanggan['address_customercode'] = $row['1'];
            $alamatpelanggan['address_label'] = $row['2'];
            $alamatpelanggan['address_detail'] = $row['3'];
            $alamatpelanggan['description'] = $row['4'];
            // $alamatpelanggan['activated_at'] = now();
            // $alamatpelanggan['activated_by'] = $user_code;
            $alamatpelanggan['created_by'] = $user_code;

            $alamatpelanggan = Alamatpelanggan::create($alamatpelanggan);
        }

        return true;

    }
}