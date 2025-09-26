<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configurePassport();
    }

    private function configurePassport(): void
    {
        Passport::tokensCan([
            'users.read'    => 'Read users',
            'users.write'   => 'Create/Update/Delete users',
            'external.read' => 'Call /external endpoint',
        ]);
    }
}
