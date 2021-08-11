<?php

namespace App\Repositories\Master\Workflow;

use App\Models\Master\WorkflowStateMaster as WorkflowStateMaster;
use Illuminate\Support\Facades\DB;

class WorkflowStateRepository
{
    public function get($params = NULL)
    {
        $model = new WorkflowStateMaster();
        
        $model->setPerPage($params['show'] ?? $model->getPerPage());

        $data = $model->select('id',
            'workflow_state_name',
            'style',
            'is_active')
            ->when(!empty($params['keyword']), function ($q) use ($params) {
                $keyword = $params['keyword'];

                $q->orWhere('workflow_state_name', 'like', "%{$keyword}%");
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
        $workflowstate = WorkflowStateMaster::where($key, $value)->firstOrFail();

        return $workflowstate;
    }

    public function show($code)
    {
        $data = $this->find($code, 'id');
        
        return $data;
    }

    public function list($filter = [])
    {
        $model = new WorkflowStateMaster();

        return $model->select('id',
            'workflow_state_name',
            'style',
            'is_active')
            ->whereIsActive(true)
            ->when(!empty($filter['keyword']), function ($q) use ($filter) {
                $keyword = $filter['keyword'];

                $q->where(function ($q) use ($keyword) {
                    $q->orWhere('workflow_state_name', 'like', "%{$keyword}%");
                });
            })
            //->limit($model->getPerPage())
            ->get();
    }
}