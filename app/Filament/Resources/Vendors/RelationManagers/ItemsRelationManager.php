<?php

namespace App\Filament\Resources\Vendors\RelationManagers;

use App\Filament\Forms\MoneyInput;
use App\Models\Unit;
use App\Models\Vendor;
use App\Models\VendorItem;
use App\Support\Money;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Harga';

    protected static string|\BackedEnum|null $icon = Heroicon::OutlinedBanknotes;

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        if (! $ownerRecord instanceof Vendor) {
            return null;
        }

        $count = $ownerRecord->items()->count();

        return $count > 0 ? (string) $count : null;
    }

    public function isReadOnly(): bool
    {
        $owner = $this->getOwnerRecord();

        return ! ($owner instanceof Vendor && (auth()->user()?->can('update', $owner) ?? false));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'md' => 2])
            ->components([
                TextInput::make('name')
                    ->label('Barang atau jasa')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Select::make('unit_id')
                    ->label('Satuan')
                    ->relationship('unit', 'name')
                    ->getOptionLabelFromRecordUsing(fn (Unit $record): string => $record->name.' ('.$record->symbol.')')
                    ->searchable()
                    ->preload()
                    ->native(false),
                TextInput::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->default(1)
                    ->live(onBlur: true),
                MoneyInput::make('cost_price')
                    ->label('Harga modal')
                    ->required()
                    ->live(onBlur: true),
                MoneyInput::make('sell_price')
                    ->label('Harga jual')
                    ->required()
                    ->live(onBlur: true),
                Placeholder::make('gross_profit_preview')
                    ->label('Laba kotor')
                    ->content(fn (Get $get): string => Money::rupiah(VendorItem::calculateGrossProfit(
                        $get('sell_price'),
                        $get('cost_price'),
                        $get('quantity'),
                    )))
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->modelLabel('harga')
            ->pluralModelLabel('harga')
            ->stackedOnMobile()
            ->description(fn (): string => 'Total laba kotor '.Money::rupiah($this->getOwnerRecord()->items()->sum('gross_profit')))
            ->columns([
                TextColumn::make('name')->label('Barang atau jasa')->searchable()->wrap(),
                TextColumn::make('quantity')->label('Jumlah')->formatStateUsing(fn (VendorItem $record): string => self::quantityLabel($record)),
                TextColumn::make('cost_price')->label('Harga modal')->formatStateUsing(fn ($state): string => Money::rupiah($state)),
                TextColumn::make('sell_price')->label('Harga jual')->formatStateUsing(fn ($state): string => Money::rupiah($state)),
                TextColumn::make('gross_profit')->label('Laba kotor')->formatStateUsing(fn ($state): string => Money::rupiah($state)),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    private static function quantityLabel(VendorItem $record): string
    {
        $quantity = rtrim(rtrim(number_format((float) $record->quantity, 2, ',', '.'), '0'), ',');
        $symbol = $record->unit?->symbol;

        return $symbol ? $quantity.' '.$symbol : $quantity;
    }
}
