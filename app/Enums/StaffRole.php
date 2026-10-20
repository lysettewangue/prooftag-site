<?php

declare(strict_types=1);

namespace App\Enums;

enum StaffRole: string
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case ContentManager = 'content_manager';
    case TechnicalEditor = 'technical_editor';
    case CommunicationsEditor = 'communications_editor';
    case Translator = 'translator';
    case ReviewerPublisher = 'reviewer_publisher';
    case SupportAgent = 'support_agent';

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Custom verbs from docs/admin/roles.md §3. Domain "update" waits for CMS
     * resources (088+). Role-resource abilities are assigned only to Super Admin.
     *
     * @return list<StaffPermission>
     */
    public function permissions(): array
    {
        return match ($this) {
            self::SuperAdmin, self::Admin => StaffPermission::cases(),
            self::ContentManager => [StaffPermission::Translate],
            self::TechnicalEditor => [StaffPermission::VerifyCentre],
            self::CommunicationsEditor => [StaffPermission::Publish, StaffPermission::ContactInbox],
            self::Translator => [StaffPermission::Translate],
            self::ReviewerPublisher => [StaffPermission::Publish],
            self::SupportAgent => [StaffPermission::ContactInbox],
        };
    }

    /**
     * @return list<string>
     */
    public function permissionNames(): array
    {
        return array_map(
            static fn (StaffPermission $permission): string => $permission->value,
            $this->permissions(),
        );
    }
}
