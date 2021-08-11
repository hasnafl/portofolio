<?php

namespace App\Services\Master\Workflow;
use App\Models\Master\WorkflowStateMaster;
use Att\Responisme\Exceptions\StarterKitException;

class WorkflowStateService
{
    public function save($data, WorkflowStateMaster $workflowstate = null)
    {
        $user_code = auth()->user()->code;

        $validated_data = validate($data, WorkflowStateMaster::rules($workflowstate->id ?? null));

        if (!$workflowstate) {
            $validated_data['activated_at'] = now();
            // $validated_data['activated_by'] = $user_code;
            $validated_data['created_by'] = $user_code;
        }

        $validated_data['updated_at'] = $workflowstate ? now() : null;
        $validated_data['updated_by'] = $workflowstate ? $user_code : null;
        
        try {
            if ($workflowstate) {
                $workflowstate->update($validated_data);
            } else {
                $workflowstate = WorkflowStateMaster::create($validated_data);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $workflowstate;
    }

    public function create(array $data)
    {
        return $this->save($data);
    }

    public function update(WorkflowStateMaster $workflowstate, array $data)
    {
        return $this->save($data, $workflowstate);
    }

    public function delete(WorkflowStateMaster $workflowstate)
    {
        $user_code = auth()->user()->code;
        return $workflowstate->update([
            'deleted_at' => now(),
            'is_active' => 0,
            'deleted_by' => $user_code,
        ]);
    }

    public function destroy(WorkflowStateMaster $workflowstate, $code)
    {
        $workflowstate = WorkflowStateMaster::where('id', $code)->delete();
        $workflowstate->where('id', $code)->delete();

        return $workflowstate;
    }

    public function changeActive(WorkflowStateMaster $workflowstate, array $data)
    {
        try {
            $workflowstate->changeActive([
            ]);
        }
        catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $workflowstate;
    }

    public function changeDeleted(WorkflowStateMaster $workflowstate, array $data)
    {
        try {
            $workflowstate->changeDeleted([
            ]);
        }
        catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $workflowstate;
    }

    public function saveExport($data)
    {
        $user_code = auth()->user()->code;

        foreach($data as $row){
            $workflowstate['id'] = $row['0'];
            $workflowstate['workflow_state_name'] = $row['1'];
            $workflowstate['style'] = $row['2'];
            $workflowstate['activated_at'] = now();
            // $workflowstate['activated_by'] = $user_code;
            $workflowstate['created_by'] = $user_code;

            $workflowstate = WorkflowStateMaster::create($workflowstate);
        }

        return true;

    }
}