<?php

namespace Routegroup\Database\Relations;

use Routegroup\Database\Relations\TestCase;
use Routegroup\Database\Relations\Models\Page;

class SyncPolymorphicOneToManyTest extends TestCase
{
    /** @test */
    public function should_sync_with_collection()
    {
        $page = Page::create(['title' => 'Example']);

        $page->sections = collect([
            ['name' => 'Item 1'],
            ['name' => 'Item 2'],
            ['name' => 'Item 3'],
            ['name' => 'Item 4']
        ]);

        $this->assertEquals(4, $page->sections->count());
    }

    /** @test */
    public function should_sync_with_array()
    {
        $page = Page::create(['title' => 'Example']);

        $page->sections = [
            ['name' => 'Item 1'],
            ['name' => 'Item 2'],
            ['name' => 'Item 3'],
            ['name' => 'Item 4']
        ];

        $this->assertEquals(4, $page->sections->count());
    }

    /** @test */
    public function should_sync_with_jsoned_string()
    {
        $page = Page::create(['title' => 'Example']);

        $page->sections = json_encode([
            ['name' => 'Item 1'],
            ['name' => 'Item 2'],
            ['name' => 'Item 3'],
            ['name' => 'Item 4']
        ]);

        $this->assertEquals(4, $page->sections->count());
    }

    /** @test */
    public function should_sync_with_array_but_json_as_item()
    {
        $page = Page::create(['title' => 'Example']);

        $page->sections = [
            json_encode(['name' => 'Item 1']),
            json_encode(['name' => 'Item 2']),
            json_encode(['name' => 'Item 3']),
            json_encode(['name' => 'Item 4'])
        ];

        $this->assertEquals(4, $page->sections->count());
    }

    /** @test */
    public function should_sync_with_jsoned_array_with_jsoned_items()
    {
        $page = Page::create(['title' => 'Example']);

        $page->sections = json_encode([
            json_encode(['name' => 'Item 1']),
            json_encode(['name' => 'Item 2']),
            json_encode(['name' => 'Item 3']),
            json_encode(['name' => 'Item 4'])
        ]);

        $this->assertEquals(4, $page->sections->count());
    }

    /** @test */
    public function should_remove_with_null_parameter()
    {
        $page = Page::create(['title' => 'Example']);

        $page->sections = collect([
            ['name' => 'Item 1'],
            ['name' => 'Item 2'],
            ['name' => 'Item 3']
        ]);

        $page->sections = null;

        $this->assertEquals(0, $page->sections->count());
    }

    /** @test */
    public function should_receive_invalid_argument_exception_with_not_jsoned_string()
    {
        $this->expectException(\InvalidArgumentException::class);

        $page = Page::create(['title' => 'Example']);

        $page->sections = 'string';
    }

    /** @test */
    public function should_update_given_items()
    {
        $page = Page::create(['title' => 'Example']);

        $page->sections = [
            ['name' => 'Item 1'],
            ['name' => 'Item 2']
        ];

        $sections = $page->sections->toArray();

        // Test purpose ... normally you would love to use eloquent save()
        $sections[1]['name'] = 'New Item Name';
        $page->sections = $sections;

        $this->assertTrue($page->fresh()->sections->get(1)->name == 'New Item Name');
    }
}
