<?php
namespace App\Constants\Models;

use Illuminate\Http\Response;

trait BaseEntityManager
{
    abstract protected function getModelClass(): string;

    public function updateAttribute($id, $attribute, $value)
    {
        $modelClass = $this->getModelClass();
        $model = $modelClass::find($id);
        $model->$attribute = $value;
        $model->save();
    }
}
