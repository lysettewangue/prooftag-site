<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\SecurityPoint as SecurityPointSlot;
use App\Models\Equipment;

/**
 * Static premium visuals + Lucide icons for Contrôle technique surfaces.
 */
final class TechnicalInspectionVisual
{
    public static function hub(): string
    {
        return asset('images/content/technical-inspection/hub.jpg');
    }

    public static function process(): string
    {
        return asset('images/content/technical-inspection/process/pv-vignette.jpg');
    }

    public static function fieldCheck(): string
    {
        return asset('images/content/technical-inspection/process/field.jpg');
    }

    public static function equipment(): string
    {
        return asset('images/content/technical-inspection/equipment/test-bench.jpg');
    }

    public static function schema(): string
    {
        return asset('images/content/technical-inspection/schema-12-points.jpg');
    }

    public static function bubbleSeal(): string
    {
        return asset('images/content/technical-inspection/scelle-bulles.jpg');
    }

    /**
     * Concrete image for an official process step (0 = centre, 1 = MINT, 2 = field).
     * Images must match the step meaning — no decorative mismatch.
     */
    public static function processStep(int $index): string
    {
        return match ($index) {
            0 => asset('images/content/technical-inspection/process/pv-vignette.jpg'),
            1 => asset('images/content/technical-inspection/equipment/server.jpg'),
            2 => asset('images/content/technical-inspection/process/field.jpg'),
            default => self::hub(),
        };
    }

    public static function equipmentImageFor(Equipment $item): string
    {
        $media = $item->getFirstMedia('images');

        if ($media !== null) {
            return $media->getUrl();
        }

        return match ($item->sort_order) {
            1 => asset('images/content/technical-inspection/equipment/test-bench.jpg'),
            2 => asset('images/content/technical-inspection/equipment/tablet.jpg'),
            3 => asset('images/content/technical-inspection/equipment/server.jpg'),
            4 => asset('images/content/technical-inspection/equipment/douchette.jpg'),
            5 => asset('images/content/technical-inspection/equipment/legacy.jpg'),
            default => self::equipment(),
        };
    }

    /**
     * Lucide icon that matches the function of each security point.
     */
    public static function pointIcon(SecurityPointSlot|int $point): string
    {
        $slot = $point instanceof SecurityPointSlot
            ? $point
            : SecurityPointSlot::tryFrom($point);

        return match ($slot) {
            SecurityPointSlot::RegistrationCheck => 'file-search',
            SecurityPointSlot::MintDataUpdate => 'refresh-cw',
            SecurityPointSlot::TestBenchCollection => 'gauge',
            SecurityPointSlot::Photograph => 'camera',
            SecurityPointSlot::GpsLocation => 'map-pin',
            SecurityPointSlot::VisualInspectionTablet => 'tablet',
            SecurityPointSlot::ReportGeneration => 'file-text',
            SecurityPointSlot::DecisionGeneration => 'circle-check',
            SecurityPointSlot::SecuredPvPrint => 'printer',
            SecurityPointSlot::VignetteSeal => 'qr-code',
            SecurityPointSlot::MintCentralisation => 'server',
            SecurityPointSlot::FieldControl => 'smartphone',
            default => 'badge-check',
        };
    }
}
