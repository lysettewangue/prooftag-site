<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\StaffRole;
use App\Filament\Resources\ActivityLog\ActivityLogResource;
use App\Filament\Resources\Careers\CareerResource;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\ContactRequests\ContactRequestResource;
use App\Filament\Resources\Equipment\EquipmentResource;
use App\Filament\Resources\Expertises\ExpertiseResource;
use App\Filament\Resources\InspectionCentres\InspectionCentreResource;
use App\Filament\Resources\LibraryDocuments\LibraryDocumentResource;
use App\Filament\Resources\MediaLibrary\MediaLibraryResource;
use App\Filament\Resources\Partners\PartnerResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Redirects\RedirectResource;
use App\Filament\Resources\SecurityPoints\SecurityPointResource;
use App\Filament\Resources\SitePages\SitePageResource;
use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Filament\Resources\StaffUsers\StaffUserResource;
use App\Filament\Resources\TeamMembers\TeamMemberResource;
use App\Filament\Resources\TechnicalInspection\TechnicalInspectionResource;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPermissionsGateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_each_of_the_eight_roles_matches_the_module_matrix(): void
    {
        foreach (StaffRole::cases() as $role) {
            $user = User::factory()->staff($role)->create();

            foreach ($this->surfaces() as $name => $url) {
                $response = $this->actingAs($user)->get($url);
                $allowed = in_array($role, $this->allowedRoles($name), true);

                if ($allowed) {
                    $this->assertTrue(
                        $response->isSuccessful(),
                        "{$role->value} should open {$name} ({$url}); got {$response->status()}",
                    );

                    continue;
                }

                $this->assertSame(
                    403,
                    $response->status(),
                    "{$role->value} should be forbidden on {$name} ({$url}); got {$response->status()}",
                );
            }
        }
    }

    /**
     * @return array<string, string>
     */
    private function surfaces(): array
    {
        return [
            'dashboard' => '/admin',
            'settings' => SiteSettingResource::getUrl('edit', ['record' => 1]),
            'pages' => SitePageResource::getUrl('index'),
            'products' => ProductResource::getUrl('index'),
            'expertises' => ExpertiseResource::getUrl('index'),
            'ct_hub' => TechnicalInspectionResource::getUrl('edit', ['record' => TechnicalInspectionResource::hub()]),
            'security_points' => SecurityPointResource::getUrl('index'),
            'equipment' => EquipmentResource::getUrl('index'),
            'centres' => InspectionCentreResource::getUrl('index'),
            'team' => TeamMemberResource::getUrl('index'),
            'partners' => PartnerResource::getUrl('index'),
            'posts' => PostResource::getUrl('index'),
            'categories' => CategoryResource::getUrl('index'),
            'resources' => LibraryDocumentResource::getUrl('index'),
            'careers' => CareerResource::getUrl('index'),
            'media' => MediaLibraryResource::getUrl('index'),
            'contact' => ContactRequestResource::getUrl('index'),
            'redirects' => RedirectResource::getUrl('index'),
            'users' => StaffUserResource::getUrl('index'),
            'audit' => ActivityLogResource::getUrl('index'),
            'roles' => '/admin/shield/roles',
        ];
    }

    /**
     * @return list<StaffRole>
     */
    private function allowedRoles(string $surface): array
    {
        $all = StaffRole::cases();
        $technical = [
            StaffRole::SuperAdmin,
            StaffRole::Admin,
            StaffRole::ContentManager,
            StaffRole::TechnicalEditor,
            StaffRole::Translator,
            StaffRole::ReviewerPublisher,
        ];
        $communications = [
            StaffRole::SuperAdmin,
            StaffRole::Admin,
            StaffRole::ContentManager,
            StaffRole::CommunicationsEditor,
            StaffRole::Translator,
            StaffRole::ReviewerPublisher,
        ];
        $settingsAndMedia = [
            StaffRole::SuperAdmin,
            StaffRole::Admin,
            StaffRole::ContentManager,
            StaffRole::Translator,
        ];
        $centres = [
            StaffRole::SuperAdmin,
            StaffRole::Admin,
            StaffRole::ContentManager,
            StaffRole::TechnicalEditor,
            StaffRole::Translator,
            StaffRole::SupportAgent,
        ];
        $contact = [
            StaffRole::SuperAdmin,
            StaffRole::Admin,
            StaffRole::CommunicationsEditor,
            StaffRole::SupportAgent,
        ];
        $platform = [StaffRole::SuperAdmin, StaffRole::Admin];

        return match ($surface) {
            'dashboard' => $all,
            'settings', 'media' => $settingsAndMedia,
            'pages', 'team', 'partners', 'posts', 'categories', 'careers' => $communications,
            'products', 'expertises', 'ct_hub', 'security_points', 'equipment', 'resources' => $technical,
            'centres' => $centres,
            'contact' => $contact,
            'redirects', 'users', 'audit' => $platform,
            'roles' => [StaffRole::SuperAdmin],
        };
    }
}
