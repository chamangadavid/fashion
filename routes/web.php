<?php


use App\Http\Controllers\Audit\AuditLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\Staff\StaffsController;
use App\Http\Controllers\UserSearchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MyFashions\OrderController;
use App\Http\Controllers\MyFashions\ProductController;
use App\Http\Controllers\MyFashions\CollectionController;
use App\Http\Controllers\MyFashions\CustomerController;
use App\Http\Controllers\MyFashions\ReportController;
use App\Http\Controllers\MyFashions\FashionUserController;
use App\Http\Controllers\MyFashions\FashionSettingsController;
use App\Http\Controllers\MyFashions\ProductCategoryController;
use App\Http\Controllers\MyFashions\StockAdjustmentController;
use App\Http\Controllers\MyFashions\InventoryAuditController;
use App\Http\Controllers\MyFashions\ClientDashboardController;
use App\Http\Controllers\MyFashions\UserDashboardController;
use App\Http\Controllers\MyFashions\CheckoutController;
use App\Http\Controllers\MyFashions\CartController;
use App\Http\Controllers\MyFashions\SettingsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\ShopController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');


    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Site Pages Public pages
    |--------------------------------------------------------------------------
    */
   
    Route::post('/contact-us', [ContactController::class, 'store'])->name('contact.store');
    Route::get('/collections', [CollectionController::class, 'publicIndex'])->name('collections.index');
    Route::get('/collections/{collection:slug}', [CollectionController::class, 'publicShow'])->name('collections.show');
    Route::get('/products/{product:slug}', [ProductController::class, 'publicShow'])->name('products.show');
    Route::get('/shop/{slug}', [ShopController::class, 'category'])->name('shop.category');
    Route::get('/clothing/{slug}', [ProductCategoryController::class, 'show'])->name('shop.clothing');
    Route::get('/accessories/{slug}', [ProductCategoryController::class, 'show'])->name('shop.accessories');
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    /*
    |--------------------------------------------------------------------------
    | SHOPPING CART
    |--------------------------------------------------------------------------
    */

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/new-arrivals', [ClientDashboardController::class, 'newArrivals'])->name('new-arrivals');
    Route::get('/categories', [ProductCategoryController::class, 'categories'])->name('categories.index');


// Route::get('/about-us', function () {
//     return Inertia::render('Site/aboutUs');
// })->name('aboutUs');

// Route::get('/product-gallery', function () {
//     return Inertia::render('Site/Gallery');
// })->name('productGalleries');

// Route::get('/faq', function () {
//     return Inertia::render('Site/FAQ');
// })->name('faq');

Route::get('/contact-us', function () {
    return Inertia::render('Site/Contact');
})->name('contactDetails');



// Route::get('/management-team', function () {
//     return Inertia::render('Site/ManagementTeam');
// })->name('managementTeam');

// Route::get('/history-details', function () {
//     return Inertia::render('Site/History');
// })->name('history');

// Route::get('/our-services', function () {
//     return Inertia::render('Site/Services');
// })->name('ourServices');



// Route::get('/icao-annex', function () {
//     return Inertia::render('Site/ICAOAnnex');
// })->name('icaoAnnex');

// Route::get('/document-repository', function () {
//     return Inertia::render('Site/Documents');
// })->name('documentRepository');

// Route::get('/report-accidents', function () {
//     return Inertia::render('Site/AccidentReports');
// })->name('accidentPage');

// Route::get('/all-news', function () {
//     return Inertia::render('Site/News');
// })->name('newsPage');

// Route::get('/press-releases', function () {
//     return Inertia::render('Site/PressReleases');
// })->name('pressReleasesPage');

