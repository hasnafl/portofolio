<?php

namespace App\Repositories\Master\Entity;

use App\Models\Master\Entity as Entity;
use Illuminate\Support\Facades\DB;

class EntityRepository
{
    public function get($params = NULL)
    {
        $model = new Entity();
        
        $model->setPerPage($params['show'] ?? $model->getPerPage());

        $data = $model->select('id', 'entityname', 'entityaddress', 'entitycode','entityphone', 'entitycontactperson', 'is_active')
            ->when(!empty($params['keyword']), function ($q) use ($params) {
                $keyword = $params['keyword'];

                $q->orWhere('entityname', 'like', "%{$keyword}%")
                    ->orWhere('entitycode', 'like', "%{$keyword}%");
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
        $entity = Entity::where($key, $value)->firstOrFail();

        return $entity;
    }

    public function show($code)
    {
        $data = $this->find($code, 'id');
        
        return $data;
    }

    public function list($filter = [])
    {
        $model = new Entity();

        return $model->select('id', 'entityname', 'entityaddress', 'entitycode','entityphone', 'entitycontactperson', 'is_active')
            ->whereIsActive(true)
            ->when(!empty($filter['keyword']), function ($q) use ($filter) {
                $keyword = $filter['keyword'];

                $q->where(function ($q) use ($keyword) {
                    $q->orWhere('entityname', 'like', "%{$keyword}%")
                        ->orWhere('entitycode', 'like', "%{$keyword}%");
                });
            })
            //->limit($model->getPerPage())
            ->get();
    }
}