<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Expertise\ExpertiseTitleRules;
use PHPUnit\Framework\TestCase;

class ExpertiseTitleRulesTest extends TestCase
{
    public function test_product_public_names_are_rejected_as_expertise_titles(): void
    {
        $this->assertFalse(ExpertiseTitleRules::mayUseTitle('CERTIDOCS CT'));
        $this->assertFalse(ExpertiseTitleRules::mayUseTitle('ct-verif'));
        $this->assertFalse(ExpertiseTitleRules::mayUseTitle('  DOSER  '));
        $this->assertFalse(ExpertiseTitleRules::mayUseTitle('Video Enforcement'));
    }

    public function test_distinct_expertise_titles_are_allowed(): void
    {
        $this->assertTrue(ExpertiseTitleRules::mayUseTitle('Sécurité documentaire'));
        $this->assertTrue(ExpertiseTitleRules::mayUseTitle('Document security'));
        $this->assertTrue(ExpertiseTitleRules::mayUseTitle('Recherche et développement'));
    }
}
