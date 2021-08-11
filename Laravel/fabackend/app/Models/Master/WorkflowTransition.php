<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class WorkflowTransition extends Model
{
    protected $table = 'fna_mdm_workflow_transitions';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'string';
    protected $fillable = ['parentname, state, action, next_state, allow_self_approval, condition, allowed'];

    public static function rules()
    {
        $rules = [
            'parentname'    => 'required',
            'state'      => 'required',
            'action' => 'nullable',
            'next_state' => 'nullable',
            'allow_self_approval' => 'nullable',
            'condition' => 'nullable',
            'allowed' => 'nullable'
        ];

        return $rules;
    }
}
