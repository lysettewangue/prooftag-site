<?php

declare(strict_types=1);

namespace App\Filament\Support;

use App\Enums\ContentBlockType;
use App\Models\Product;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

final class ContentBlockBuilder
{
    public static function make(string $name): Builder
    {
        return Builder::make($name)
            ->label('Blocs')
            ->addActionLabel('Ajouter un bloc')
            ->blocks([
                self::block(ContentBlockType::RichText, [
                    Textarea::make('content')->label('Texte')->rows(6),
                ]),
                self::block(ContentBlockType::TextAndMedia, [
                    Textarea::make('content')->label('Texte')->rows(5),
                    TextInput::make('media_id')->numeric()->label('Id média (optionnel)'),
                ]),
                self::block(ContentBlockType::Gallery, [
                    Textarea::make('caption')->label('Légende'),
                ]),
                self::block(ContentBlockType::Timeline, [
                    Repeater::make('items')
                        ->label('Étapes')
                        ->schema([
                            TextInput::make('year')->label('Année'),
                            Textarea::make('text')->label('Texte')->rows(3),
                        ]),
                ]),
                self::block(ContentBlockType::Statistics, [
                    Repeater::make('items')
                        ->label('Chiffres')
                        ->helperText('Pas de compteur live (AUD-013). Uniquement des valeurs validées.')
                        ->schema([
                            TextInput::make('label')->label('Libellé'),
                            TextInput::make('value')->label('Valeur'),
                        ]),
                ]),
                self::block(ContentBlockType::Faq, [
                    Repeater::make('items')
                        ->label('Questions')
                        ->schema([
                            TextInput::make('question')->label('Question'),
                            Textarea::make('answer')->label('Réponse')->rows(3),
                        ]),
                ]),
                self::block(ContentBlockType::DocumentList, [
                    Textarea::make('intro')->label('Introduction'),
                ]),
                self::block(ContentBlockType::Specifications, [
                    Repeater::make('items')
                        ->label('Lignes')
                        ->schema([
                            TextInput::make('label')->label('Libellé'),
                            TextInput::make('value')->label('Valeur'),
                        ]),
                ]),
                self::block(ContentBlockType::RelatedSolutions, [
                    Select::make('product_ids')
                        ->label('Produits')
                        ->multiple()
                        ->options(fn (): array => Product::query()->orderBy('sort_order')->pluck('public_name', 'id')->all()),
                ]),
                self::block(ContentBlockType::RelatedResources, [
                    Textarea::make('intro')->label('Introduction'),
                ]),
                self::block(ContentBlockType::Partners, [
                    Textarea::make('intro')->label('Introduction'),
                ]),
                self::block(ContentBlockType::Team, [
                    Textarea::make('intro')->label('Introduction'),
                ]),
                self::block(ContentBlockType::CallToAction, [
                    TextInput::make('label')->label('Libellé'),
                    TextInput::make('url')->label('Chemin')->startsWith('/'),
                ]),
            ]);
    }

    /**
     * @param  list<mixed>  $schema
     */
    private static function block(ContentBlockType $type, array $schema): Block
    {
        return Block::make($type->value)
            ->label($type->label())
            ->schema($schema);
    }
}
