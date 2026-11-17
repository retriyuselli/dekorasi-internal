<?php

namespace App\Filament\Resources\Warehouses;

use App\Filament\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\Warehouses\Pages\CreateWarehouse;
use App\Filament\Resources\Warehouses\Pages\EditWarehouse;
use App\Filament\Resources\Warehouses\Pages\ListWarehouses;
use App\Filament\Resources\Warehouses\Pages\ViewWarehouse;
use App\Filament\Resources\Warehouses\RelationManagers\LocationsRelationManager;
use App\Filament\Resources\Warehouses\RelationManagers\StocksRelationManager;
use App\Models\Warehouse;
use App\Support\Money;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WarehouseResource extends Resource
{
    protected static ?string $model = Warehouse::class;

    protected static ?string $navigationLabel = 'Gudang';

    protected static ?string $modelLabel = 'gudang';

    protected static ?string $pluralModelLabel = 'Gudang';

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?int $navigationSort = 7;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data gudang')
                    ->schema([
                        TextInput::make('name')->label('Nama')->required()->maxLength(255),
                        TextInput::make('code')->label('Kode')->required()->maxLength(30)->unique(ignoreRecord: true),
                        Textarea::make('address')->label('Alamat')->rows(3)->columnSpanFull(),
                        Toggle::make('is_active')->label('Aktif')->default(true),
                    ])
                    ->columns(['default' => 1, 'md' => 2]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data gudang')
                    ->schema([
                        TextEntry::make('name')->label('Nama'),
                        TextEntry::make('code')->label('Kode'),
                        TextEntry::make('address')->label('Alamat')->placeholder('-')->columnSpanFull(),
                        IconEntry::make('is_active')->label('Aktif')->boolean(),
                        TextEntry::make('item_count')
                            ->label('Jenis barang')
                            ->state(fn (Warehouse $record): string => (string) $record->itemCount()),
                        TextEntry::make('nominal_total')
                            ->label('Nominal')
                            ->state(fn (Warehouse $record): string => Money::rupiah($record->nominalTotal())),
                    ])
                    ->columns(['default' => 1, 'md' => 2]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->stackedOnMobile()
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->withSum('stocks as nominal_total', 'nominal')->withCount('stocks'))
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable()->wrap(),
                TextColumn::make('code')->label('Kode')->searchable(),
                TextColumn::make('stocks_count')->label('Baris isi')->toggleable(),
                TextColumn::make('nominal_total')->label('Nominal')->state(fn (Warehouse $record): string => Money::rupiah($record->getAttribute('nominal_total') ?? 0)),
                TextColumn::make('address')->label('Alamat')->wrap()->toggleable(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Aktif'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            LocationsRelationManager::class,
            StocksRelationManager::class,
            ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWarehouses::route('/'),
            'create' => CreateWarehouse::route('/create'),
            'view' => ViewWarehouse::route('/{record}'),
            'edit' => EditWarehouse::route('/{record}/edit'),
        ];
    }
}
