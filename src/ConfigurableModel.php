<?php

namespace R64\Checkout;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ConfigurableModel
{
    /** @var Model */
    private $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    public function getClassName()
    {
        return get_class($this->getModel());
    }

    public function getTableName()
    {
        return $this->getModel()->getTable();
    }

    public function getForeignKey()
    {
        return Str::singular($this->getTableName()) . '_id';
    }
}
