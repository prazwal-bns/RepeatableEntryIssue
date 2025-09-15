<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Resources\Comments\Schemas\CommentForm;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

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
                    ->columns(4)
                    ->schema([
                        TextEntry::make('content')
                            ->label('Content'),
                        TextEntry::make('author_name')
                            ->label('Author'),
                        Action::make('edit')
                            ->link()
                            ->label('Edit')
                            ->button()
                            ->icon(Heroicon::OutlinedPencilSquare)
                            ->schema(CommentForm::configure(new Schema())->getComponents())
                            ->action(function($data,$record){
                                $record->update($data);
                            })
                            ->mountUsing(function (Schema $schema, $record) {
                                return $schema->fill([
                                    'content' => $record->content,
                                    'author_name' => $record->author_name,
                                ]);
                            }),
                        Action::make('delete')
                            ->label('Delete')
                            ->outlined()
                            ->requiresConfirmation()
                            ->icon(Heroicon::OutlinedTrash)
                            ->action(function($record){
                                $record->delete();
                            }),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
