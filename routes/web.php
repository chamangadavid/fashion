<?php


use App\Http\Controllers\Audit\AuditLogController;
use App\Http\Controllers\Contacts\ContactController;
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
});



Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

// Route::get('/test-imagick', function() {
//     phpinfo();
// });


/*
|--------------------------------------------------------------------------
| Site Pages
|--------------------------------------------------------------------------
*/

//Collection Api
Route::get('/collections', function () {
    return Inertia::render('Site/Collections/Index');
})->name('collections.index');


Route::get('/shop/{slug}', [ShopController::class, 'category'])->name('shop.category');





Route::get('/about-us', function () {
    return Inertia::render('Site/aboutUs');
})->name('aboutUs');

Route::get('/product-gallery', function () {
    return Inertia::render('Site/Gallery');
})->name('productGalleries');

Route::get('/faq', function () {
    return Inertia::render('Site/FAQ');
})->name('faq');

Route::get('/contact-us', function () {
    return Inertia::render('Site/Contact');
})->name('contactDetails');

Route::get('/management-team', function () {
    return Inertia::render('Site/ManagementTeam');
})->name('managementTeam');

Route::get('/history-details', function () {
    return Inertia::render('Site/History');
})->name('history');

Route::get('/our-services', function () {
    return Inertia::render('Site/Services');
})->name('ourServices');



Route::get('/icao-annex', function () {
    return Inertia::render('Site/ICAOAnnex');
})->name('icaoAnnex');

Route::get('/document-repository', function () {
    return Inertia::render('Site/Documents');
})->name('documentRepository');

Route::get('/report-accidents', function () {
    return Inertia::render('Site/AccidentReports');
})->name('accidentPage');

Route::get('/all-news', function () {
    return Inertia::render('Site/News');
})->name('newsPage');

Route::get('/press-releases', function () {
    return Inertia::render('Site/PressReleases');
})->name('pressReleasesPage');

Route::get('/announcement', function () {
    return Inertia::render('Site/Announcements');
})->name('announcementsPage');

//details page for management team members
// Route::get('/management-team/{id}', function ($id) {
//     $member = Team::findOrFail($id);

//     return Inertia::render('Site/ManagementTeamDetails', [
//         'member' => $member
//     ]);
// });


//public
Route::post('/contact', [ContactController::class, 'store']);
// Route::get('/faqs/all', [FaqController::class, 'getfrontIndex']); // For your FAQ page
// Route::get('/public/announcements', [AnnouncementsController::class, 'publicAnnouncementsIndex']);
// Route::get('/public/news', [NewsController::class, 'publicNewsIndex']);
// Route::get('/all-news/{id}', [NewsController::class, 'show']);
// Route::get('/public-documents/folders', [DocumentController::class, 'Publicindex']);

// Route::get('/jobs/active', [JobController::class, 'getActiveJobs']);
// Route::get('/public-members', [TeamController::class, 'publicMember']);
// Route::get('/all-jobs-details/{job}', [JobController::class, 'details']);

// Route::get('/frontend/faqs', [FaqController::class, 'getfrontIndex']); 
// Route::get('/frontend/testimonials', [TestimonyController::class, 'frontendTestimonials']);
// Route::get('/frontend/gallery', [GalleryController::class,'frontendGallery']);



