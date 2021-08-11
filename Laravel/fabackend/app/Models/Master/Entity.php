<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FilterKeyword;
use App\Traits\FormatDates;
use App\Traits\Activeable;
use App\Traits\Deleteable;

class Entity extends Model
{
    use Activeable, Deleteable, FilterKeyword, FormatDates;
    
    protected $guarded  = [];
    protected $table = 'fna_mdm_entity';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'string';

    public static function rules()
    {
        $rules = [
            'entitycode'    => 'required|string',
            'entityname'      => 'required|string',
            'entityaddress'   => 'required|nullable',
            'companycode'   => 'required|nullable',
            'entityphone' => 'required|nullable',            
            'entitycontactperson' => 'required|nullable'            

        ];

        return $rules;
    }
}
