<?php

namespace App\Http\Controllers\Master\Pelanggan;

use App\Exceptions\AppException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Master\Pelanggan\KlasifikasipelangganService;
use App\Repositories\Master\Pelanggan\KlasifikasipelangganRepository;
use App\Models\Master\Klasifikasipelanggan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KlasifikasipelangganImport;
use App\Exports\KlasifikasipelangganExport;

class KlasifikasipelangganController extends Controller
{
    public function __construct(Request $request, KlasifikasipelangganService $service, KlasifikasipelangganRepository $repository)
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

    public function listaccount()
    {
        $data = $this->repository->listaccount($this->request->all());

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function store()
    {

        // $exists = Customers Classification::whereCode($this->request->code)->first();

        // if (isset($exists)) {
        //     throw new \Exception('Customers Classification Code '.$this->request->code. ' already exists.');
        // }
        // $class_code = $this->request->classification_code;
        // if ($class_code == "") {
        //     $class_code = $this->get_seq();
        //     $this->request->classification_code = $class_code;
        // }

        $data = $this->service->create($this->request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customers Classification '.$data->id.' created.',
            'data' => $this->repository->show($data->id)
        ], 201);
    }

    public function update($code)
    {
        $data = $this->repository->find($code, 'id');
 
        if ($code != $this->request->id) {
            throw new \Exception('Customers Classification Code cannot be changed.');
        }

        if ($code != $data->id) {
            throw new \Exception('Customers Classification Code cannot be changed.');
        }

        if ($this->request->id != $data->id) {
            throw new \Exception('employee Code cannot be changed.');
        }       

        $this->service->update($data, $this->request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customers Classification '.$code.' updated.',
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
            'message' => 'Customers Classification  '.$code.' deleted.',
        ]);
    }

    public function get_seq()
    {   
        $prefix = $this->request->prefix;
        $date = now();
        $company = $this->request->company;
        $entity = $this->request->entity;
        $data = $this->service->get($prefix, $date, $company, $entity);

        if($data == false)
        {
            return response()->json([
                'success' => false,
                'message' => 'Sequence Not Found'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
        // return $data;
    }

    public function destroy($code)
    {
        $data = $this->repository->find($code, 'id');

        

        return response()->json([
            'success' => true,
            'message' => 'Customers Classification deleted.',
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
            'message' => 'Customers Classification ' ."$code". " " . $message,
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
        'message' => 'Customers Classification ' ."$code". " " . $message,
        'data' => $this->repository->show($code)
    ]);

    }

    public function import()
    {
        $this->validate($this->request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        DB::beginTransaction();

        $rows = Excel::toArray(new KlasifikasipelangganImport, $this->request->file('file'));

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
        return Excel::download(new KlasifikasipelangganExport, 'customerclass_export.xlsx');
    }

    public function template()
    {
        return response()->download(storage_path('template/customerclass_template.xlsx'));
    }
}