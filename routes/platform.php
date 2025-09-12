<?php

declare(strict_types=1);

use App\Orchid\Screens\Examples\ExampleActionsScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleGridScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Product\ProductEditScreen;
use App\Orchid\Screens\Product\ProductListScreen;
use App\Orchid\Screens\Product\ProductViewScreen;
use App\Orchid\Screens\RentalApplication\RentalApplicationEditScreen;
use App\Orchid\Screens\RentalApplication\RentalApplicationListScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

Route::prefix('products')->as('products.')->group(function () {
    Route::screen('/', ProductListScreen::class)
        ->name('index')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.index')
            ->push('Каталог товаров', route('products.index')));
    
    Route::screen('/create', ProductEditScreen::class)
        ->name('create')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('products.index')
            ->push('Создание товара', route('products.create')));
    
    Route::screen('/{product}/edit', ProductEditScreen::class)
        ->name('edit')
        ->breadcrumbs(fn (Trail $trail, $product) => $trail
            ->parent('products.index')
            ->push('Редактирование товара', route('products.edit', $product)));

    Route::screen('/{product}/view', ProductViewScreen::class)
        ->name('view')
        ->breadcrumbs(fn (Trail $trail, $product) => $trail
            ->parent('products.index')
            ->push($product->name, route('products.view', $product)));
});

Route::prefix('rental-applications')->as('rental-applications.')->group(function () {
    Route::screen('/', RentalApplicationListScreen::class)
        ->name('index')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.index')
            ->push('Заявки на аренду', route('rental-applications.index')));
    
    Route::screen('/create', RentalApplicationEditScreen::class)
        ->name('create')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('rental-applications.index')
            ->push('Создание заявки на аренду', route('rental-applications.create')));
    
    Route::screen('/{rentalApplication}/edit', RentalApplicationEditScreen::class)
        ->name('edit')
        ->breadcrumbs(fn (Trail $trail, $rentalApplication) => $trail
            ->parent('rental-applications.index')
            ->push('Редактирование заявки на аренду', route('rental-applications.edit', $rentalApplication)));

    // Route::screen('/{rentalApplication}/view', ProductViewScreen::class)
    //     ->name('view')
    //     ->breadcrumbs(fn (Trail $trail, $rentalApplication) => $trail
    //         ->parent('rental-applications.index')
    //         ->push($rentalApplication->name, route('rental-applications.view', $rentalApplication)));
});

Route::name('platform.')->group(function () {
    Route::screen('/main', PlatformScreen::class)
        ->name('main');

    Route::screen('profile', UserProfileScreen::class)
        ->name('profile')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.index')
            ->push(__('Profile'), route('platform.profile')));

    Route::name('systems.')->group(function () {
        Route::prefix('users')->as('users.')->group(function () {
            Route::screen('/{user}/edit', UserEditScreen::class)
                ->name('edit')
                ->breadcrumbs(fn (Trail $trail, $user) => $trail
                    ->parent('platform.systems.users.index')
                    ->push($user->name, route('platform.systems.users.edit', $user)));
    
            Route::screen('/create', UserEditScreen::class)
                ->name('create')
                ->breadcrumbs(fn (Trail $trail) => $trail
                    ->parent('platform.systems.users.index')
                    ->push(__('Create'), route('platform.systems.users.create')));
    
            Route::screen('/', UserListScreen::class)
                ->name('index')
                ->breadcrumbs(fn (Trail $trail) => $trail
                    ->parent('platform.index')
                    ->push(__('Users'), route('platform.systems.users.index')));
        });

        Route::prefix('roles')->as('roles.')->group(function () {
            Route::screen('/{role}/edit', RoleEditScreen::class)
                ->name('edit')
                ->breadcrumbs(fn (Trail $trail, $role) => $trail
                    ->parent('platform.systems.roles.index')
                    ->push($role->name, route('platform.systems.roles.edit', $role)));
    
            Route::screen('/create', RoleEditScreen::class)
                ->name('create')
                ->breadcrumbs(fn (Trail $trail) => $trail
                    ->parent('platform.systems.roles.index')
                    ->push(__('Create'), route('platform.systems.roles.create')));
    
            Route::screen('/', RoleListScreen::class)
                ->name('index')
                ->breadcrumbs(fn (Trail $trail) => $trail
                    ->parent('platform.index')
                    ->push(__('Roles'), route('platform.systems.roles.index')));
        });
    });

    Route::screen('example', ExampleScreen::class)
        ->name('example')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.index')
            ->push('Example Screen'));

    Route::prefix('examples')->as('example.')->group(function () {
        Route::prefix('form')->group(function () {
            Route::screen('/fields', ExampleFieldsScreen::class)->name('fields');
            Route::screen('/advanced', ExampleFieldsAdvancedScreen::class)->name('advanced');
            Route::screen('/editors', ExampleTextEditorsScreen::class)->name('editors');
            Route::screen('/actions', ExampleActionsScreen::class)->name('actions');
        });

        Route::screen('/layouts', ExampleLayoutsScreen::class)->name('layouts');
        Route::screen('/grid', ExampleGridScreen::class)->name('grid');
        Route::screen('/charts', ExampleChartsScreen::class)->name('charts');
        Route::screen('/cards', ExampleCardsScreen::class)->name('cards');
    });
});
