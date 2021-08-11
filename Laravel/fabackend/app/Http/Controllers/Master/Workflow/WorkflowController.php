<?php

namespace App\Http\Controllers\Master\Workflow;

use App\Exceptions\AppException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Master\Workflow\WorkflowService;
use App\Repositories\Master\Workflow\WorkflowRepository;
use App\Models\Master\Workflow;
use App\Models\Master\WorkflowDocumentState;
use App\Models\Master\TmpWorkflow;
use App\Models\Master\TmpDocstate;
use App\Models\Master\TmpTransition;
use App\Models\Master\WorkflowTransition;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\WorkflowImport;
use App\Exports\WorkflowExport;

class WorkflowController extends Controller
{
    public function __construct(Request $request, WorkflowService $service, WorkflowRepository $repository)
    {
        $this->request = $request;
        $this->service = $service;
        $this->repository = $repository;
        
    }

    public function index()
    {
        $data = $this->repository->get($this->request->all());

        $data = collect(['success' => true])->merge($data);

        return response()->json($data);
    }

    public function indexdocstate()
    {
        $data = $this->repository->getdocstate($this->request->all());

        $data = collect(['success' => true])->merge($data);

        return response()->json($data);
    }

    public function indextmpdocstate()
    {
        $data = $this->repository->gettmpdocstate($this->request->all());

        $data = collect(['success' => true])->merge($data);

        return response()->json($data);
    }

    public function indextransition()
    {
        $data = $this->repository->gettransition($this->request->all());

        $data = collect(['success' => true])->merge($data);

        return response()->json($data);
    }

    public function indextmptransition()
    {
        $data = $this->repository->gettmptransition($this->request->all());

        $data = collect(['success' => true])->merge($data);

        return response()->json($data);
    }

    public function show($code)
    {
        $data = $this->repository->show($code);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function showtransition($code)
    {
        $data = $this->repository->showtransition($code);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function list()
    {
        $data = $this->repository->list($this->request->all());

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getroles()
    {
        $data = $this->repository->getroles($this->request->all());

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function store()
    {

        // $exists = Employee::whereCode($this->request->code)->first();

        // if (isset($exists)) {
        //     throw new \Exception('Employee Code '.$this->request->code. ' already exists.');
        // }
        $data = $this->service->create($this->request->all());

        return response()->json([
            'success' => true,
            'message' => 'Workflow '.$data->id.' created.',
            'data' => $this->repository->show($data->id)
        ], 201);
    }

    public function storewftemp()
    {
        $id = new TmpWorkflow;
        $id->workflow_name = '';
        $id->save();

        return response()->json([
            'success' => true,
            // 'message' => 'Workflow '.$id->id.' created.',
            'data' => $id->id
        ], 201);

    }

    public function storetmpdocstate(Request $request)
    {
        $id = new TmpDocstate;
        $id->parentname = $request->parentname;
        $id->state = $request->state;
        $id->docstatus = $request->docstatus;
        $id->allow_edit = $request->allow_edit;
        $id->save();

        return response()->json([
            'success' => true,
            'message' => 'Document State Created.',
        ], 201);
    }

    public function storetmptransition(Request $request)
    {
        $id = new TmpTransition;
        $id->parentname = $request->parentname;
        $id->state = $request->state;
        $id->action = $request->action;
        $id->next_state = $request->next_state;
        $id->condition = $request->condition;
        $id->allow_self_approval = $request->allow_self_approval;
        $id->allowed = $request->allowed;
        $id->save();

        return response()->json([
            'success' => true,
            'message' => 'Transition Created.',
        ], 201);
    }

    public function storedocstate(Request $request)
    {
        $id = new WorkflowDocumentState;
        $id->parentname = $request->parentname;
        $id->state = $request->state;
        $id->docstatus = $request->docstatus;
        $id->allow_edit = $request->allow_edit;
        $id->save();

        return response()->json([
            'success' => true,
            'message' => 'Document State Created.',
        ], 201);
    }

    public function storetransition(Request $request)
    {
        $id = new WorkflowTransition;
        $id->parentname = $request->parentname;
        $id->state = $request->state;
        $id->action = $request->action;
        $id->next_state = $request->next_state;
        $id->condition = $request->condition;
        $id->allow_self_approval = $request->allow_self_approval;
        $id->allowed = $request->allowed;
        $id->save();

        return response()->json([
            'success' => true,
            'message' => 'Transition Created.',
        ], 201);
    }

    public function update($code)
    {
        $data = $this->repository->find($code, 'id');
 
        if ($code != $this->request->id) {
            throw new \Exception('Workflow cannot be changed.');
        }

        if ($code != $data->id) {
            throw new \Exception('Workflow cannot be changed.');
        }

        if ($this->request->id != $data->id) {
            throw new \Exception('Workflow cannot be changed.');
        }       

        $this->service->update($data, $this->request->all());

        return response()->json([
            'success' => true,
            'message' => 'Workflow '.$code.' updated.',
            'data' => $this->repository->show($code)
        ]);
    }

    public function updatetmpdocstate($code)
    {
        $data = TmpDocstate::where('id', $code)->firstOrFail();

        $data->state = $this->request->state;
        $data->docstatus = $this->request->docstatus;
        $data->allow_edit = $this->request->allow_edit;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Docstate updated.'
        ]);
    }

    public function updatedocstate($code)
    {
        $data = WorkflowDocumentState::where('id', $code)->firstOrFail();

        $data->state = $this->request->state;
        $data->docstatus = $this->request->docstatus;
        $data->allow_edit = $this->request->allow_edit;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Docstate updated.'
        ]);
    }

