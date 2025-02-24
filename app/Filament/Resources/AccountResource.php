<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Filament\Resources\AccountResource\RelationManagers;
use App\Models\Account;
use App\Models\Trade;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required(),
                Forms\Components\TextInput::make('balance')
                    ->numeric()
                    ->label('Balance (Initial)')
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->label('Description')->columnSpanFull(),
                Forms\Components\Select::make('symbol_id')
                    ->relationship('symbol', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Default Symbol')
                    ->required(),
                Forms\Components\Select::make('strategies')
                    ->relationship('strategies', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->label('Default Strategies'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Name'),
                Tables\Columns\TextColumn::make('current')
                    ->badge()
                    ->color('success')
                    ->state(fn(Account $record) => $record->user->current_account_id === $record->id ? 'Current Account' : null)
                    ->label('Current Account'),
                Tables\Columns\TextColumn::make('current_balance')
                    ->badge()
                    ->money()
                    ->label('Balance')
                    ->state(fn(Model $record) => $record->balance + ($record->trades()->sum('pnl') ?? 0)),
                Tables\Columns\TextColumn::make('trades')
                    ->label('Trades')
                    ->state(fn(Model $record) => Trade::where('account_id', $record->id)->count())
                ,
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('set_current_account')
                    ->disabled(fn(Account $record) => $record->user->current_account_id === $record->id)
                    ->accessSelectedRecords()
                    ->action(function (Account $record) {
                        $record->user()->update(['current_account_id' => $record->id]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(3)
            ->schema([
                Infolists\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Name'),
                        Infolists\Components\TextEntry::make('description')
                            ->label('Description'),
                        Infolists\Components\TextEntry::make('current_balance')
                            ->label('Balance')
                            ->state(fn(Model $record) => $record->balance + ($record->trades()->sum('pnl') ?? 0))
                            ->badge()
                            ->money(),
                        Infolists\Components\TextEntry::make('trades')->state(fn(Model $record) => $record->trades()->count() ?? 0),
                        Infolists\Components\TextEntry::make('winning_trades')->state(fn(Model $record) => $record->trades()->where('pnl', '>', 0)->count() ?? 0),
                        Infolists\Components\TextEntry::make('losing_trades')->state(fn(Model $record) => $record->trades()->where('pnl', '<', 0)->count() ?? 0),
                        Infolists\Components\TextEntry::make('win-loss ratio')->state(function (Model $record) {
                            $total = $record->trades()->count();
                            if ($total === 0) return '--';
                            $winning = $record->trades()->where('pnl', '>', 0)->count() ?? 0;
                            return number_format((($winning / $total) * 100), 2) . '%';
                        }),
                        Infolists\Components\TextEntry::make('max_drawdown')->state(function (Model $record) {
                            $trades = $record->trades()->orderBy('created_at')->get(['pnl']);

                            if ($trades->isEmpty()) {
                                return '--';
                            }

                            $balance = $record->balance;
                            $peak = $balance;
                            $max_drawdown = 0;

                            foreach ($trades as $trade) {
                                $balance += $trade->pnl;
                                $peak = max($peak, $balance);
                                $drawdown = ($peak - $balance) / max($peak, 1) * 100;
                                $max_drawdown = max($max_drawdown, $drawdown);
                            }

                            return number_format($max_drawdown, 2) . '%';
                        }),
                        Infolists\Components\TextEntry::make('balance')
                            ->label('Balance (Initial)')
                            ->badge()
                            ->money(),
                    ])
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
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'view' => Pages\ViewAccount::route('/{record}'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
