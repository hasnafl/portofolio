<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FilterKeyword;
use App\Traits\FormatDates;
use App\Traits\Activeable;
use App\Traits\Deleteable;

class WorkflowActionMaster extends Model
{
    use Activeable, Deleteable, FilterKeyword, FormatDates;

    protected $guarded  = [];
    protected $table = 'fna_mdm_workflow_action_masters';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'string';

    public static function rules()
    {
        $rules = [
            'workflow_action_name'    => 'required'
        ];

        return $rules;
    }
}
