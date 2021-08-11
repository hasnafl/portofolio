<?php

namespace App\Http\Controllers\Master\Entity;

use App\Exceptions\AppException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Master\Entity\EntityService;
use App\Repositories\Master\Entity\EntityRepository;
use App\Models\Master\Entity;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EntityImport;
use App\Exports\EntityExport;

class EntityController extends Controller
{
    public function __construct(Request $request, EntityService $service, EntityRepository $repository)
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

    public function show($code)
    {
        $data = $this->repository->show($code);

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

    public function store()
    {

        // $exists = Entity::whereCode($this->request->code)->first();

        // if (isset($exists)) {
        //     throw new \Exception('Entity Code '.$this->request->code. ' already exists.');
        // }

        $data = $this->service->create($this->request->all());

        return response()->json([
            'success' => true,
            'message' => 'Entity '.$data->id.' created.',
            'data' => $this->repository->show($data->id)
        ], 201);
    }

    public function update($code)
    {
        $data = $this->repository->find($code, 'id');
 
        if ($code != $this->request->id) {
            throw new \Exception('Entity Code cannot be changed.');
        }

        if ($code != $data->id) {
            throw new \Exception('Entity Code cannot be changed.');
        }

        if ($this->request->id != $data->id) {
            throw new \Exception('Entity Code cannot be changed.');
        }       

        $this->service->update($data, $this->request->all());

        return response()->json([
            'success' => true,
            'message' => 'Entity '.$code.' updated.',
            'data' => $this->repository->show($code)
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
            'message' => 'Entity  '.$code.' deleted.',
        ]);
    }

    public function destroy($code)
    {
        $data = $this->repository->find($code, 'id');

        

        return response()->json([
            'success' => true,
            'message' => 'Entity deleted.',
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
        'message' => 'Entity ' ."$code". " " . $message,
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
        'message' => 'Entity ' ."$code". " " . $message,
        'data' => $this->repository->show($code)
    ]);

    }

    public function import()
    {
        $this->validate($this->request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        DB::beginTransaction();

        $rows = Excel::toArray(new EntityImport, $this->request->file('file'));

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
        return Excel::download(new EntityExport, 'entityexport.xlsx');
    }

    public function template()
    {
        return response()->download(storage_path('template/entity_template.xlsx'));
    }
}