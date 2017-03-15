<?php

namespace Routegroup\Database\Relations;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;

abstract class SyncModel
{
    /**
     * Relationship object
     *
     * @var \Illuminate\Database\Eloquent\Relations\Relation
     */
    protected $model;

    /**
     * Additional data to add in create or update method
     *
     * @var array
     */
    protected $dependencies;

    /**
     * Return always synced values
     *
     * @param \Illuminate\Support\Collection|array|null $request
     * @param \Illuminate\Database\Eloquent\Relations\Relation $model
     * @param array $dependencies
     */
    public function __construct($request, Relation $model, array $dependencies = [])
    {
        $this->model = $model;

        $this->dependencies = $dependencies;

        $this->sync($request);
    }

    /**
     * Calling relationship as new instance
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    abstract protected function relationship();

    /**
     * Prepare input
     *
     * @param  \Illuminate\Support\Collection|array|null $items
     * @return \Illuminate\Support\Collection
     */
    protected function prepare($items)
    {
        if (is_null($items)) {
            $items = collect();
        }

        if (!$items instanceof Collection && is_array($items)) {
            $items = $this->transform($items);
        } elseif (!$items instanceof Collection && is_string($items)) {
            if (!json_decode($items)) {
                throw new \InvalidArgumentException('Given parameter is incorrect.');
            }

            $items = $this->transform(json_decode($items, true) ?: $items);
        }

        return $items;
    }

    /**
     * Tranform request to collection
     *
     * @param  array  $items
     * @return \Illuminate\Support\Collection
     */
    protected function transform(array $items)
    {
        return collect($items)->map(function ($item) {
            if (is_array($item)) {
                return $item;
            }

            return json_decode($item);
        });
    }

    /**
     * Sync items for relationship
     *
     * @param  \Illuminate\Support\Collection|array|null $items
     * @return void
     */
    public function sync($items)
    {
        $items = $this->prepare($items);

        $current = $this->relationship->pluck('id');

        $current->each(function ($id) use ($items) {
            if (!$items->contains('id', $id)) {
                $this->relationship->find($id)->delete();
            }
        });

        $items->each(function ($item) {
            $this->createOrUpdateItem((array) $item);
        });

        $this->model->getParent()->touch();
    }

    /**
     * Object which will create or update
     *
     * @return class
     */
    protected function object()
    {
        return method_exists($this->relationship->getRelated(), 'bootNodeTrait')
            ? $this->relationship->getRelated()
            : $this->relationship;
    }

     /**
     * Creates or updates an item
     *
     * @param  StdClass $data
     * @return class
     */
    protected function createOrUpdateItem($data)
    {
        $data = $this->resolveDependencies($data);

        if (isset($data['id']) && $data['id'] != 0) {
            $item = $this->object->find($data['id']);

            $item->fill($data)->save();

            return $item;
        }

        return $this->object->create($data);
    }

    /**
     * Resolve dependencies
     *
     * @param  array  $data
     * @return array
     */
    protected function resolveDependencies(array $data)
    {
        foreach ($this->dependencies as $attribute => $value) {
            $data[$attribute] = $value;

            if (isset($data['children'])) {
                $data['children'] = collect($data['children'])->map(function ($child) {
                    return $this->resolveDependencies($child);
                })->toArray();
            }
        }

        return $data;
    }

    /**
     * Dynamically retrieve function like attribute.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if (method_exists($this, $key)) {
            return $this->$key();
        }

        return $this->$key;
    }
}
