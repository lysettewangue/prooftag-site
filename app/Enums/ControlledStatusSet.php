<?php

declare(strict_types=1);

namespace App\Enums;

enum ControlledStatusSet: string
{
    case Publication = 'publication';
    case CentreVerification = 'centre_verification';
    case ResourceRegulatory = 'resource_regulatory';
    case EquipmentLifecycle = 'equipment_lifecycle';
    case CareerKind = 'career_kind';
    case PostKind = 'post_kind';
    case ContactRequest = 'contact_request';

    /**
     * @return list<string>
     */
    public function codes(): array
    {
        return match ($this) {
            self::Publication => PublicationStatus::codes(),
            self::CentreVerification => CentreVerificationStatus::codes(),
            self::ResourceRegulatory => ResourceRegulatoryStatus::codes(),
            self::EquipmentLifecycle => EquipmentLifecycle::codes(),
            self::CareerKind => CareerKind::codes(),
            self::PostKind => PostKind::codes(),
            self::ContactRequest => ContactRequestStatus::codes(),
        };
    }
}
