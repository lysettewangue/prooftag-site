<?php

declare(strict_types=1);

namespace App\Enums;

enum StaffPermission: string
{
    case Translate = 'translate';
    case Publish = 'publish';
    case VerifyCentre = 'verify_centre';
    case ManageRedirects = 'manage_redirects';
    case ContactInbox = 'contact_inbox';
    case ManageUsers = 'manage_users';

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_column(self::cases(), 'value');
    }
}
