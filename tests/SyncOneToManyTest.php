<?php

namespace Routegroup\Database\Relations;

use Routegroup\Database\Relations\TestCase;
use Routegroup\Database\Relations\Models\Page;

class SyncOneToManyTest extends TestCase
{
    /** @test */
    public function should_sync_with_collection()
    {
        $page = Page::create(['title' => 'Example']);

        $page->options = collect([
            ['name' => 'Item 1', 'page_id' => $page->id],
            ['name' => 'Item 2', 'page_id' => $page->id],
            ['name' => 'Item 3', 'page_id' => $page->id],
            ['name' => 'Item 4', 'page_id' => $page->id]
        ]);

        $this->assertEquals(4, $page->options->count());
    }

    /** @test */
    public function should_sync_with_array()
    {
        $page = Page::create(['title' => 'Example']);

        $page->options = [
            ['name' => 'Item 1', 'page_id' => $page->id],
            ['name' => 'Item 2', 'page_id' => $page->id],
            ['name' => 'Item 3', 'page_id' => $page->id],
            ['name' => 'Item 4', 'page_id' => $page->id]
        ];

        $this->assertEquals(4, $page->options->count());
    }

    /** @test */
    public function should_sync_with_jsoned_string()
    {
        $page = Page::create(['title' => 'Example']);

        $page->options = json_encode([
            ['name' => 'Item 1', 'page_id' => $page->id],
            ['name' => 'Item 2', 'page_id' => $page->id],
            ['name' => 'Item 3', 'page_id' => $page->id],
            ['name' => 'Item 4', 'page_id' => $page->id]
        ]);

        $this->assertEquals(4, $page->options->count());
    }

    /** @test */
    public function should_sync_with_array_but_json_as_item()
    {
        $page = Page::create(['title' => 'Example']);

        $page->options = [
            json_encode(['name' => 'Item 1', 'page_id' => $page->id]),
            json_encode(['name' => 'Item 2', 'page_id' => $page->id]),
            json_encode(['name' => 'Item 3', 'page_id' => $page->id]),
            json_encode(['name' => 'Item 4', 'page_id' => $page->id])
        ];

        $this->assertEquals(4, $page->options->count());
    }

    /** @test */
    public function should_sync_with_jsoned_array_with_jsoned_items()
    {
        $page = Page::create(['title' => 'Example']);

        $page->options = json_encode([
            json_encode(['name' => 'Item 1', 'page_id' => $page->id]),
            json_encode(['name' => 'Item 2', 'page_id' => $page->id]),
            json_encode(['name' => 'Item 3', 'page_id' => $page->id]),
            json_encode(['name' => 'Item 4', 'page_id' => $page->id])
        ]);

        $this->assertEquals(4, $page->options->count());
    }

    /** @test */
    public function should_remove_with_null_parameter()
    {
        $page = Page::create(['title' => 'Example']);

        $page->options = collect([
            ['name' => 'Item 1', 'page_id' => $page->id],
            ['name' => 'Item 2', 'page_id' => $page->id],
            ['name' => 'Item 3', 'page_id' => $page->id]
        ]);

        $page->options = null;

        $this->assertEquals(0, $page->options->count());
    }

    /** @test */
    public function should_receive_invalid_argument_exception_with_not_jsoned_string()
    {
        $this->expectException(\InvalidArgumentException::class);

        $page = Page::create(['title' => 'Example']);

        $page->options = 'string';
    }

    /** @test */
    public function should_update_given_items()
    {
        $page = Page::create(['title' => 'Example']);

        $page->options = [
            ['name' => 'Item 1', 'page_id' => $page->id],
            ['name' => 'Item 2', 'page_id' => $page->id]
        ];

        $sections = $page->options->toArray();

        // Test purpose ... normally you would love to use eloquent save()
        $sections[1]['name'] = 'New Item Name';
        $page->options = $sections;

        $this->assertTrue($page->fresh()->options->get(1)->name == 'New Item Name');
    }
}
