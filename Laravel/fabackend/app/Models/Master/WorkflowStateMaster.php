<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FilterKeyword;
use App\Traits\FormatDates;
use App\Traits\Activeable;
use App\Traits\Deleteable;

class WorkflowStateMaster extends Model
{
    use Activeable, Deleteable, FilterKeyword, FormatDates;

    protected $guarded  = [];
    protected $table = 'fna_mdm_workflow_state_masters';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'string';

    public static function rules()
    {
        $rules = [
            'workflow_state_name'    => 'required',
            'style'      => 'nullable',
        ];

        return $rules;
    }
}
