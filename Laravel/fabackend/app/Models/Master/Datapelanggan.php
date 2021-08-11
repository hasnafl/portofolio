<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FilterKeyword;
use App\Traits\FormatDates;
use App\Traits\Activeable;
use App\Traits\Deleteable;

class Datapelanggan extends Model
{
    use Activeable, Deleteable, FilterKeyword, FormatDates;

    protected $guarded  = [];
    protected $table = 'fna_mdm_customers';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'string';

    public static function rules()
    {
        $rules = [
            'customer_code'    => 'nullable',
            'customer_name'      => 'required',
            'customer_branch' => 'nullable',
            'customer_classification' => 'nullable',
            'customer_phone1' => 'nullable'
        ];

        return $rules;
    }

    // public function isActive()
    // {
    //     return $this->is_active;
    // }

}
