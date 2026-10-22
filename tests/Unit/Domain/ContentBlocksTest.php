<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Content\ContentBlocks;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\ContentBlockType;
use PHPUnit\Framework\TestCase;

class ContentBlocksTest extends TestCase
{
    public function test_empty_blocks_are_valid(): void
    {
        $this->assertSame([], ContentBlocks::normalize(null));
        $this->assertSame([], ContentBlocks::normalize([]));
    }

    public function test_thirteen_approved_types_are_registered(): void
    {
        $this->assertCount(13, ContentBlockType::codes());
        $this->assertContains('rich_text', ContentBlockType::codes());
        $this->assertContains('call_to_action', ContentBlockType::codes());
    }

    public function test_filament_builder_payload_flattens_to_stored_shape(): void
    {
        $stored = ContentBlocks::normalize([
            [
                'type' => 'rich_text',
                'data' => ['content' => 'Bonjour.'],
                'uuid' => 'drop-me',
            ],
        ]);

        $this->assertSame([
            ['type' => 'rich_text', 'content' => 'Bonjour.'],
        ], $stored);

        $this->assertSame([
            ['type' => 'rich_text', 'data' => ['content' => 'Bonjour.']],
        ], ContentBlocks::toForm($stored));
    }

    public function test_unknown_types_are_rejected(): void
    {
        $this->expectException(DomainRuleViolation::class);

        ContentBlocks::normalize([['type' => 'hero_banner', 'content' => 'x']]);
    }

    public function test_brand_and_spacing_keys_are_rejected(): void
    {
        $this->expectException(DomainRuleViolation::class);

        ContentBlocks::normalize([
            ['type' => 'rich_text', 'content' => 'x', 'color' => '#0033aa'],
        ]);
    }
}
