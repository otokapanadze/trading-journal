<?php

namespace App\Filament\Resources\SessionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TradesRelationManager extends RelationManager
{
    protected static string $relationship = 'trades';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('symbol_id')
                    ->relationship('symbol', 'name')
                    ->searchable()
                    ->preload()
                    ->default(function () {
                        return Auth::user()->currentAccount()->symbol_id;
                    })
                    ->columnSpan(2)
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->editOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->required(),
                Forms\Components\Radio::make('direction')
                    ->columnSpan(2)
                    ->options([
                        'buy' => 'Buy 📈',
                        'sell' => 'Sell 📉',
                    ])
                    ->default('buy')
                    ->required(),
                Forms\Components\TextInput::make('pnl')
                    ->columnSpan(2)
                    ->label('Profit/Loss (-∞ / +∞)')
                    ->required()
                    ->numeric()
                ,

                Forms\Components\RichEditor::make('notes')
                    ->columnSpanFull(),
                Forms\Components\Select::make('strategies')
                    ->relationship('strategies', 'name')
                    ->multiple()
                    ->preload()
                    ->default(function () {
                        return Auth::user()->currentAccount()->strategies->pluck('id')->toArray();
                    })
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\RichEditor::make('description')
                            ->label('Description')
                            ->columnSpanFull(),
                    ])
                    ->editOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\RichEditor::make('description')
                            ->label('Description')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('images')
                    ->defaultItems(0)
                    ->schema([
                        Forms\Components\TextInput::make('url')
                            ->label('Image URL')
                            ->required(),
                    ])
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('open_at')
                    ->default(now()->subMinutes(10))
                    ->columnSpan(3),
                Forms\Components\DateTimePicker::make('closes_at')
                    ->default(now())
                    ->columnSpan(3),
                Forms\Components\TextInput::make('account_id')
                    ->formatStateUsing(fn() => auth()->user()->currentAccount()->id)
                ->extraFieldWrapperAttributes(['class' => 'hidden']),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('pnl')
            ->columns([
                Tables\Columns\TextColumn::make('open_at')
                    ->label('Open At')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('pnl')
                    ->label('P/L')
                    ->color(fn(string $state): string => $state == 0 ? 'gray' : ($state < 0 ? 'danger' : 'success'))
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('imagess')
                    ->label('Images')
                    ->getStateUsing(function (Model $record) {
                        if (!isset($record->images[0]['url'])) return null;
                        return $record->images[0]['url'];
                    })
                    ->width(200)
                    ->wrap()
                    ->square()
                    ->extraImgAttributes([
                        'img' => 'src'
                    ])
                ,
                Tables\Columns\TextColumn::make('symbol.name')
                    ->sortable()
                    ->searchable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('direction')
                    ->sortable()
                    ->searchable()
                    ->color(fn(string $state): string => match ($state) {
                        'buy' => 'success',
                        'sell' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->icon('heroicon-o-play')
                    ->label('')
                    ->action(function ($record) {
                        $this->preview($record);
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function preview($record)
    {
        $diff = $this->getOwnerRecord()->start->diffInSeconds($record->open_at);

        $this->js('document.querySelector("video").currentTime = "' . ($diff - 2) . '";');
    }
}
