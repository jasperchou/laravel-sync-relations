<?php

namespace Routegroup\Database\Relations\Models;

use Illuminate\Database\Eloquent\Model;
use Routegroup\Database\Relations\SyncOneToMany;
use Routegroup\Database\Relations\SyncPolymorphicOneToMany;

class Page extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'sections', 'options'];

    /**
     * Can contains many comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function sections()
    {
        return $this->morphMany('Routegroup\Database\Relations\Models\Section', 'sectionable');
    }

    /**
     * Has many options
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany('Routegroup\Database\Relations\Models\Option');
    }

    /**
     * Variables setter
     *
     * @param array $items
     */
    public function setSectionsAttribute($items)
    {
        if (!$this->exists) {
            return ;
        }

        return new SyncPolymorphicOneToMany($items, $this->sections());
    }

    /**
     * Options setter
     *
     * @param array $items
     */
    public function setOptionsAttribute($items)
    {
        if (!$this->exists) {
            return ;
        }

        return new SyncOneToMany($items, $this->options());
    }
}
