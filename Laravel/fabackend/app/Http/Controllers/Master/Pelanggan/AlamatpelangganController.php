<?php

namespace App\Http\Controllers\Master\Pelanggan;

use App\Exceptions\AppException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Master\Pelanggan\AlamatpelangganService;
use App\Repositories\Master\Pelanggan\AlamatpelangganRepository;
use App\Models\Master\Alamatpelanggan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AlamatpelangganImport;
use App\Exports\AlamatpelangganExport;

class AlamatpelangganController extends Controller
{
    public function __construct(Request $request, AlamatpelangganService $service, AlamatpelangganRepository $repository)
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

        // $exists = Customer Address::whereCode($this->request->code)->first();
        if ($this->request->address_label == "Alamat Utama"){
            $exists = Alamatpelanggan::where("address_label", "Alamat Utama")
            ->where("address_customercode", "=", $this->request->address_customercode)->first();
            if (isset($exists)) {
                throw new \Exception('Customer Address With Label '.$this->request->address_label. ' already exists.');
            }
        }

        $data = $this->service->create($this->request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customer Address '.$data->id.' created.',
            'data' => $this->repository->show($data->id)
        ], 201);
    }

    public function update($code)
    {
        $data = $this->repository->find($code, 'id');
 
        if ($code != $this->request->id) {
            throw new \Exception('Customer Address Code cannot be changed.');
        }

        if ($code != $data->id) {
            throw new \Exception('Customer Address Code cannot be changed.');
        }

        if ($this->request->id != $data->id) {
            throw new \Exception('employee Code cannot be changed.');
        }       

        $this->service->update($data, $this->request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customer Address '.$code.' updated.',
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
            'message' => 'Customer Address  '.$code.' deleted.',
        ]);
    }

    public function destroy($code)
    {
        $data = $this->repository->find($code, 'id');

        

        return response()->json([
            'success' => true,
            'message' => 'Customer Address deleted.',
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
            'message' => 'Customer Address ' ."$code". " " . $message,
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
        'message' => 'Customer Address ' ."$code". " " . $message,
        'data' => $this->repository->show($code)
    ]);

    }

    public function import()
    {
        $this->validate($this->request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        DB::beginTransaction();

        $rows = Excel::toArray(new AlamatpelangganImport, $this->request->file('file'));

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
        return Excel::download(new AlamatpelangganExport, 'customeraddress_export.xlsx');
    }

    public function template()
    {
        return response()->download(storage_path('template/customeraddress_template.xlsx'));
    }
}