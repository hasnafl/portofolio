<?php

namespace App\Repositories\Master\Pelanggan;

use App\Models\Master\Datapelanggan as Datapelanggan;
use App\Models\Master\Entity as Entity;
use Illuminate\Support\Facades\DB;

class DatapelangganRepository
{
    public function get($params = NULL)
    {
        $model = new Datapelanggan();
        
        $model->setPerPage($params['show'] ?? $model->getPerPage());

        // $data = $model->select('id',
        //     'customer_code',
        //     'customer_name',
        //     'customer_branch',
        //     'customer_phone1',
        //     'customer_classification',
        //     'is_active')
        //     ->when(!empty($params['keyword']), function ($q) use ($params) {
        //         $keyword = $params['keyword'];

        //         $q->orWhere('customer_name', 'like', "%{$keyword}%")
        //             ->orWhere('customer_code', 'like', "%{$keyword}%");
        //     })
        //     ->when(isset($params['is_active']), function ($q) use ($params) {
        //         $is_active = (boolean) $params['is_active'];
    
        //         $q->where('is_active', $is_active);
        //     })
        //     ->paginate();
        
        $data = $model->select('fna_mdm_customers.*', 'fna_mdm_customers_address.address_detail')
            ->join('fna_mdm_customers_address', 'fna_mdm_customers.customer_code', '=', 'fna_mdm_customers_address.address_customercode')
            ->when(!empty($params['keyword']), function ($q) use ($params) {
                $keyword = $params['keyword'];

                $q->orWhere('fna_mdm_customers.customer_name', 'like', "%{$keyword}%")
                    ->where('fna_mdm_customers_address.address_label', '=', 'Alamat Utama')
                    ->orWhere('fna_mdm_customers.customer_code', 'like', "%{$keyword}%")
                    ->where('fna_mdm_customers_address.address_label', '=', 'Alamat Utama');
                
            })
            ->when(isset($params['is_active']), function ($q) use ($params) {
                $is_active = (boolean) $params['is_active'];

                $q->where('fna_mdm_customers.is_active', $is_active);
            })
            ->where('fna_mdm_customers_address.address_label', '=', 'Alamat Utama')
            ->paginate();
        
        return [
            'tableState'=>$params,'data'=>$data
        ];
    }

    public function find($value, $key = 'id')
    {
        // $datapelanggan = Datapelanggan::where($key, $value)->firstOrFail();
        $datapelanggan = Datapelanggan::where('fna_mdm_customers.id', $value)
            ->where('fna_mdm_customers_address.address_label', '=', 'Alamat Utama')
            ->join('fna_mdm_customers_address', 'fna_mdm_customers.customer_code', '=', 'fna_mdm_customers_address.address_customercode')
            ->select('fna_mdm_customers.*', 'fna_mdm_customers_address.address_detail')
            ->firstOrFail();

        return $datapelanggan;
    }

    public function show($code)
    {
        $data = $this->find($code, 'id');
        
        return $data;
    }

    public function list($filter = [])
    {
        $model = new Datapelanggan();

        // return $model->select('id',
        // 'customer_code',
        // 'customer_name',
        // 'customer_branch',
        // 'customer_phone1',
        // 'customer_classification',
        // 'is_active')
        //     ->whereIsActive(true)
        //     ->when(!empty($filter['keyword']), function ($q) use ($filter) {
        //         $keyword = $filter['keyword'];

        //         $q->where(function ($q) use ($keyword) {
        //             $q->orWhere('customer_code', 'like', "%{$keyword}%")
        //                 ->orWhere('customer_name', 'like', "%{$keyword}%");
        //         });
        //     })
        //     //->limit($model->getPerPage())
        //     ->get();
        
        return $model->select('fna_mdm_customers.*', 'fna_mdm_customers_address.address_detail')
        ->join('fna_mdm_customers_address', 'fna_mdm_customers.customer_code', '=', 'fna_mdm_customers_address.address_customercode')
        ->where('fna_mdm_customers_address.address_label', '=', 'Alamat Utama')
        ->where('fna_mdm_customers.is_active', '=', 1)
        ->when(!empty($filter['keyword']), function ($q) use ($filter) {
            $keyword = $filter['keyword'];
            
            $q->where(function ($q) use ($keyword) {
                $q->orWhere('fna_mdm_customers.customer_name', 'like', "%{$keyword}%")
                    ->orWhere('fna_mdm_customers.customer_code', 'like', "%{$keyword}%");
            });
        })
        ->get();
    }

    public function listbranch($filter = [])
    {
        $model = new Entity();

        return $model->select('id',
        'entityname',
        'entitycode',
        'entitytype',
        'is_active')
            ->whereIsActive(true)
            ->when(!empty($filter['keyword']), function ($q) use ($filter) {
                $keyword = $filter['keyword'];

                $q->where(function ($q) use ($keyword) {
                    $q->orWhere('entitytype', 'like', "%{$keyword}%");
                });
            })
            //->limit($model->getPerPage())
            ->get();
    }
}