// Route::get('/announcement', function () {
//     return Inertia::render('Site/Announcements');
// })->name('announcementsPage');



    /*
    |--------------------------------------------------------------------------
    | Authenticated Routes
    |--------------------------------------------------------------------------
    */


    Route::middleware('auth')->group(function () {

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/roles-permissions', fn () => Inertia::render('RolesPermissions'))->name('roles.permissions');
        Route::get('/roles', [RolePermissionController::class, 'roles']);
        Route::post('/roles', [RolePermissionController::class, 'storeRole']);
        Route::put('/roles/{id}', [RolePermissionController::class, 'update']);
        Route::delete('/roles/{id}', [RolePermissionController::class, 'destroy']);
        Route::post('/roles/bulk-delete', [RolePermissionController::class, 'bulkDestroy']);
        Route::get('/permissions', [RolePermissionController::class, 'permissions']);
        Route::post('/permissions', [RolePermissionController::class, 'storePermission']);
        Route::get('/rolesAndPermission', [RolePermissionController::class, 'rolesAndPermission'])->name('admin.rolesAndPermission');

        Route::get('/staff-roles', [StaffsController::class, 'staffRoles'])->name('staff.index');
        Route::post('/add-users', [StaffsController::class, 'store']);
        Route::get('/get-staff-roles', [RolePermissionController::class, 'staffRoles']);
        // Route::get('/search-users', [UserSearchController::class, 'search'])->name('users.search');
        // Route::get('/users/{user}', [UserSearchController::class, 'show'])->name('users.show');


        //Cards & Assign
        Route::get('/eBusinessCards', [RolePermissionController::class, 'eBusinessCards'])->name('admin.eBusinessCards');
        Route::get('/users', [RolePermissionController::class, 'users']);
        Route::post('/users/assign-role', [RolePermissionController::class, 'assignRole']);




        /*
            |--------------------------------------------------------------------------
            | CHECKOUT-allowing checkout only for authenticated customers:
            |--------------------------------------------------------------------------
            */

        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
        Route::get('/checkout/confirmation/{order}', [OrderController::class, 'confirmation'])->name('checkout.confirmation');


        /*
        |--------------------------------------------------------------------------
        | MY FASHION
        |--------------------------------------------------------------------------
        */

        Route::prefix('fashion')->group(function () {
            
            /*
            |--------------------------------------------------------------------------
            | ORDERS
            |--------------------------------------------------------------------------
            */

            Route::get('/orders', [OrderController::class, 'index'])->name('fashion.orders.index');
            Route::get('/orders/pending', [OrderController::class, 'pending'])->name('fashion.orders.pending');
            Route::get('/orders/processing', [OrderController::class, 'processing'])->name('fashion.orders.processing');
            Route::get('/orders/completed', [OrderController::class, 'completed'])->name('fashion.orders.completed');
            Route::get('/orders/cancelled', [OrderController::class, 'cancelled'])->name('fashion.orders.rejected');
            Route::get('/orders/{order}', [OrderController::class, 'show'])->name('fashion.orders.show');
            Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('fashion.orders.status');

            /*
            |--------------------------------------------------------------------------
            | PRODUCTS
            |--------------------------------------------------------------------------
            */

            Route::get('/products', [ProductController::class, 'index'])->name('fashion.products.index');
            Route::get('/products/create', [ProductController::class, 'create'])->name('fashion.products.create');
            Route::post('/products/store', [ProductController::class, 'store'])->name('store');
            Route::get('/products/categories', [ProductController::class, 'categories'])->name('fashion.products.categories');
            Route::get('/products/inventory', [ProductController::class, 'inventory'])->name('fashion.products.inventory');
            Route::get('/products/{product}', [ProductController::class, 'show'])->name('show');
            Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('/products/{product}', [ProductController::class, 'update'])->name('update');
            Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('destroy');
            Route::post('/products/{product}/stock-adjustment', [StockAdjustmentController::class, 'store'])->name('fashion.products.stock-adjustment');
            Route::get('/products/inventory/audit', [InventoryAuditController::class, 'index'])->name('fashion.products.inventory.audit');
            Route::get('/products/inventory/audit/product/{product}', [InventoryAuditController::class, 'productAuditDetails'])->name('fashion.products.inventory.audit.product');





            /*
            |--------------------------------------------------------------------------
            | COLLECTIONS
            |--------------------------------------------------------------------------
            */

            Route::get('/collections', [CollectionController::class, 'index'])->name('fashion.collections.index');
            Route::get('/collections/create', [CollectionController::class, 'create'])->name('fashion.collections.create');
            Route::post('/collections/store', [CollectionController::class, 'store'])->name('store');
            Route::get('/collections/featured', [CollectionController::class, 'featured'])->name('fashion.collections.featured');

            /*
            |--------------------------------------------------------------------------
            | COLLECTION PRODUCTS
            |--------------------------------------------------------------------------
            */

            Route::get('/collections/{collection}/products', [CollectionController::class, 'products'])->name('products');
            Route::post('/collections/{collection}/products', [CollectionController::class, 'assignProducts'])->name('products.assign');


            /*
            |--------------------------------------------------------------------------
            | COLLECTION CRUD
            |--------------------------------------------------------------------------
            */
            Route::patch( '/collections/{collection}/toggle-featured', [CollectionController::class, 'toggleFeatured'])->name('fashion.collections.toggle-featured');
            Route::get('/collections/{collection}', [CollectionController::class, 'show'])->name('show');
            Route::get('/collections/{collection}/edit', [CollectionController::class, 'edit'])->name('edit');
            Route::put('/collections/{collection}', [CollectionController::class, 'update'])->name('update');
            Route::delete('/collections/{collection}', [CollectionController::class, 'destroy'])->name('destroy');
        
        /*
            |--------------------------------------------------------------------------
            | PRODUCT CATEGORIES
            |--------------------------------------------------------------------------
            */

            Route::get('/products/categories', [ProductCategoryController::class, 'index'])->name('fashion.products.categories');
            Route::post('/products/categories', [ProductCategoryController::class, 'store'])->name('fashion.products.categories.store');
            Route::put('/products/categories/{category}', [ProductCategoryController::class, 'update'])->name('fashion.products.categories.update');
            Route::delete('/products/categories/{category}', [ProductCategoryController::class, 'destroy'])->name('fashion.products.categories.destroy');
            Route::patch('/products/categories/{category}/toggle-status', [ProductCategoryController::class, 'toggleStatus'])->name('fashion.products.categories.toggle-status');
            Route::patch('/products/categories/{category}/toggle-featured', [ProductCategoryController::class, 'toggleFeatured'])->name('fashion.products.categories.toggle-featured');



            /*
            |--------------------------------------------------------------------------
            | CUSTOMERS
            |--------------------------------------------------------------------------
            */

            //Route::get('/fashion/customers', [OrderController::class, 'customers'])->name('fashion.customers');

            Route::get('/customers', [CustomerController::class, 'customers'])->name('fashion.customers.index');
            Route::get('/customers/groups', [CustomerController::class, 'groups'])->name('fashion.customers.groups');
            Route::get('/customers/vip', [CustomerController::class, 'vip'])->name('fashion.customers.vip');
            Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('fashion.customers.show');



            /*
            |--------------------------------------------------------------------------
            | REPORTS
            |--------------------------------------------------------------------------
            */

            Route::get('/reports/sales', [ReportController::class, 'sales'])->name('fashion.reports.sales');
            Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('fashion.reports.revenue');
            Route::get('/reports/customers', [ReportController::class, 'customers'])->name('fashion.reports.customers');
            Route::get('/reports/products', [ReportController::class, 'products'])->name('fashion.reports.products');

            // Download reports
        Route::get('/reports/sales/download', [ReportController::class, 'downloadSales'])->name('reports.sales.download');

        Route::get('/reports/revenue/download', [ReportController::class, 'downloadRevenue'])->name('reports.revenue.download');

        Route::get('/reports/customers/download',  [ReportController::class, 'downloadCustomers'])->name('reports.customers.download');

        Route::get('/reports/products/download', [ReportController::class, 'downloadProducts'])->name('reports.products.download');
        


            /*
            |--------------------------------------------------------------------------
            | USERS
            |--------------------------------------------------------------------------
            */

            Route::get('/users', [FashionUserController::class, 'index'])->name('fashion.users.index');
            Route::get('/users/create', [FashionUserController::class, 'create'])->name('fashion.users.create');
            Route::get('/users/roles', [FashionUserController::class, 'roles'])->name('fashion.users.roles');


            /*
            |--------------------------------------------------------------------------
            | SETTINGS
            |--------------------------------------------------------------------------
            */

            Route::get('/settings', [FashionSettingsController::class, 'index'])->name('fashion.settings.index');
            Route::get('/settings/shipping', [FashionSettingsController::class, 'shipping'])->name('fashion.settings.shipping');
            Route::post('/settings', [FashionSettingsController::class, 'update']);
            Route::get('/settings/store', [SettingsController::class, 'store'])->name('fashions.settings.store');
            Route::put('/settings/store', [SettingsController::class, 'updateStore'])->name('fashions.settings.store.update');
            Route::get('/settings/payments', [SettingsController::class, 'payments'])->name('fashion.settings.payments');
            Route::post('/settings/payments', [SettingsController::class, 'updatePayments'])->name('fashion.settings.payments.update');
            Route::post('/settings/shipping', [SettingsController::class, 'updateShipping'])->name('fashion.settings.shipping.update');






            //UserDasboard route for user

















        });


        /*
        |--------------------------------------------------------------------------
        | MY CLIENTS
        |--------------------------------------------------------------------------
        */
        Route::prefix('client')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | CLIENT DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get('/user-dashboard', [ClientDashboardController::class, 'dashboard'])->name('client.dashboard');
        Route::get('/shop', [ClientDashboardController::class, 'shop' ])->name('client.shop');


        /*
        |--------------------------------------------------------------------------
        | CLIENT ORDERS
        |--------------------------------------------------------------------------
        */

        Route::get('/orders', [ClientDashboardController::class, 'orders'])->name('client.orders.index');
        Route::get('/orders/pending', [ClientDashboardController::class, 'pendingOrders'])->name('client.orders.pending');


        Route::get('/orders/processing', [ClientDashboardController::class, 'processingOrders'])->name('client.orders.processing');


        Route::get('/orders/completed', [ClientDashboardController::class, 'completedOrders'])->name('client.orders.completed');


        Route::get('/orders/cancelled', [ClientDashboardController::class, 'cancelledOrders'])->name('client.orders.cancelled');



        /*
        |--------------------------------------------------------------------------
        | ORDER DETAILS
        |--------------------------------------------------------------------------
        */

        Route::get('/orders/{order}', [
            ClientDashboardController::class,
            'show'
        ])->name('client.orders.show');


        /*
        |--------------------------------------------------------------------------
        | PAYMENT HISTORY
        |--------------------------------------------------------------------------
        */

            Route::get('/payments', [ClientDashboardController::class, 'paymentHistory'])->name('client.payments');
        

            //my cart routes
            Route::get('/cart', [CartController::class, 'myCartIndex'])->name('client.cart');
            Route::post('/cart/{product}/add', [CartController::class, 'add'])->name('client.cart.add');
            Route::patch('/cart/{productId}', [CartController::class, 'update'])->name('client.cart.update');
            Route::delete('/cart/{productId}', [CartController::class, 'remove'])->name('client.cart.remove');
            Route::delete('/cart', [CartController::class, 'clear'])->name('client.cart.clear');


            //my checkout routes
            Route::get('/checkout', [CheckoutController::class, 'myCheckoutIndex'])->name('client.checkout');



        Route::post('/checkout/shipping', [CheckoutController::class, 'calculateShipping'])->name('client.checkout.shipping');
        Route::post('/checkout', [OrderController::class, 'myStore'])->name('client.checkout.store');
        Route::get('/checkout/confirmation/{order}', [OrderController::class, 'myConfirmation'])->name('checkout.my-confirmation');
        Route::get('/payments', [OrderController::class, 'myPayments'])->name('client.my-payments');




        Route::get('/my-profile', [ClientDashboardController::class, 'profile'])->name('client.profile');
        Route::get('/my-profile/edit', [ProfileController::class, 'clientEdit'])->name('client.profile.edit');
        Route::patch('/my-profile', [ProfileController::class, 'clientUpdate'])->name('client.profile.update');










        });


        /*
        |--------------------------------------------------------------------------
        | CONTACTS
        |--------------------------------------------------------------------------
        */
        Route::get('/admin/contact-messages', [ContactController::class, 'messages'])->name('admin.contact.messages');

        Route::patch(
            '/admin/contact-messages/{contactMessage}/read',
            [ContactController::class, 'markAsRead']
        )->name('admin.contact.messages.read');


        Route::post(
            '/admin/contact-messages/{contactMessage}/reply',
            [ContactController::class, 'reply']
        )->name('admin.contact.messages.reply');


        Route::delete(
            '/admin/contact-messages/{contactMessage}',
            [ContactController::class, 'destroy']
        )->name('admin.contact.messages.destroy');












































    });







require __DIR__ . '/auth.php';
