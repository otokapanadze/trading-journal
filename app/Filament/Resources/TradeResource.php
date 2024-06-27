<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TradeResource\Pages;
use App\Filament\Resources\TradeResource\RelationManagers;
use App\Models\Trade;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists;
use Filament\Infolists\Components\Tabs;
use Illuminate\Support\HtmlString;

class TradeResource extends Resource
{
    protected static ?string $model = Trade::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(5)
            ->schema([
                Forms\Components\TextInput::make('symbol')
                    ->default('EUR/USD')
                    ->columnSpan(1)
                    ->required(),
                Forms\Components\Select::make('direction')
                    ->columnSpan(1)
                    ->options([
                        'buy' => 'Buy',
                        'sell' => 'Sell',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('pnl')
                    ->columnSpan(1)
                    ->label('Profit/Loss (-∞ / +∞)')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('open_at')
                    ->columnSpan(1)
                    ->required(),
                Forms\Components\DateTimePicker::make('closes_at')
                    ->columnSpan(1),

                Forms\Components\RichEditor::make('notes')
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('images')
                    ->schema([
                        Forms\Components\TextInput::make('url')
                            ->label('Image URL')
                            ->required(),
                    ])
                    ->columnSpanFull(),
//                Forms\Components\Repeater::make('params')
//                    ->columns(2)
//                    ->defaultItems(0)
//                    ->schema([
//                        Forms\Components\TextInput::make('name')
//                            ->label('Name')
//                            ->columnSpan(1),
//                        Forms\Components\TextInput::make('value')
//                            ->label('Value')
//                            ->columnSpan(1),
//                    ])
//                    ->columnSpanFull(),
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
                                Infolists\Components\TextEntry::make('pnl')
                                    ->money('USD')
                                    ->badge()
                                    ->columnSpan(1)
                                    ->color(fn(string $state): string => $state == 0 ? 'gray' : ($state < 0 ? 'danger' : 'success')),
                                Infolists\Components\TextEntry::make('direction')
                                    ->badge()
                                    ->columnSpan(1)->color(fn(string $state): string => match ($state) {
                                        'buy' => 'success',
                                        'sell' => 'danger',
                                        default => 'gray',
                                    }),
                                Infolists\Components\TextEntry::make('symbol')
                                    ->badge()
                                    ->columnSpan(1),
                                Infolists\Components\TextEntry::make('notes')
                                    ->html()
                                    ->columnSpanFull(),

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
                        Tabs\Tab::make('Details')
                            ->schema([
                                Infolists\Components\TextEntry::make('open_at')
                                    ->columnSpan(1),
                                Infolists\Components\TextEntry::make('closes_at')
                                    ->columnSpan(1),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('symbol')
                    ->searchable(),
                Tables\Columns\TextColumn::make('direction')
                    ->color(fn(string $state): string => match ($state) {
                        'buy' => 'success',
                        'sell' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('pnl')
                    ->label('P/L')
                    ->color(fn(string $state): string => $state == 0 ? 'gray' : ($state < 0 ? 'danger' : 'success'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('open_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('closes_at')
                    ->dateTime()
                    ->sortable(),
//                Tables\Columns\TextColumn::make('created_at')
//                    ->dateTime()
//                    ->sortable()
//                    ->toggleable(isToggledHiddenByDefault: true),
//                Tables\Columns\TextColumn::make('updated_at')
//                    ->dateTime()
//                    ->sortable()
//                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListTrades::route('/'),
            'create' => Pages\CreateTrade::route('/create'),
            'view' => Pages\ViewTrade::route('/{record}'),
            'edit' => Pages\EditTrade::route('/{record}/edit'),
        ];
    }
}