    public function updatetmptransition($code)
    {
        $data = TmpTransition::where('id', $code)->firstOrFail();

        $data->state = $this->request->state;
        $data->action = $this->request->action;
        $data->next_state = $this->request->next_state;
        $data->condition = $this->request->condition;
        $data->allow_self_approval = $this->request->allow_self_approval;
        $data->allowed = $this->request->allowed;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Transition updated.'
        ]);
    }

    public function updatetransition($code)
    {
        $data = WorkflowTransition::where('id', $code)->firstOrFail();

        $data->state = $this->request->state;
        $data->action = $this->request->action;
        $data->next_state = $this->request->next_state;
        $data->condition = $this->request->condition;
        $data->allow_self_approval = $this->request->allow_self_approval;
        $data->allowed = $this->request->allowed;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Transition updated.'
        ]);
    }

    public function delete($code)
    {
        $data = $this->repository->find($code, 'id');

        DB::beginTransaction();

        $this->service->delete($data);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Workflow  '.$code.' deleted.',
        ]);
    }

    public function deletedocstate($code)
    {
        $data = WorkflowDocumentState::where('id', $code);
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document State Deleted.',
        ]);
    }

    public function deletetmpdocstate($code)
    {
        $data = TmpDocstate::where('id', $code);
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document State Deleted.',
        ]);
    }

    public function deletetransition($code)
    {
        $data = WorkflowTransition::where('id', $code);
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transition State Deleted.',
        ]);
    }

    public function deletetmptransition($code)
    {
        $data = TmpTransition::where('id', $code);
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transition State Deleted.',
        ]);
    }

    public function destroy($code)
    {
        $data = $this->repository->find($code, 'id');

        

        return response()->json([
            'success' => true,
            'message' => 'Workflow deleted.',
        ]);
    }

    public function changeActive($code)
    {
        DB::beginTransaction();

        $data = $this->repository->find($code, 'id');

        $this->service->changeActive($data, $this->request->all());

       $message = $data->is_active ? 'activated' : 'deactivated';

        DB::commit();

       return response()->json([
            'success' => true,
            'message' => 'Workflow ' ."$code". " " . $message,
            'data' => $this->repository->show($code)
        ]);

    }

    public function changeDelete($code)
    {
        DB::beginTransaction();

        $data = $this->repository->find($code, 'id');

        $this->service->changeDeleted($data, $this->request->all());

       $message = $data->is_deleted ? 'deleted' : 'undeleted';

        DB::commit();

       return response()->json([
        'success' => true,
        'message' => 'Workflow ' ."$code". " " . $message,
        'data' => $this->repository->show($code)
    ]);

    }

    public function import()
    {
        $this->validate($this->request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        DB::beginTransaction();

        $rows = Excel::toArray(new WorkflowImport, $this->request->file('file'));

        if(!$rows) {
            throw new AppException("Data is empty");
        }

        $data = $this->service->saveExport($rows[0]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Import Data Success.'
        ], 201);
    }

    public function export()
    {
        return Excel::download(new WorkflowExport, 'workflowexport.xlsx');
    }

    public function template()
    {
        return response()->download(storage_path('template/workflow_template.xlsx'));
    }
}