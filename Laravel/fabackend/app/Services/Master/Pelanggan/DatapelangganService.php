<?php

namespace App\Services\Master\Pelanggan;
use App\Models\Master\Datapelanggan;
use Att\Responisme\Exceptions\StarterKitException;

class DatapelangganService
{
    public function save($data, Datapelanggan $datapelanggan = null)
    {
        $user_code = auth()->user()->code;

        $validated_data = validate($data, Datapelanggan::rules($datapelanggan->id ?? null));

        if (!$datapelanggan) {
            // $validated_data['activated_at'] = now();
            // $validated_data['activated_by'] = $user_code;
            $validated_data['created_by'] = $user_code;
        }

        $validated_data['updated_at'] = $datapelanggan ? now() : null;
        $validated_data['updated_by'] = $datapelanggan ? $user_code : null;
        
        try {
            if ($datapelanggan) {
                $datapelanggan->update($validated_data);
            } else {
                $datapelanggan = Datapelanggan::create($validated_data);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $datapelanggan;
    }

    public function create(array $data)
    {
        return $this->save($data);
    }

    public function update(Datapelanggan $datapelanggan, array $data)
    {
        return $this->save($data, $datapelanggan);
    }

    public function delete(Datapelanggan $datapelanggan)
    {
        $user_code = auth()->user()->code;
        return $datapelanggan->update([
            'deleted_at' => now(),
            'is_active' => 0,
            'deleted_by' => $user_code,
        ]);
    }

    public function destroy(Datapelanggan $datapelanggan, $code)
    {
        $datapelanggan = Datapelanggan::where('id', $code)->delete();
        $datapelanggan->where('id', $code)->delete();

        return $datapelanggan;
    }

    public function changeActive(Datapelanggan $datapelanggan, array $data)
    {
        try {
            $datapelanggan->changeActive([
            ]);
        }
        catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $datapelanggan;
    }

    public function changeDeleted(Datapelanggan $datapelanggan, array $data)
    {
        try {
            $datapelanggan->changeDeleted([
            ]);
        }
        catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $datapelanggan;
    }

    public function saveExport($data)
    {
        $user_code = auth()->user()->code;

        foreach($data as $row){
            $datapelanggan['id'] = $row['0'];
            $datapelanggan['customer_code'] = $row['1'];
            $datapelanggan['customer_name'] = $row['2'];
            $datapelanggan['customer_branch'] = $row['3'];
            $datapelanggan['customer_classification'] = $row['4'];
            // $datapelanggan['activated_at'] = now();
            // $datapelanggan['activated_by'] = $user_code;
            $datapelanggan['created_by'] = $user_code;

            $datapelanggan = Datapelanggan::create($datapelanggan);
        }

        return true;

    }
}