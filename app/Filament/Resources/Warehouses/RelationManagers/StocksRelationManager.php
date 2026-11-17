<?php

namespace App\Filament\Resources\Warehouses\RelationManagers;

use App\Enums\StockCondition;
use App\Filament\Forms\MoneyInput;
use App\Models\Item;
use App\Models\Stock;
use App\Models\Warehouse;
use App\Support\Money;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class StocksRelationManager extends RelationManager
{
    protected static string $relationship = 'stocks';

    protected static ?string $title = 'Isi gudang';

    protected static string|\BackedEnum|null $icon = Heroicon::OutlinedArchiveBox;

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        if (! $ownerRecord instanceof Warehouse) {
            return null;
        }

        $count = $ownerRecord->itemCount();

        return $count > 0 ? (string) $count : null;
    }

    public function isReadOnly(): bool
    {
        $owner = $this->getOwnerRecord();

        return ! ($owner instanceof Warehouse && (auth()->user()?->can('update', $owner) ?? false));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'md' => 2])
            ->components([
                Select::make('item_id')
                    ->label('Barang')
                    ->relationship(
                        'item',
                        'name',
                        fn (Builder $query, Select $component): Builder => $query->unassigned(
                            $component->getRecord() instanceof Stock ? $component->getRecord()->getKey() : null,
                        ),
                    )
                    ->getOptionLabelFromRecordUsing(fn (Item $record): string => $record->code.' — '.$record->name)
                    ->helperText('Hanya barang yang belum tersimpan di gudang.')
                    ->searchable(['name', 'code'])
                    ->preload()
                    ->required()
                    ->native(false)
                    ->columnSpanFull(),
                Select::make('warehouse_location_id')
                    ->label('Penempatan')
                    ->options(function (): array {
                        $owner = $this->getOwnerRecord();

                        if (! $owner instanceof Warehouse) {
                            return [];
                        }

                        return $owner->locations()->orderBy('name')->pluck('name', 'id')->all();
                    })
                    ->searchable()
                    ->native(false),
                Select::make('condition')
                    ->label('Kondisi')
                    ->options(StockCondition::options())
                    ->default(StockCondition::Good->value)
                    ->required()
                    ->native(false),
                TextInput::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->default(1)
                    ->live(onBlur: true),
                MoneyInput::make('purchase_price')
                    ->label('Harga beli awal')
                    ->required()
                    ->live(onBlur: true),
                DatePicker::make('purchased_at')->label('Tanggal beli')->native(false),
                Placeholder::make('nominal_preview')
                    ->label('Nominal')
                    ->content(fn (Get $get): string => Money::rupiah(Stock::calculateNominal($get('quantity'), $get('purchase_price'))))
                    ->columnSpanFull(),
                Textarea::make('notes')->label('Catatan')->rows(2)->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->modelLabel('isi gudang')
            ->pluralModelLabel('isi gudang')
            ->stackedOnMobile()
            ->description(fn (): string => 'Nominal gudang '.Money::rupiah($this->getOwnerRecord()->stocks()->sum('nominal')))
            ->columns([
                TextColumn::make('item.name')->label('Barang')->searchable()->wrap(),
                TextColumn::make('location.name')->label('Penempatan')->placeholder('—')->wrap(),
                TextColumn::make('quantity')->label('Jumlah')->formatStateUsing(fn (Stock $record): string => self::quantityLabel($record)),
                TextColumn::make('purchase_price')->label('Harga beli awal')->formatStateUsing(fn ($state): string => Money::rupiah($state)),
                TextColumn::make('nominal')->label('Nominal')->formatStateUsing(fn ($state): string => Money::rupiah($state)),
                TextColumn::make('condition')
                    ->label('Kondisi')
                    ->badge()
                    ->formatStateUsing(fn (StockCondition $state): string => $state->label())
                    ->color(fn (StockCondition $state): string => $state->color()),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    private static function quantityLabel(Stock $record): string
    {
        $quantity = rtrim(rtrim(number_format((float) $record->quantity, 2, ',', '.'), '0'), ',');
        $symbol = $record->item?->unit?->symbol;

        return $symbol ? $quantity.' '.$symbol : $quantity;
    }
}
