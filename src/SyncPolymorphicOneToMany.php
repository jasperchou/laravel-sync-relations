<?php

namespace Routegroup\Database\Relations;

use Routegroup\Database\Relations\SyncModel;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SyncPolymorphicOneToMany extends SyncModel
{
    /**
     * Calling relationship as new instance
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    protected function relationship()
    {
        $model = $this->model;

        return new MorphMany(
            $model->getRelated()->newQuery(),
            $model->getParent(),
            $model->getMorphType(),
            $model->getForeignKeyName(),
            $model->getParent()->getKeyName()
        );
    }
}
