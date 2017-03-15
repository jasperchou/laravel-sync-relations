# Laravel Sync Relations

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]

Small package to sync one to many and polymorphic one to many data in Laravel Eloquent.

## Install

Via Composer

``` bash
$ composer require routegroup/laravel-sync-relations
```

## Example

In model
``` php
    public function setOptionsAttribute($items)
    {
        if (!$this->exists) {
            return ;
        }

        // this->options() is method which returns \Illuminate\Database\Eloquent\Relations\HasMany
        return new SyncOneToMany($items, $this->options(), [
            'page_id' => $this->id
        ]);
    }
```

In ex. controller
``` php
    public function create()
    {
        $page = Page::create([...]);

        $page->options = [
            ['name' => 'Option 1'],
            ['name' => 'Option 2'],
            ['name' => 'Option 3']
        ];
    }
```

Options can be as json encoded data, laravel collection or just array.
For more examples look at tests.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/routegroup/laravel-sync-relations.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/routegroup/laravel-sync-relations/master.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/routegroup/laravel-sync-relations
[link-travis]: https://travis-ci.org/routegroup/laravel-sync-relations
[link-author]: https://github.com/routegroup
