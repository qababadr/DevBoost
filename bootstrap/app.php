<?php

use BadrQaba\DevBoost\Console\Commands\CleanFolder;
use BadrQaba\DevBoost\Console\Commands\CreateBlade;
use BadrQaba\DevBoost\Console\Commands\CreateLayout;
use BadrQaba\DevBoost\Console\Commands\CreateSubscriber;
use BadrQaba\DevBoost\Console\Commands\CreateTrait;
use BadrQaba\DevBoost\Console\Commands\MigrateAll;
use BadrQaba\DevBoost\Console\Commands\WipeTable;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withCommands([
        CleanFolder::class,
        CreateBlade::class,
        CreateTrait::class,
        CreateLayout::class,
        CreateSubscriber::class,
        MigrateAll::class,
        WipeTable::class
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
