<?php

namespace App\Filament\Resources\Items;

use App\Filament\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\Items\Pages\CreateItem;
use App\Filament\Resources\Items\Pages\EditItem;
use App\Filament\Resources\Items\Pages\ListItems;
use App\Filament\Resources\Items\Pages\ViewItem;
use App\Models\Item;
use App\Support\Money;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationLabel = 'Barang';

    protected static ?string $modelLabel = 'barang';

    protected static ?string $pluralModelLabel = 'Barang';

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static ?int $navigationSort = 6;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data barang')
                    ->schema([
                        TextInput::make('name')->label('Nama')->required()->maxLength(255),
                        TextInput::make('code')
                            ->label('Kode')
                            ->required()
                            ->maxLength(30)
                            ->unique(ignoreRecord: true)
                            ->helperText('Disimpan dalam huruf besar.'),
                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),
                        Select::make('unit_id')
                            ->label('Satuan')
                            ->relationship('unit', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => $record->name.' ('.$record->symbol.')')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),
                        Select::make('vendor_id')
                            ->label('Pemasok')
                            ->relationship('vendor', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false),
                        Toggle::make('is_active')->label('Aktif')->default(true),
                        Textarea::make('notes')->label('Catatan')->rows(3)->columnSpanFull(),
                    ])
                    ->columns(['default' => 1, 'md' => 2]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data barang')
                    ->schema([
                        TextEntry::make('name')->label('Nama'),
                        TextEntry::make('code')->label('Kode'),
                        TextEntry::make('category.name')->label('Kategori'),
                        TextEntry::make('unit.name')->label('Satuan')->formatStateUsing(fn (Item $record): string => $record->unit ? $record->unit->name.' ('.$record->unit->symbol.')' : '—'),
                        TextEntry::make('vendor.name')->label('Pemasok')->placeholder('—'),
                        IconEntry::make('is_active')->label('Aktif')->boolean(),
                        TextEntry::make('stock_nominal')
                            ->label('Nominal tersimpan')
                            ->state(fn (Item $record): string => Money::rupiah($record->stocks()->sum('nominal'))),
                        TextEntry::make('notes')->label('Catatan')->placeholder('—')->columnSpanFull(),
                    ])
                    ->columns(['default' => 1, 'md' => 2]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->stackedOnMobile()
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->withSum('stocks as nominal_total', 'nominal'))
            ->columns([
                TextColumn::make('code')->label('Kode')->searchable(),
                TextColumn::make('name')->label('Nama')->searchable()->wrap(),
                TextColumn::make('category.name')->label('Kategori')->toggleable(),
                TextColumn::make('unit.symbol')->label('Satuan'),
                TextColumn::make('nominal_total')->label('Nominal')->state(fn (Item $record): string => Money::rupiah($record->getAttribute('nominal_total') ?? 0)),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Aktif'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()->before(function (Item $record, DeleteAction $action): void {
                    if ($record->stocks()->exists()) {
                        Notification::make()->title('Barang masih tersimpan di gudang.')->danger()->send();
                        $action->halt();
                    }
                }),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListItems::route('/'),
            'create' => CreateItem::route('/create'),
            'view' => ViewItem::route('/{record}'),
            'edit' => EditItem::route('/{record}/edit'),
        ];
    }
}
