<?php

namespace App\Filament\Resources\Warehouses\RelationManagers;

use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class LocationsRelationManager extends RelationManager
{
    protected static string $relationship = 'locations';

    protected static ?string $title = 'Penempatan';

    protected static string|\BackedEnum|null $icon = Heroicon::OutlinedMapPin;

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        if (! $ownerRecord instanceof Warehouse) {
            return null;
        }

        $count = $ownerRecord->locations()->count();

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
                TextInput::make('name')->label('Nama')->required()->maxLength(255)->columnSpanFull(),
                TextInput::make('code')
                    ->label('Kode')
                    ->required()
                    ->maxLength(30)
                    ->helperText('Disimpan dalam huruf besar.'),
                Textarea::make('notes')->label('Catatan')->rows(2)->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->modelLabel('penempatan')
            ->pluralModelLabel('penempatan')
            ->stackedOnMobile()
            ->columns([
                TextColumn::make('code')->label('Kode')->searchable(),
                TextColumn::make('name')->label('Nama')->searchable()->wrap(),
                TextColumn::make('notes')->label('Catatan')->placeholder('—')->wrap()->toggleable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->before(function (WarehouseLocation $record, DeleteAction $action): void {
                    if ($record->stocks()->exists()) {
                        Notification::make()->title('Penempatan masih berisi barang.')->danger()->send();
                        $action->halt();
                    }
                }),
            ]);
    }
}
