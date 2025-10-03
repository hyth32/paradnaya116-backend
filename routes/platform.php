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
use App\Orchid\Screens\Promotion\PromotionEditScreen;
use App\Orchid\Screens\Promotion\PromotionListScreen;
use App\Orchid\Screens\Promotion\PromotionViewScreen;
use App\Orchid\Screens\Service\ServiceEditScreen;
use App\Orchid\Screens\Service\ServiceListScreen;
use App\Orchid\Screens\Service\ServiceViewScreen;
use App\Orchid\Screens\PurchaseApplication\PurchaseApplicationEditScreen;
use App\Orchid\Screens\PurchaseApplication\PurchaseApplicationListScreen;
use App\Orchid\Screens\PurchaseApplication\PurchaseApplicationViewScreen;
use App\Orchid\Screens\RentalApplication\RentalApplicationEditScreen;
use App\Orchid\Screens\RentalApplication\RentalApplicationListScreen;
use App\Orchid\Screens\RentalApplication\RentalAppllicationViewScreen;
use App\Orchid\Screens\ServiceApplication\ServiceApplicationEditScreen;
use App\Orchid\Screens\ServiceApplication\ServiceApplicationListScreen;
use App\Orchid\Screens\ServiceApplication\ServiceApplicationViewScreen;
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

Route::prefix('services')->as('services.')->group(function () {
    Route::screen('/', ServiceListScreen::class)
        ->name('index')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.index')
            ->push('Каталог услуг', route('services.index')));
    
    Route::screen('/create', ServiceEditScreen::class)
        ->name('create')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('services.index')
            ->push('Создание услуги', route('services.create')));
    
    Route::screen('/{service}/edit', ServiceEditScreen::class)
        ->name('edit')
        ->breadcrumbs(fn (Trail $trail, $service) => $trail
            ->parent('services.index')
            ->push('Редактирование услуги', route('services.edit', $service)));

    Route::screen('/{service}/view', ServiceViewScreen::class)
        ->name('view')
        ->breadcrumbs(fn (Trail $trail, $service) => $trail
            ->parent('services.index')
            ->push($service->name, route('services.view', $service)));
});

Route::prefix('promotions')->as('promotions.')->group(function () {
    Route::screen('/', PromotionListScreen::class)
        ->name('index')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.index')
            ->push('Список акций', route('promotions.index')));

    Route::screen('/create', PromotionEditScreen::class)
        ->name('create')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('promotions.index')
            ->push('Создание акции', route('promotions.create')));
    
    Route::screen('/{promotion}/edit', PromotionEditScreen::class)
        ->name('edit')
        ->breadcrumbs(fn (Trail $trail, $promotion) => $trail
            ->parent('promotions.index')
            ->push('Редактирование акции', route('promotions.edit', $promotion)));

    Route::screen('/{promotion}/view', PromotionViewScreen::class)
        ->name('view')
        ->breadcrumbs(fn (Trail $trail, $promotion) => $trail
            ->parent('promotions.index')
            ->push($promotion->name, route('promotions.view', $promotion)));
});

Route::prefix('rental-applications')->as('rental-applications.')->group(function () {
    Route::screen('/', RentalApplicationListScreen::class)
        ->name('index')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.index')
            ->push('Список заявок', route('rental-applications.index')));
    
    Route::screen('/create', RentalApplicationEditScreen::class)
        ->name('create')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('rental-applications.index')
            ->push('Создание заявки', route('rental-applications.create')));
    
    Route::screen('/{rentalApplication}/edit', RentalApplicationEditScreen::class)
        ->name('edit')
        ->breadcrumbs(fn (Trail $trail, $rentalApplication) => $trail
            ->parent('rental-applications.index')
            ->push('Редактирование заявки', route('rental-applications.edit', $rentalApplication)));

    Route::screen('/{rentalApplication}/view', RentalAppllicationViewScreen::class)
        ->name('view')
        ->breadcrumbs(fn (Trail $trail, $rentalApplication) => $trail
            ->parent('rental-applications.index')
            ->push("Заявка №{$rentalApplication->id}", route('rental-applications.view', $rentalApplication)));
});

Route::prefix('purchase-applications')->as('purchase-applications.')->group(function () {
    Route::screen('/', PurchaseApplicationListScreen::class)
        ->name('index')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.index')
            ->push('Список заявок на покупку', route('purchase-applications.index')));
    
    Route::screen('/create', PurchaseApplicationEditScreen::class)
        ->name('create')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('purchase-applications.index')
            ->push('Создание заявки на покупку', route('purchase-applications.create')));
    
    Route::screen('/{purchaseApplication}/edit', PurchaseApplicationEditScreen::class)
        ->name('edit')
        ->breadcrumbs(fn (Trail $trail, $purchaseApplication) => $trail
            ->parent('purchase-applications.index')
            ->push('Редактирование заявки на покупку', route('purchase-applications.edit', $purchaseApplication)));

    Route::screen('/{purchaseApplication}/view', PurchaseApplicationViewScreen::class)
        ->name('view')
        ->breadcrumbs(fn (Trail $trail, $purchaseApplication) => $trail
            ->parent('purchase-applications.index')
            ->push("Заявка на покупку №{$purchaseApplication->id}", route('purchase-applications.view', $purchaseApplication)));
});

Route::prefix('service-applications')->as('service-applications.')->group(function () {
    Route::screen('/', ServiceApplicationListScreen::class)
        ->name('index')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('platform.index')
            ->push('Список заявок на услугу', route('service-applications.index')));
    
    Route::screen('/create', ServiceApplicationEditScreen::class)
        ->name('create')
        ->breadcrumbs(fn (Trail $trail) => $trail
            ->parent('service-applications.index')
            ->push('Создание заявки на услугу', route('service-applications.create')));
    
    Route::screen('/{serviceApplication}/edit', ServiceApplicationEditScreen::class)
        ->name('edit')
        ->breadcrumbs(fn (Trail $trail, $serviceApplication) => $trail
            ->parent('service-applications.index')
            ->push('Редактирование заявки на услугу', route('service-applications.edit', $serviceApplication)));

    Route::screen('/{serviceApplication}/view', ServiceApplicationViewScreen::class)
        ->name('view')
        ->breadcrumbs(fn (Trail $trail, $serviceApplication) => $trail
            ->parent('service-applications.index')
            ->push("Заявка на услугу №{$serviceApplication->id}", route('service-applications.view', $serviceApplication)));
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
