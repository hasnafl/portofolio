<?php

namespace App\Http\Controllers\Master\Karyawan;

use App\Exceptions\AppException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Master\Karyawan\KaryawanService;
use App\Repositories\Master\Karyawan\KaryawanRepository;
use App\Models\Master\Karyawan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KaryawanImport;
use App\Exports\KaryawanExport;

class KaryawanController extends Controller
{
    public function __construct(Request $request, KaryawanService $service, KaryawanRepository $repository)
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

        // $exists = Employee::whereCode($this->request->code)->first();

        // if (isset($exists)) {
        //     throw new \Exception('Employee Code '.$this->request->code. ' already exists.');
        // }

        $data = $this->service->create($this->request->all());

        return response()->json([
            'success' => true,
            'message' => 'Employee '.$data->idk_kode_karyawan.' created.',
            'data' => $this->repository->show($data->idk_kode_karyawan)
        ], 201);
    }

    public function update($code)
    {
        $data = $this->repository->find($code, 'idk_kode_karyawan');
 
        if ($code != $this->request->idk_kode_karyawan) {
            throw new \Exception('Employee Code cannot be changed.');
        }

        if ($code != $data->idk_kode_karyawan) {
            throw new \Exception('Employee Code cannot be changed.');
        }

        if ($this->request->idk_kode_karyawan != $data->idk_kode_karyawan) {
            throw new \Exception('employee Code cannot be changed.');
        }       

        $this->service->update($data, $this->request->all());

        return response()->json([
            'success' => true,
            'message' => 'Employee '.$code.' updated.',
            'data' => $this->repository->show($code)
        ]);
    }

    public function delete($code)
    {
        $data = $this->repository->find($code, 'idk_kode_karyawan');

        DB::beginTransaction();

        $this->service->delete($data);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Employee  '.$code.' deleted.',
        ]);
    }

    public function destroy($code)
    {
        $data = $this->repository->find($code, 'idk_kode_karyawan');

        

        return response()->json([
            'success' => true,
            'message' => 'Employee deleted.',
        ]);
    }

    public function changeActive($code)
    {
        DB::beginTransaction();

        $data = $this->repository->find($code, 'idk_kode_karyawan');

        $this->service->changeActive($data, $this->request->all());

       $message = $data->is_active ? 'activated' : 'deactivated';

        DB::commit();

       return response()->json([
            'success' => true,
            'message' => 'Employee ' ."$code". " " . $message,
            'data' => $this->repository->show($code)
        ]);

    }

    public function changeDelete($code)
    {
        DB::beginTransaction();

        $data = $this->repository->find($code, 'idk_kode_karyawan');

        $this->service->changeDeleted($data, $this->request->all());

       $message = $data->is_deleted ? 'deleted' : 'undeleted';

        DB::commit();

       return response()->json([
        'success' => true,
        'message' => 'Employee ' ."$code". " " . $message,
        'data' => $this->repository->show($code)
    ]);

    }

    public function import()
    {
        $this->validate($this->request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        DB::beginTransaction();

        $rows = Excel::toArray(new KaryawanImport, $this->request->file('file'));

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
        return Excel::download(new KaryawanExport, 'employeeexport.xlsx');
    }

    public function template()
    {
        return response()->download(storage_path('template/employee_template.xlsx'));
    }
}