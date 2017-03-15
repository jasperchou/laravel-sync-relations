<?php

namespace Routegroup\Database\Relations;

use Routegroup\Database\Relations\SyncModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SyncOneToMany extends SyncModel
{
    /**
     * Calling relationship as new instance
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    protected function relationship()
    {
        $model = $this->model;

        return new HasMany(
            $model->getRelated()->newQuery(),
            $model->getParent(),
            $model->getForeignKeyName(),
            $model->getParent()->getKeyName()
        );
    }
}
