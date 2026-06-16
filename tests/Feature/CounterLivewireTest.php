<?php

namespace Tests\Feature;

use App\Livewire\Counter;
use Livewire\Livewire;
use Tests\TestCase;

class CounterLivewireTest extends TestCase
{
    public function test_counter_component_is_reactive(): void
    {
        Livewire::test(Counter::class)
            ->assertSet('count', 0)
            ->call('increment')
            ->call('increment')
            ->assertSet('count', 2)
            ->call('decrement')
            ->assertSet('count', 1)
            ->assertSee('1');
    }

    public function test_counter_renders_inside_a_page(): void
    {
        // Confirms Livewire components mount within a Blade page.
        $this->blade('<livewire:counter />')->assertSee('add');
    }
}
