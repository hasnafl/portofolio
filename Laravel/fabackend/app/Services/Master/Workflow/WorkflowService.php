<?php

namespace App\Services\Master\Workflow;
use App\Models\Master\Workflow;
use App\Models\Master\WorkflowDocumentState;
use App\Models\Master\TmpDocstate;
use App\Models\Master\TmpWorkflow;
use App\Models\Master\TmpTransition;
use App\Models\Master\WorkflowTransition;
use Att\Responisme\Exceptions\StarterKitException;

class WorkflowService
{
    public function save($data, Workflow $workflow = null, TmpDocstate $tmpdocstate = null, WorkflowDocumentState $workflowdocumentstate = null, TmpTransition $tmptransition = null, WorkflowTransition $workflowtransition = null)
    {
        $user_code = auth()->user()->code;

        $validated_data = validate($data, Workflow::rules($workflow->id ?? null));

        if (!$workflow) {
            $validated_data['activated_at'] = now();
            $validated_data['activated_by'] = $user_code;
            $validated_data['created_by'] = $user_code;
        }

        $validated_data['updated_at'] = $workflow ? now() : null;
        $validated_data['updated_by'] = $workflow ? $user_code : null;
        
        try {
            if ($workflow) {
                $workflow->update($validated_data);
            } else {
                $workflow = Workflow::create($validated_data);

                $tmpdocstate = TmpDocstate::where('parentname', '=', $workflow->id)->get();
                foreach ($tmpdocstate as $docstate) {
                    $workflowdocumentstate = new WorkflowDocumentState;
                    $workflowdocumentstate->parentname = $docstate->parentname;
                    $workflowdocumentstate->state = $docstate->state;
                    $workflowdocumentstate->docstatus = $docstate->docstatus;
                    $workflowdocumentstate->allow_edit = $docstate->allow_edit;
                    $workflowdocumentstate->save();
                }

                $tmptransition = TmpTransition::where('parentname', '=', $workflow->id)->get();
                foreach ($tmptransition as $transition) {
                    $workflowtransition = new WorkflowTransition;
                    $workflowtransition->parentname = $transition->parentname;
                    $workflowtransition->state = $transition->state;
                    $workflowtransition->action = $transition->action;
                    $workflowtransition->next_state = $transition->next_state;
                    $workflowtransition->condition = $transition->condition;
                    $workflowtransition->allow_self_approval = $transition->allow_self_approval;
                    $workflowtransition->allowed = $transition->allowed;
                    $workflowtransition->save();
                }

            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $workflow;
    }

    public function savedocstate($data, TmpDocstate $tmpdocstate = null)
    {
        $user_code = auth()->user()->code;

        $validated_data = validate($data, TmpDocstate::rules($tmpdocstate->id ?? null));

        if (!$tmpdocstate) {
            $validated_data['activated_at'] = now();
            $validated_data['activated_by'] = $user_code;
            $validated_data['created_by'] = $user_code;
        }

        $validated_data['updated_at'] = $tmpdocstate ? now() : null;
        $validated_data['updated_by'] = $tmpdocstate ? $user_code : null;
        
        try {
            if ($tmpdocstate) {
                $tmpdocstate->update($validated_data);
            } else {
                $tmpdocstate = new TmpDocstate;
                $tmpdocstate->parentname = $data->parentname;
                $tmpdocstate->state = $data->state;
                $tmpdocstate->docstatus = $data->docstatus;
                $tmpdocstate->allow_edit = $data->allow_edit;
                $tmpdocstate->save();
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $tmpdocstate;
    }

    public function create(array $data)
    {
        return $this->save($data);
    }

    public function createdocstate(array $data)
    {
        return $this->savedocstate($data);
    }

    public function update(Workflow $workflow, array $data)
    {
        return $this->save($data, $workflow);
    }

    public function delete(Workflow $workflow)
    {
        $user_code = auth()->user()->code;
        return $workflow->update([
            'deleted_at' => now(),
            'is_active' => 0,
            'deleted_by' => $user_code,
        ]);
    }

    public function destroy(Workflow $workflow, $code)
    {
        $workflow = Workflow::where('id', $code)->delete();
        $workflow->where('id', $code)->delete();

        return $workflow;
    }

    public function changeActive(Workflow $workflow, array $data)
    {
        try {
            $workflow->changeActive([
            ]);
        }
        catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $workflow;
    }

    public function changeDeleted(Workflow $workflow, array $data)
    {
        try {
            $workflow->changeDeleted([
            ]);
        }
        catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $workflow;
    }

    public function saveExport($data)
    {
        $user_code = auth()->user()->code;

        foreach($data as $row){
            $workflow['id'] = $row['0'];
            $workflow['workflow_name'] = $row['1'];
            $workflow['document_type'] = $row['2'];
            $workflow['workflow_state_field'] = $row['3'];
            $workflow['activated_at'] = now();
            $workflow['activated_by'] = $user_code;
            $workflow['created_by'] = $user_code;

            $workflow = Workflow::create($workflow);
        }

        return true;

    }
}