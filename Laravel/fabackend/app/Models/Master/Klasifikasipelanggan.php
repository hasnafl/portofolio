<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FilterKeyword;
use App\Traits\FormatDates;
use App\Traits\Activeable;
use App\Traits\Deleteable;

class Klasifikasipelanggan extends Model
{
    use Activeable, Deleteable, FilterKeyword, FormatDates;

    protected $guarded  = [];
    protected $table = 'fna_mdm_customers_classification';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'string';

    public static function rules()
    {
        $rules = [
            'classification_code'    => 'nullable',
            'classification_name'      => 'required',
            'account_akrual_piutang' => 'nullable',
            'account_piutang' => 'nullable',
            'account_downpayment' => 'nullable',
            'description' => 'nullable'
        ];

        return $rules;
    }

    // public function isActive()
    // {
    //     return $this->is_active;
    // }

}
