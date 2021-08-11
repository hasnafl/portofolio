<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class TmpDocstate extends Model
{
    protected $table = 'fna_mdm_tmpdocstates';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'string';
    protected $fillable = ['parentname, state, docstatus, allow_edit'];

    public static function rules()
    {
        $rules = [
            'parentname'    => 'required',
            'state'      => 'required',
            'docstatus' => 'required',
            'allow_edit' => 'required'
        ];

        return $rules;
    }

}
