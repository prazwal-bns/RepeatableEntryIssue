<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('content')
                    ->columnSpanFull(),
                TextEntry::make('author'),
                TextEntry::make('education_histories')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),

                RepeatableEntry::make('comments')
                    // ->relationship('comments')
                    ->schema([
                        TextEntry::make('content'),
                        TextEntry::make('author_name'),
                    ]),
            ]);
    }
}
