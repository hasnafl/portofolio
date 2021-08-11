<?php

namespace App\Services\Master\Workflow;
use App\Models\Master\WorkflowActionMaster;
use Att\Responisme\Exceptions\StarterKitException;

class WorkflowActionService
{
    public function save($data, WorkflowActionMaster $workflowaction = null)
    {
        $user_code = auth()->user()->code;

        $validated_data = validate($data, WorkflowActionMaster::rules($workflowaction->id ?? null));

        if (!$workflowaction) {
            $validated_data['activated_at'] = now();
            // $validated_data['activated_by'] = $user_code;
            $validated_data['created_by'] = $user_code;
        }

        $validated_data['updated_at'] = $workflowaction ? now() : null;
        $validated_data['updated_by'] = $workflowaction ? $user_code : null;
        
        try {
            if ($workflowaction) {
                $workflowaction->update($validated_data);
            } else {
                $workflowaction = WorkflowActionMaster::create($validated_data);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $workflowaction;
    }

    public function create(array $data)
    {
        return $this->save($data);
    }

    public function update(WorkflowActionMaster $workflowaction, array $data)
    {
        return $this->save($data, $workflowaction);
    }

    public function delete(WorkflowActionMaster $workflowaction)
    {
        $user_code = auth()->user()->code;
        return $workflowaction->update([
            'deleted_at' => now(),
            'is_active' => 0,
            'deleted_by' => $user_code,
        ]);
    }

    public function destroy(WorkflowActionMaster $workflowaction, $code)
    {
        $workflowaction = WorkflowActionMaster::where('id', $code)->delete();
        $workflowaction->where('id', $code)->delete();

        return $workflowaction;
    }

    public function changeActive(WorkflowActionMaster $workflowaction, array $data)
    {
        try {
            $workflowaction->changeActive([
            ]);
        }
        catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $workflowaction;
    }

    public function changeDeleted(WorkflowActionMaster $workflowaction, array $data)
    {
        try {
            $workflowaction->changeDeleted([
            ]);
        }
        catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $workflowaction;
    }

    public function saveExport($data)
    {
        $user_code = auth()->user()->code;

        foreach($data as $row){
            $workflowaction['id'] = $row['0'];
            $workflowaction['workflow_action_name'] = $row['1'];
            $workflowaction['activated_at'] = now();
            // $workflowaction['activated_by'] = $user_code;
            $workflowaction['created_by'] = $user_code;

            $workflowaction = WorkflowActionMaster::create($workflowaction);
        }

        return true;

    }
}