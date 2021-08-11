<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FilterKeyword;
use App\Traits\FormatDates;
use App\Traits\Activeable;
use App\Traits\Deleteable;

class Workflow extends Model
{
    use Activeable, Deleteable, FilterKeyword, FormatDates;

    protected $guarded  = [];
    protected $table = 'fna_mdm_workflows';
    protected $primaryKey = 'workflow_name';
    // public $incrementing = true;
    protected $keyType = 'string';

    public static function rules()
    {
        $rules = [
            'id' => 'required',
            'workflow_name'    => 'required',
            'document_type'      => 'required'
        ];

        return $rules;
    }

    // public function workflowdocstate()
    // {
    //     return $this->hasMany('WorkflowDocumentState', 'parentname');
    // }
}

// class WorkflowDocumentState extends Eloquent
// {
//     protected $table = 'fna_mdm_workflow_document_states';

//     protected $hidden = array('state', 'docstatus', 'allow_edit');

//     public function something()
//     {
//         return $this->belongsTo('Workflow', 'workflow_parentcode');
//     }
// }