Route::middleware('auth')->group(function () {

 Route::get('/user-dashboard', [DashboardController::class, 'index'])->name('user.dashboard');



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
    Route::get('/search-users', [UserSearchController::class, 'search'])->name('users.search');
    Route::get('/users/{user}', [UserSearchController::class, 'show'])->name('users.show');


    //contact routes
    Route::get('/contact-messages', [ContactController::class, 'ContactUs'])->name('contact.index');
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);
    


     //Cards & Assign
    Route::get('/eBusinessCards', [RolePermissionController::class, 'eBusinessCards'])->name('admin.eBusinessCards');
    Route::get('/users', [RolePermissionController::class, 'users']);
    Route::post('/users/assign-role', [RolePermissionController::class, 'assignRole']);





    //Order Routes
    // Route::prefix('fashion')->group(function () {

    //     // Fashion Dashboard
    //     //Route::get('/', [DashboardController::class, 'fashionDashboard'])->name('fashion.index');

    //     Route::get('/orders', [OrderController::class, 'index'])->name('fashion.orders.index');
    //     Route::get('/orders/pending', [OrderController::class, 'pending'])->name('fashion.orders.pending');
    //     Route::get('/orders/processing', [OrderController::class, 'processing'])->name('fashion.orders.processing');
    //     Route::get('/orders/completed', [OrderController::class, 'completed'])->name('fashion.orders.completed');

    // });




    /*
|--------------------------------------------------------------------------
| MY FASHION
|--------------------------------------------------------------------------
*/

Route::prefix('fashion')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    // Route::get('/', [DashboardController::class,
    //     'fashionDashboard'
    // ])->name('fashion.index');


    /*
    |--------------------------------------------------------------------------
    | ORDERS
    |--------------------------------------------------------------------------
    */

    Route::get('/orders', [OrderController::class, 'index'])->name('fashion.orders.index');
    Route::get('/orders/pending', [OrderController::class, 'pending'])->name('fashion.orders.pending');
    Route::get('/orders/processing', [OrderController::class, 'processing'])->name('fashion.orders.processing');
    Route::get('/orders/completed', [OrderController::class, 'completed'])->name('fashion.orders.completed');


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

    Route::get('/collections', [CollectionController::class, 'index'])
    ->name('fashion.collections.index');

Route::get('/collections/create', [CollectionController::class, 'create'])
    ->name('fashion.collections.create');

Route::post('/collections/store', [CollectionController::class, 'store'])
    ->name('store');

Route::get('/collections/featured', [CollectionController::class, 'featured'])
    ->name('fashion.collections.featured');


/*
|--------------------------------------------------------------------------
| COLLECTION PRODUCTS
|--------------------------------------------------------------------------
*/

Route::get(
    '/collections/{collection}/products',
    [CollectionController::class, 'products']
)->name('products');

Route::post(
    '/collections/{collection}/products',
    [CollectionController::class, 'assignProducts']
)->name('products.assign');


/*
|--------------------------------------------------------------------------
| COLLECTION CRUD
|--------------------------------------------------------------------------
*/

Route::get(
    '/collections/{collection}',
    [CollectionController::class, 'show']
)->name('show');

Route::get(
    '/collections/{collection}/edit',
    [CollectionController::class, 'edit']
)->name('edit');

Route::put(
    '/collections/{collection}',
    [CollectionController::class, 'update']
)->name('update');

Route::delete(
    '/collections/{collection}',
    [CollectionController::class, 'destroy']
)->name('destroy');



    // Route::get('/collections', [CollectionController::class, 'index'])->name('fashion.collections.index');
    // Route::get('/collections/create', [CollectionController::class, 'create'])->name('fashion.collections.create');
    // Route::post('/collections/store', [CollectionController::class, 'store'])->name('store');
    // Route::get('/collections/featured', [CollectionController::class, 'featured'])->name('fashion.collections.featured');
    // Route::get('/collections/{collection}/products', [CollectionController::class, 'products'])->name('products');
    // Route::post('/collections/{collection}/products', [CollectionController::class, 'assignProducts'])->name('products.assign');
    // Route::get('/collections/{collection}', [CollectionController::class, 'show'])->name('show');
    // Route::get('/collections/{collection}/edit', [CollectionController::class, 'edit'])->name('edit');
    // Route::put('/collections/{collection}', [CollectionController::class, 'update'])->name('update');
    // Route::delete('/collections/{collection}', [CollectionController::class, 'destroy'])->name('destroy');
  
  
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

    Route::get('/customers', [CustomerController::class, 'index'])->name('fashion.customers.index');
    Route::get('/customers/groups', [CustomerController::class, 'groups'])->name('fashion.customers.groups');
    Route::get('/customers/vip', [CustomerController::class, 'vip'])->name('fashion.customers.vip');


    /*
    |--------------------------------------------------------------------------
    | REPORTS
    |--------------------------------------------------------------------------
    */

    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('fashion.reports.sales');
    Route::get('/reports/products', [ReportController::class, 'products'])->name('fashion.reports.products');
    Route::get('/reports/customers', [ReportController::class, 'customers'])->name('fashion.reports.customers');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('fashion.reports.revenue');


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
    Route::get('/settings/store', [FashionSettingsController::class, 'store'])->name('fashion.settings.store');
    Route::get('/settings/payments', [FashionSettingsController::class, 'payments'])->name('fashion.settings.payments');
    Route::get('/settings/shipping', [FashionSettingsController::class, 'shipping'])->name('fashion.settings.shipping');

});










































});







require __DIR__ . '/auth.php';
