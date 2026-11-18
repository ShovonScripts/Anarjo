<?php

use App\Providers\AppServiceProvider;
use App\Providers\VoltServiceProvider;
use Mews\Purifier\PurifierServiceProvider;

return [
    AppServiceProvider::class,
    VoltServiceProvider::class,
    PurifierServiceProvider::class,
];
