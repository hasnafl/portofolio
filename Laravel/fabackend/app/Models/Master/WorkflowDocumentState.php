<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class WorkflowDocumentState extends Model
{
    protected $table = 'fna_mdm_workflow_document_states';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'string';
    protected $fillable = ['parentname, state, docstatus, allow_edit'];

    public static function rules()
    {
        $rules = [
            'parentname'    => 'required',
            'state'      => 'required',
            'docstatus' => 'nullable',
            'allow_edit' => 'nullable'
        ];

        return $rules;
    }

    // protected $hidden = array('state', 'docstatus', 'allow_edit');

    // public function workflow()
    // {
    //     return $this->belongsTo('Workflow', 'workflow_parentcode');
    // }
}
