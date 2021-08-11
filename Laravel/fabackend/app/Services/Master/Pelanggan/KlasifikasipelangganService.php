<?php

namespace App\Services\Master\Pelanggan;
use App\Models\Master\Klasifikasipelanggan;
use App\Models\Sequence;
use App\Models\SequenceCompany;
use Att\Responisme\Exceptions\StarterKitException;

class KlasifikasipelangganService
{
    public function save($data, Klasifikasipelanggan $klasifikasipelanggan = null)
    {
        $user_code = auth()->user()->code;

        $validated_data = validate($data, Klasifikasipelanggan::rules($klasifikasipelanggan->id ?? null));

        if (!$klasifikasipelanggan) {
            // $validated_data['activated_at'] = now();
            // $validated_data['activated_by'] = $user_code;
            $validated_data['created_by'] = $user_code;
        }

        $validated_data['updated_at'] = $klasifikasipelanggan ? now() : null;
        $validated_data['updated_by'] = $klasifikasipelanggan ? $user_code : null;
        
        try {
            if ($klasifikasipelanggan) {
                $klasifikasipelanggan->update($validated_data);
            } else {
                $klasifikasipelanggan = Klasifikasipelanggan::create($validated_data);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $klasifikasipelanggan;
    }

    public function create(array $data)
    {
        return $this->save($data);
    }

    public function update(Klasifikasipelanggan $klasifikasipelanggan, array $data)
    {
        return $this->save($data, $klasifikasipelanggan);
    }

    public function delete(Klasifikasipelanggan $klasifikasipelanggan)
    {
        $user_code = auth()->user()->code;
        return $klasifikasipelanggan->update([
            'deleted_at' => now(),
            'is_active' => 0,
            'deleted_by' => $user_code,
        ]);
    }

    public function destroy(Klasifikasipelanggan $klasifikasipelanggan, $code)
    {
        $klasifikasipelanggan = Klasifikasipelanggan::where('id', $code)->delete();
        $klasifikasipelanggan->where('id', $code)->delete();

        return $klasifikasipelanggan;
    }

    public function changeActive(Klasifikasipelanggan $klasifikasipelanggan, array $data)
    {
        try {
            $klasifikasipelanggan->changeActive([
            ]);
        }
        catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $klasifikasipelanggan;
    }

    public function changeDeleted(Klasifikasipelanggan $klasifikasipelanggan, array $data)
    {
        try {
            $klasifikasipelanggan->changeDeleted([
            ]);
        }
        catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $klasifikasipelanggan;
    }

    public function saveExport($data)
    {
        $user_code = auth()->user()->code;

        foreach($data as $row){
            $klasifikasipelanggan['id'] = $row['0'];
            $klasifikasipelanggan['classification_code'] = $row['1'];
            $klasifikasipelanggan['classification_name'] = $row['2'];
            $klasifikasipelanggan['account_akrual_piutang'] = $row['3'];
            $klasifikasipelanggan['account_piutang'] = $row['4'];
            // $klasifikasipelanggan['activated_at'] = now();
            // $klasifikasipelanggan['activated_by'] = $user_code;
            $klasifikasipelanggan['created_by'] = $user_code;

            $klasifikasipelanggan = Klasifikasipelanggan::create($klasifikasipelanggan);
        }

        return true;

    }

    public function get($prefix, $date, $company, $entity = null)
    {
        $data = ['prefix'   => $prefix,
                 'date'     => $date,
                 'company'  => $company,
                 'entity'   => $entity];
        $validated_data = validate($data, Sequence::rules());

        // dd($validated_data);

        $format = $this->get_format($validated_data['prefix'], $validated_data['company']);
        if($format == false)
        {
            return $format;
        }

        $month = date("m", strtotime($validated_data['date']));
        $year = date("Y", strtotime($validated_data['date']));

        $seq_data = Sequence::where('sequence_name', $format->sequence_prefix)
                                ->where('companycode', $validated_data['company'])
                                ->where('finyear', $year)
                                ->where('finmonth', $month);
        if($validated_data['entity'] != null){
            $seq_data = $seq_data->where('entitycode', $validated_data['entity']);
        }else $seq_data = $seq_data->where('entitycode', NULL);
        $seq_data = $seq_data->first();
        // dd($seq_data);
        if(is_null($seq_data)){
            $insert_seq = array("companycode" => $validated_data['company'],
                                "sequence_prefix" => $format->sequence_prefix,
                                "entitycode" => $validated_data['entity'],
                                "sequence_name" => $format->sequence_prefix,
                                "finyear" => $year,
                                "finmonth" => $month,
                                "sequence_length" => $format->sequence_length,
                                "sequence_number" => 0,
                                "increment" => 1,
                                "min_value" => 1,
                                "max_value" => 99999999999,
                                "cur_value" => 2,
                                "cycle" => 0,
                                "is_active" => '1',
                                "created_by" => "0",
                                "updated_by" => "0");
            $insert = Sequence::create($insert_seq);
            $sequence = [
                            "companycode" => $validated_data['company'],
                            "sequence_prefix" => $format->sequence_prefix,
                            "entitycode" => $validated_data['entity'],
                            "year" => $year,
                            "month" => $month,
                            "number" => str_pad('1', $format->sequence_length,"0",STR_PAD_LEFT)
            ];

            $ret =  $sequence[$format->sequence_format1] .
                    $sequence[$format->sequence_format2] . 
                    $sequence[$format->sequence_format3] .
                    $sequence[$format->sequence_format4] .
                    $sequence[$format->sequence_format5] .
                    $sequence[$format->sequence_format6];
        }else{
            $update = Sequence::where('sequence_name', $format->sequence_prefix)
                                ->where('finyear', $year)
                                ->where('finmonth', $month)
                                ->update([  "cur_value" => ($seq_data->cur_value + 1),
                                            "updated_at" => now()]);
            $sequence = [
                            "companycode" => $seq_data->companycode,
                            "sequence_prefix" => $seq_data->sequence_prefix,
                            "entitycode" => $seq_data->entitycode,
                            "year" => $year,
                            "month" => $month,
                            "number" => str_pad($seq_data->cur_value, $seq_data->sequence_length, $seq_data->sequence_number, STR_PAD_LEFT)
            ];
            $ret =  $sequence[$format->sequence_format1] .
                    $sequence[$format->sequence_format2] . 
                    $sequence[$format->sequence_format3] .
                    $sequence[$format->sequence_format4] .
                    $sequence[$format->sequence_format5] .
                    $sequence[$format->sequence_format6];
        }
        return $ret;
    }

    private function get_format($prefix_name, $company)
    {
        $seq_format = SequenceCompany::where('sequence_name', $prefix_name)
                                ->where('companycode', $company)
                                ->first();
        if(is_null($seq_format))
        {
            $seq_format = SequenceCompany::where('sequence_name', $prefix_name)
                                ->where('companycode', null)
                                ->first();
        }
        if(is_null($seq_format))
        {
            return false;
        }
        return $seq_format;
    }
}