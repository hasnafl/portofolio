<?php

namespace App\Repositories\Master\Pelanggan;

use App\Models\Master\Klasifikasipelanggan as Klasifikasipelanggan;
use App\Models\Master\Account as Account;
use Illuminate\Support\Facades\DB;

class KlasifikasipelangganRepository
{
    public function get($params = NULL)
    {
        $model = new Klasifikasipelanggan();
        
        $model->setPerPage($params['show'] ?? $model->getPerPage());

        $data = $model->select('id',
            'classification_code',
            'classification_name',
            'account_akrual_piutang',
            'account_piutang',
            'account_downpayment',
            'description',
            'is_active')
            ->when(!empty($params['keyword']), function ($q) use ($params) {
                $keyword = $params['keyword'];

                $q->orWhere('classification_name', 'like', "%{$keyword}%")
                    ->orWhere('classification_code', 'like', "%{$keyword}%");
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
        $klasifikasipelanggan = Klasifikasipelanggan::where($key, $value)->firstOrFail();

        return $klasifikasipelanggan;
    }

    public function show($code)
    {
        $data = $this->find($code, 'id');
        
        return $data;
    }

    public function list($filter = [])
    {
        $model = new Klasifikasipelanggan();

        return $model->select('id',
        'classification_code',
        'classification_name',
        'account_akrual_piutang',
        'account_piutang',
        'account_downpayment',
        'description',
        'is_active')
            ->whereIsActive(true)
            ->when(!empty($filter['keyword']), function ($q) use ($filter) {
                $keyword = $filter['keyword'];

                $q->where(function ($q) use ($keyword) {
                    $q->orWhere('classification_code', 'like', "%{$keyword}%")
                        ->orWhere('classification_name', 'like', "%{$keyword}%");
                });
            })
            //->limit($model->getPerPage())
            ->get();
    }

    public function listaccount($filter = [])
    {
        $model = new Account();

        return $model->select('id',
        'accountnumber',
        'accountname',
        'accountclass',
        'is_active')
            ->whereIsActive(true)
            ->when(!empty($filter['keyword']), function ($q) use ($filter) {
                $keyword = $filter['keyword'];

                $q->where(function ($q) use ($keyword) {
                    $q->orWhere('accountclass', 'like', "%{$keyword}%");
                });
            })
            //->limit($model->getPerPage())
            ->get();
    }
}