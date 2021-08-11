<?php

namespace App\Repositories\Master\Workflow;

use App\Models\Master\Workflow as Workflow;
use App\Models\Master\WorkflowDocumentState as WorkflowDocumentState;
use App\Models\Master\TmpDocstate as TmpDocstate;
use App\Models\Master\TmpTransition as TmpTransition;
use App\Models\Master\WorkflowTransition as WorkflowTransition;
use App\Models\Master\RolesWorkflow as RolesWorkflow;
use Illuminate\Support\Facades\DB;

class WorkflowRepository
{
    public function get($params = NULL)
    {
        $model = new Workflow();
        
        $model->setPerPage($params['show'] ?? $model->getPerPage());

        $data = $model->select('id',
            'workflow_name',
            'document_type',
            'workflow_state_field',
            'is_active')
            ->when(!empty($params['keyword']), function ($q) use ($params) {
                $keyword = $params['keyword'];

                $q->orWhere('document_type', 'like', "%{$keyword}%")
                    ->orWhere('workflow_name', 'like', "%{$keyword}%");
            })
            ->when(isset($params['is_active']), function ($q) use ($params) {
                $is_active = (boolean) $params['is_active'];
    
                $q->where('is_active', $is_active);
            })
            ->paginate();
        
        return [
            'tableState'=>$params,'data'=>$data
        ];
    }

    public function getdocstate($params = NULL)
    {
        $model = new WorkflowDocumentState();
        
        $model->setPerPage($params['show'] ?? $model->getPerPage());

        $data = $model->select('id',
            'parentname',
            'state',
            'docstatus',
            'allow_edit',
            'is_active')
            ->when(!empty($params['keyword']), function ($q) use ($params) {
                $keyword = $params['keyword'];

                $q->orWhere('parentname', '=', "{$keyword}");
            })
            ->when(isset($params['is_active']), function ($q) use ($params) {
                $is_active = (boolean) $params['is_active'];
    
                $q->where('is_active', $is_active);
            })
            ->paginate();
        
        return [
            'tableState'=>$params,'data'=>$data
        ];
    }

    public function gettmpdocstate($params = NULL)
    {
        $model = new TmpDocstate();
        
        $model->setPerPage($params['show'] ?? $model->getPerPage());

        $data = $model->select('id',
            'parentname',
            'state',
            'docstatus',
            'allow_edit',
            'is_active')
            ->when(!empty($params['keyword']), function ($q) use ($params) {
                $keyword = $params['keyword'];

                $q->orWhere('parentname', '=', "{$keyword}");
            })
            ->when(isset($params['is_active']), function ($q) use ($params) {
                $is_active = (boolean) $params['is_active'];
    
                $q->where('is_active', $is_active);
            })
            ->paginate();
        
        return [
            'tableState'=>$params,'data'=>$data
        ];
    }

    public function gettransition($params = NULL)
    {
        $model = new WorkflowTransition();
        
        $model->setPerPage($params['show'] ?? $model->getPerPage());

        $data = $model->select('id',
            'parentname',
            'state',
            'action',
            'next_state',
            'condition',
            'allow_self_approval',
            'allowed',
            'is_active')
            ->when(!empty($params['keyword']), function ($q) use ($params) {
                $keyword = $params['keyword'];

                $q->orWhere('parentname', '=', "{$keyword}");
            })
            ->when(isset($params['is_active']), function ($q) use ($params) {
                $is_active = (boolean) $params['is_active'];
    
                $q->where('is_active', $is_active);
            })
            ->paginate();
        
        return [
            'tableState'=>$params,'data'=>$data
        ];
    }

    public function gettmptransition($params = NULL)
    {
        $model = new TmpTransition();
        
        $model->setPerPage($params['show'] ?? $model->getPerPage());

        $data = $model->select('id',
            'parentname',
            'state',
            'action',
            'next_state',
            'condition',
            'allow_self_approval',
            'allowed',
            'is_active')
            ->when(!empty($params['keyword']), function ($q) use ($params) {
                $keyword = $params['keyword'];

                $q->orWhere('parentname', '=', "{$keyword}");
            })
            ->when(isset($params['is_active']), function ($q) use ($params) {
                $is_active = (boolean) $params['is_active'];
    
                $q->where('is_active', $is_active);
            })
            ->paginate();
        
        return [
            'tableState'=>$params,'data'=>$data
        ];
    }

    public function find($value, $key = 'id')
    {
        $workflow = Workflow::where($key, $value)->firstOrFail();

        return $workflow;
    }

    public function findtransition($value, $key = 'id')
    {
        $workflowtransition = WorkflowTransition::where($key, $value)->firstOrFail();

        return $workflowtransition;
    }

    public function show($code)
    {
        $data = $this->find($code, 'id');
        
        return $data;
    }

    public function showtransition($code)
    {
        $data = $this->findtransition($code, 'id');
        
        return $data;
    }

    public function list($filter = [])
    {
        $model = new Workflow();

        return $model->select('id',
            'workflow_name',
            'document_type',
            'workflow_state_field',
            'is_active')
            ->whereIsActive(true)
            ->when(!empty($filter['keyword']), function ($q) use ($filter) {
                $keyword = $filter['keyword'];

                $q->where(function ($q) use ($keyword) {
                    $q->orWhere('document_type', 'like', "%{$keyword}%")
                        ->orWhere('workflow_name', 'like', "%{$keyword}%");
                });
            })
            //->limit($model->getPerPage())
            ->get();
    }

    public function getroles($filter = [])
    {
        $model = new RolesWorkflow();

        return $model->select('id',
            'name',
            'description',
            'is_active')
            ->whereIsActive(true)
            ->when(!empty($filter['keyword']), function ($q) use ($filter) {
                $keyword = $filter['keyword'];

                $q->where(function ($q) use ($keyword) {
                    $q->orWhere('name', 'like', "%{$keyword}%");
                });
            })
            //->limit($model->getPerPage())
            ->get();
    }
}