<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Resources\Comments\Schemas\CommentForm;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class PostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Post Information')
                    ->collapsible()
                    ->schema([
                        TextEntry::make('title'),
                        TextEntry::make('content'),
                        TextEntry::make('author'),
                        TextEntry::make('education_histories')
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                ->columnSpanFull()
                ->columns(3),

                Section::make('Comments')
                    ->collapsible()
                    // ->collapsed()
                    ->icon(Heroicon::OutlinedChatBubbleLeft)
                    ->headerActions([
                        Action::make('create_comment')
                            ->label('Create')
                            ->color('gray')
                            ->schema([
                                Textarea::make('content')
                                    ->required()
                                    ->columnSpanFull(),
                                TextInput::make('author_name')
                                    ->required(),
                            ])
                            ->modalHeading('Create Comment')
                            ->icon(Heroicon::OutlinedPlus)
                            ->action(function ($data, $record, $livewire) {
                                $record->comments()->create($data);
                                Notification::make()
                                    ->title('Comment created')
                                    ->success()
                                    ->send();
                                
                                // Force refresh the entire record
                                $livewire->record->refresh();
                                $livewire->record->load('comments');
                            }),
                    ])
                    ->schema([
                        RepeatableEntry::make('comments')
                            ->hiddenLabel()
                            ->schema([
                                Group::make([
                                    Group::make([
                                        Actions::make([
                                            Action::make('delete')
                                                ->link()
                                                ->label('Delete')
                                                ->color('danger')
                                                ->outlined()
                                                ->requiresConfirmation()
                                                ->icon(Heroicon::OutlinedTrash)
                                                ->action(function($record, $livewire){
                                                    $record->delete();
                                                    Notification::make()
                                                        ->title('Comment deleted')
                                                        ->success()
                                                        ->send();
                                                    
                                                    // Force refresh the entire record
                                                    $livewire->record->refresh();
                                                    $livewire->record->load('comments');
                                                }),
                                            Action::make('edit')
                                                ->link()
                                                ->color('primary')
                                                ->label('Edit')
                                                ->icon(Heroicon::OutlinedPencilSquare)
                                                ->mountUsing(function (Component $component, Schema $schema) {
                                                    $comment = $component->getRecord();
                                                    return $schema->fill([
                                                        'content' => $comment->content,
                                                        'author_name' => $comment->author_name,
                                                    ]);
                                                })
                                                ->schema(CommentForm::configure(new Schema())->getComponents())
                                                ->action(function ($data, Component $component, $livewire) {
                                                    $record = $component->getRecord();
                                                    $record->update($data);
                                                    Notification::make()
                                                        ->title('Comment updated')
                                                        ->success()
                                                        ->send();
                                                    
                                                    // Force refresh the entire record
                                                    $livewire->record->refresh();
                                                    $livewire->record->load('comments');
                                                }),
                                        ])
                                            ->columnSpanFull()
                                            ->alignment('right'),
                                    ]),

                                    Group::make([
                                        TextEntry::make('content')
                                            ->label('Content'),
                                        TextEntry::make('author_name')
                                            ->label('Author'),
                                    ])->columns(['sm' => 1, 'lg' => 2]),

                                ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
