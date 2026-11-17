<?php

namespace App\Filament\Resources\Vendors;

use App\Enums\VendorCategory;
use App\Filament\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\Vendors\Pages\CreateVendor;
use App\Filament\Resources\Vendors\Pages\EditVendor;
use App\Filament\Resources\Vendors\Pages\ListVendors;
use App\Filament\Resources\Vendors\Pages\ViewVendor;
use App\Filament\Resources\Vendors\RelationManagers\DocumentsRelationManager;
use App\Filament\Resources\Vendors\RelationManagers\ItemsRelationManager;
use App\Models\Vendor;
use App\Support\Money;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static ?string $navigationLabel = 'Vendor';

    protected static ?string $modelLabel = 'vendor';

    protected static ?string $pluralModelLabel = 'Vendor';

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data vendor')
                    ->schema([
                        TextInput::make('name')->label('Nama')->required()->maxLength(255),
                        Select::make('category')->label('Kategori')->options(VendorCategory::options())->required()->native(false),
                        TextInput::make('contact_person')->label('Narahubung')->maxLength(255),
                        TextInput::make('phone')->label('Telepon')->tel()->maxLength(50),
                        TextInput::make('email')->label('Email')->email()->maxLength(255),
                        Textarea::make('address')->label('Alamat')->rows(3)->columnSpanFull(),
                    ])
                    ->columns(['default' => 1, 'md' => 2]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data vendor')
                    ->schema([
                        TextEntry::make('name')->label('Nama'),
                        TextEntry::make('category')->label('Kategori')->badge()->formatStateUsing(fn (VendorCategory $state): string => $state->label()),
                        TextEntry::make('contact_person')->label('Narahubung')->placeholder('-'),
                        TextEntry::make('phone')->label('Telepon')->placeholder('-'),
                        TextEntry::make('email')->label('Email')->placeholder('-'),
                        TextEntry::make('address')->label('Alamat')->placeholder('-')->columnSpanFull(),
                        TextEntry::make('gross_profit_total')
                            ->label('Laba kotor')
                            ->state(fn (Vendor $record): string => Money::rupiah($record->grossProfitTotal()))
                            ->columnSpanFull(),
                    ])
                    ->columns(['default' => 1, 'md' => 2]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->stackedOnMobile()
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->withSum('items as gross_profit_total', 'gross_profit'))
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable()->wrap(),
                TextColumn::make('category')->label('Kategori')->badge()->formatStateUsing(fn (VendorCategory $state): string => $state->label()),
                TextColumn::make('contact_person')->label('Narahubung')->searchable()->wrap()->toggleable(),
                TextColumn::make('phone')->label('Telepon')->searchable()->toggleable(),
                TextColumn::make('email')->label('Email')->searchable()->wrap()->toggleable(),
                TextColumn::make('gross_profit_total')->label('Laba kotor')->formatStateUsing(fn ($state): string => Money::rupiah($state)),
            ])
            ->filters([
                SelectFilter::make('category')->label('Kategori')->options(VendorCategory::options()),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
            DocumentsRelationManager::class,
            ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVendors::route('/'),
            'create' => CreateVendor::route('/create'),
            'view' => ViewVendor::route('/{record}'),
            'edit' => EditVendor::route('/{record}/edit'),
        ];
    }
}
