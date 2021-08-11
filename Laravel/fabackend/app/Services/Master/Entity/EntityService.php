<?php

namespace App\Services\Master\Entity;
use App\Models\Master\Entity;
use Att\Responisme\Exceptions\StarterKitException;

class EntityService
{
    public function save($data, Entity $entity = null)
    {
        $user_code = auth()->user()->code;

        $validated_data = validate($data, Entity::rules($entity->id ?? null));

        if (!$entity) {
            $validated_data['activated_at'] = now();
            $validated_data['activated_by'] = $user_code;
            $validated_data['created_by'] = $user_code;
        }

        $validated_data['updated_at'] = $entity ? now() : null;
        $validated_data['updated_by'] = $entity ? $user_code : null;
        
        try {
            if ($entity) {
                $entity->update($validated_data);
            } else {
                $entity = Entity::create($validated_data);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = 'Invalid credentials.';
            }
            
            throw (new StarterKitException(transMessageException($e)))->withData($e)->withCode('02');
        }

        return $entity;
    }

    public function create(array $data)
    {
        return $this->save($data);
    }

    public function update(Entity $entity, array $data)
    {
        return $this->save($data, $entity);
    }

    public function delete(Entity $entity)
    {
        $user_code = auth()->user()->code;
        return $entity->update([
            'deleted_at' => now(),
            'is_active' => 0,
            'deleted_by' => $user_code,
        ]);
    }

    public function destroy(Entity $entity, $code)
    {
        $entity = Entity::where('id', $code)->delete();
        $entity->where('id', $code)->delete();

        return $entity;
    }

    public function changeActive(Entity $entity, array $data)
    {
        $entity->changeActive([
        ]);

        return $entity;
    }

    public function changeDeleted(Entity $entity, array $data)
    {
        $entity->changeDeleted([
        ]);

        return $entity;
    }

    public function saveExport($data)
    {
        $user_code = auth()->user()->code;

        foreach($data as $row){
            $entity['id'] = $row['0'];
            $entity['entitycode'] = $row['1'];
            $entity['entityname'] = $row['2'];
            $entity['entityaddress'] = $row['3'];
            $entity['entityphone'] = $row['4'];
            $entity['entitycontactperson'] = $row['5'];
            $entity['activated_at'] = now();
            $entity['activated_by'] = $user_code;
            $entity['created_by'] = $user_code;

            $geographicregion = Entity::create($entity);
        }

        return true;

    }
}