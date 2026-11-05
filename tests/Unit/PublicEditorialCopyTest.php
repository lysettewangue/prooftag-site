<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Support\PublicEditorialCopy;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PublicEditorialCopyTest extends TestCase
{
    #[Test]
    public function it_strips_ver_and_draft_notes_from_summary_and_html(): void
    {
        $this->assertNull(PublicEditorialCopy::summary('Suivi — publication après VER-06.'));
        $this->assertSame(
            'Plateforme de suivi des centres.',
            PublicEditorialCopy::summary('Plateforme de suivi des centres.'),
        );

        $html = <<<'HTML'
<p>Destinée aux responsables de centres.</p>
<p>La liste reste vide tant que VER-06 n’est pas clos.</p>
<p>Cette fiche reste en brouillon : l’URL publique n’est pas ouverte.</p>
HTML;

        $this->assertSame(
            '<p>Destinée aux responsables de centres.</p>',
            PublicEditorialCopy::html($html),
        );
        $this->assertSame(
            'Destinée aux responsables de centres.',
            PublicEditorialCopy::firstParagraphText($html),
        );

        $structured = <<<'HTML'
<p>Destinée aux responsables de centres.</p>
<ul>
<li>Vue consolidée</li>
<li>Tableaux de bord</li>
</ul>
<p>Lecture corporate uniquement.</p>
HTML;

        $this->assertSame(
            ['Destinée aux responsables de centres.', 'Lecture corporate uniquement.'],
            PublicEditorialCopy::paragraphs($structured),
        );
        $this->assertSame(
            ['Vue consolidée', 'Tableaux de bord'],
            PublicEditorialCopy::listItems($structured),
        );
    }
}
