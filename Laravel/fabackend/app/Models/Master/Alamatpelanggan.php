<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FilterKeyword;
use App\Traits\FormatDates;
use App\Traits\Activeable;
use App\Traits\Deleteable;

class Alamatpelanggan extends Model
{
    use Activeable, Deleteable, FilterKeyword, FormatDates;

    protected $guarded  = [];
    protected $table = 'fna_mdm_customers_address';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'string';

    public static function rules()
    {
        $rules = [
            'address_customercode'    => 'nullable',
            'address_label'      => 'required',
            'address_detail' => 'nullable',
            'address_ismainaddress' => 'nullable',
            'description' => 'nullable'
        ];

        return $rules;
    }

    // public function isActive()
    // {
    //     return $this->is_active;
    // }

}
