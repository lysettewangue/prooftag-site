<?php

declare(strict_types=1);

use App\Enums\PageCode;
use App\Http\Controllers\CareerShowController;
use App\Http\Controllers\CentreIndexController;
use App\Http\Controllers\CentreShowController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CorporatePageController;
use App\Http\Controllers\EquipmentIndexController;
use App\Http\Controllers\EquipmentShowController;
use App\Http\Controllers\ExpertiseIndexController;
use App\Http\Controllers\ExpertiseShowController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalPageController;
use App\Http\Controllers\NewsIndexController;
use App\Http\Controllers\PostShowController;
use App\Http\Controllers\ProductIndexController;
use App\Http\Controllers\ProductShowController;
use App\Http\Controllers\ResourceDownloadController;
use App\Http\Controllers\ResourceIndexController;
use App\Http\Controllers\ResourceShowController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SecurityPointsController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TechnicalInspectionController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', RobotsController::class)->name('robots');

Route::get('/fr/{path?}', function (?string $path = null) {
    $suffix = ltrim((string) $path, '/');

    return redirect($suffix === '' ? '/' : '/'.$suffix, 301);
})->where('path', '.*');

$aboutRoutes = [
    PageCode::About->value => ['fr' => 'a-propos', 'en' => 'about', 'name' => 'about'],
    PageCode::Organisation->value => ['fr' => 'a-propos/organisation', 'en' => 'about/organisation', 'name' => 'about.organisation'],
    PageCode::History->value => ['fr' => 'a-propos/historique', 'en' => 'about/history', 'name' => 'about.history'],
    PageCode::Team->value => ['fr' => 'a-propos/equipe', 'en' => 'about/team', 'name' => 'about.team'],
    PageCode::Partners->value => ['fr' => 'a-propos/partenaires', 'en' => 'about/partners', 'name' => 'about.partners'],
    PageCode::Careers->value => ['fr' => 'a-propos/carrieres', 'en' => 'about/careers', 'name' => 'about.careers'],
];

Route::middleware('locale:fr')->name('fr.')->group(function () use ($aboutRoutes): void {
    Route::get('/', HomeController::class)->name('home');
    Route::get('/produits', ProductIndexController::class)->name('products.index');
    Route::get('/produits/{slug}', ProductShowController::class)->name('products.show');
    Route::get('/expertises', ExpertiseIndexController::class)->name('expertises.index');
    Route::get('/expertises/{slug}', ExpertiseShowController::class)->name('expertises.show');
    Route::get('/controle-technique', TechnicalInspectionController::class)->name('technical-inspection.hub');
    Route::get('/controle-technique/points-de-securisation', SecurityPointsController::class)->name('technical-inspection.security-points');
    Route::get('/controle-technique/equipements', EquipmentIndexController::class)->name('technical-inspection.equipment.index');
    Route::get('/controle-technique/equipements/{slug}', EquipmentShowController::class)->name('technical-inspection.equipment.show');
    Route::get('/centres-de-controle', CentreIndexController::class)->name('centres.index');
    Route::get('/centres-de-controle/{slug}', CentreShowController::class)->name('centres.show');
    Route::get('/ressources', ResourceIndexController::class)->name('resources.index');
    Route::get('/ressources/{slug}/telecharger', ResourceDownloadController::class)->name('resources.download');
    Route::get('/ressources/{slug}', ResourceShowController::class)->name('resources.show');
    Route::get('/actualites', NewsIndexController::class)->name('news.index');
    Route::get('/actualites/{slug}', PostShowController::class)->name('news.show');
    Route::get('/a-propos/carrieres/{slug}', CareerShowController::class)->name('careers.show');
    Route::get('/contact', [ContactController::class, 'show'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    Route::get('/mentions-legales', LegalPageController::class)
        ->defaults('code', PageCode::LegalNotice->value)
        ->name('legal.notice');
    Route::get('/politique-de-confidentialite', LegalPageController::class)
        ->defaults('code', PageCode::Privacy->value)
        ->name('legal.privacy');
    Route::get('/recherche', SearchController::class)->name('search');

    foreach ($aboutRoutes as $code => $paths) {
        Route::get('/'.$paths['fr'], CorporatePageController::class)
            ->defaults('code', $code)
            ->name($paths['name']);
    }
});

Route::prefix('en')->middleware('locale:en')->name('en.')->group(function () use ($aboutRoutes): void {
    Route::get('/', HomeController::class)->name('home');
    Route::get('/products', ProductIndexController::class)->name('products.index');
    Route::get('/products/{slug}', ProductShowController::class)->name('products.show');
    Route::get('/expertise', ExpertiseIndexController::class)->name('expertises.index');
    Route::get('/expertise/{slug}', ExpertiseShowController::class)->name('expertises.show');
    Route::get('/technical-inspection', TechnicalInspectionController::class)->name('technical-inspection.hub');
    Route::get('/technical-inspection/security-points', SecurityPointsController::class)->name('technical-inspection.security-points');
    Route::get('/technical-inspection/equipment', EquipmentIndexController::class)->name('technical-inspection.equipment.index');
    Route::get('/technical-inspection/equipment/{slug}', EquipmentShowController::class)->name('technical-inspection.equipment.show');
    Route::get('/inspection-centres', CentreIndexController::class)->name('centres.index');
    Route::get('/inspection-centres/{slug}', CentreShowController::class)->name('centres.show');
    Route::get('/resources', ResourceIndexController::class)->name('resources.index');
    Route::get('/resources/{slug}/download', ResourceDownloadController::class)->name('resources.download');
    Route::get('/resources/{slug}', ResourceShowController::class)->name('resources.show');
    Route::get('/news', NewsIndexController::class)->name('news.index');
    Route::get('/news/{slug}', PostShowController::class)->name('news.show');
    Route::get('/about/careers/{slug}', CareerShowController::class)->name('careers.show');
    Route::get('/contact', [ContactController::class, 'show'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    Route::get('/legal-notice', LegalPageController::class)
        ->defaults('code', PageCode::LegalNotice->value)
        ->name('legal.notice');
    Route::get('/privacy-policy', LegalPageController::class)
        ->defaults('code', PageCode::Privacy->value)
        ->name('legal.privacy');
    Route::get('/search', SearchController::class)->name('search');

    foreach ($aboutRoutes as $code => $paths) {
        Route::get('/'.$paths['en'], CorporatePageController::class)
            ->defaults('code', $code)
            ->name($paths['name']);
    }
});
