<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StrategyResource\Pages;
use App\Filament\Resources\StrategyResource\RelationManagers;
use App\Models\Strategy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists;

class StrategyResource extends Resource
{
    protected static ?string $model = Strategy::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\RichEditor::make('description')
                    ->label('Description')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\Repeater::make('images')
                    ->defaultItems(0)
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\TextInput::make('url')
                            ->label('Image URL')
                            ->required(),
                    ])
                    ->label('Images'),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(3)
            ->schema([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('Overview')
                            ->columns(3)
                            ->columnSpanFull()
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Name'),
                                Infolists\Components\TextEntry::make('description')
                                    ->html()
                                    ->label('Description'),
                            ]),
                        Tabs\Tab::make('Details')
                            ->schema([
                                Infolists\Components\RepeatableEntry::make('images')
                                    ->columnSpanFull()
                                    ->hiddenLabel()
                                    ->schema([
                                        Infolists\Components\ImageEntry::make('url')
                                            ->extraImgAttributes([
                                                'style' => 'width: 100%; height: auto;border-radius: .75rem;',
                                            ])
                                            ->columnSpanFull()
                                            ->hiddenLabel()
                                            ->url(fn(string $state) => $state, true),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Name'),
                Tables\Columns\TextColumn::make('description')
                    ->html()
                    ->searchable()
                    ->limit(50)
                    ->label('Description'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStrategies::route('/'),
            'create' => Pages\CreateStrategy::route('/create'),
            'view' => Pages\ViewStrategy::route('/{record}'),
            'edit' => Pages\EditStrategy::route('/{record}/edit'),
        ];
    }
}
