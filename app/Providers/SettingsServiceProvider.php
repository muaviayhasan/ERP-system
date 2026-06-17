<?php

namespace App\Providers;

use App\Support\SettingsApplier;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        SettingsApplier::apply();
    }
}
