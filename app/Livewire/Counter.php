<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * Minimal demo component proving the Livewire round-trip works end to end.
 * Safe to remove once real Livewire components are in place.
 */
class Counter extends Component
{
    public int $count = 0;

    public function increment(): void
    {
        $this->count++;
    }

    public function decrement(): void
    {
        $this->count--;
    }

    public function render()
    {
        return view('livewire.counter');
    }
}
