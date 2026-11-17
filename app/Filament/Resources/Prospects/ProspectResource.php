<?php

namespace App\Filament\Resources\Prospects;

use App\Enums\CustomerType;
use App\Enums\ProspectStatus;
use App\Filament\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\Customers\CustomerResource;
use App\Filament\Resources\Prospects\Pages\CreateProspect;
use App\Filament\Resources\Prospects\Pages\EditProspect;
use App\Filament\Resources\Prospects\Pages\ListProspects;
use App\Filament\Resources\Prospects\Pages\ViewProspect;
use App\Models\Customer;
use App\Models\Prospect;
use App\Modules\MasterData\Actions\ConvertProspectToCustomer;
use Filament\Actions\Action;
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

class ProspectResource extends Resource
{
    protected static ?string $model = Prospect::class;

    protected static ?string $navigationLabel = 'Prospek';

    protected static ?string $modelLabel = 'prospek';

    protected static ?string $pluralModelLabel = 'Prospek';

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUserPlus;

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data prospek')
                    ->description('Calon klien dicatat di sini sebelum menjadi pelanggan.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        Select::make('type')
                            ->label('Jenis')
                            ->options(CustomerType::options())
                            ->required()
                            ->native(false),
                        TextInput::make('phone')
                            ->label('Telepon')
                            ->tel()
                            ->maxLength(50),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('event_interest')
                            ->label('Minat acara')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Select::make('status')
                            ->label('Status')
                            ->options(fn (?Prospect $record): array => $record?->customer_id
                                ? ProspectStatus::options()
                                : ProspectStatus::openOptions())
                            ->default(ProspectStatus::New->value)
                            ->disabled(fn (?Prospect $record): bool => filled($record?->customer_id))
                            ->required()
                            ->native(false),
                        Textarea::make('address')
                            ->label('Alamat')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(['default' => 1, 'md' => 2]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data prospek')
                    ->schema([
                        TextEntry::make('name')->label('Nama'),
                        TextEntry::make('type')->label('Jenis')->badge()->formatStateUsing(fn (CustomerType $state): string => $state->label()),
                        TextEntry::make('phone')->label('Telepon')->placeholder('—'),
                        TextEntry::make('email')->label('Email')->placeholder('—'),
                        TextEntry::make('event_interest')->label('Minat acara')->placeholder('—')->columnSpanFull(),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn (ProspectStatus $state): string => $state->label())
                            ->color(fn (ProspectStatus $state): string => $state->color()),
                        TextEntry::make('customer.name')
                            ->label('Pelanggan')
                            ->placeholder('Belum menjadi pelanggan')
                            ->url(fn (Prospect $record): ?string => $record->customer
                                ? CustomerResource::getUrl('view', ['record' => $record->customer])
                                : null),
                        TextEntry::make('address')->label('Alamat')->placeholder('—')->columnSpanFull(),
                        TextEntry::make('notes')->label('Catatan')->placeholder('—')->columnSpanFull(),
                    ])
                    ->columns(['default' => 1, 'md' => 2]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->stackedOnMobile()
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable()->wrap(),
                TextColumn::make('event_interest')->label('Minat acara')->wrap()->toggleable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (ProspectStatus $state): string => $state->label())
                    ->color(fn (ProspectStatus $state): string => $state->color()),
                TextColumn::make('phone')->label('Telepon')->searchable()->toggleable(),
                TextColumn::make('email')->label('Email')->searchable()->wrap()->toggleable(),
                TextColumn::make('customer.name')->label('Pelanggan')->placeholder('—')->wrap()->toggleable(),
                TextColumn::make('updated_at')->label('Diubah')->dateTime('d M Y H:i')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')->label('Status')->options(ProspectStatus::options()),
                SelectFilter::make('type')->label('Jenis')->options(CustomerType::options()),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                self::convertAction(),
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

    public static function convertAction(): Action
    {
        return Action::make('convert')
            ->label('Jadikan pelanggan')
            ->icon(Heroicon::OutlinedUserPlus)
            ->requiresConfirmation()
            ->modalHeading('Jadikan pelanggan')
            ->modalDescription('Nama, kontak, dan catatan prospek disalin menjadi pelanggan. Status prospek berubah menjadi sudah menjadi pelanggan.')
            ->visible(fn (Prospect $record): bool => self::canConvert($record))
            ->action(function (Prospect $record) {
                $customer = app(ConvertProspectToCustomer::class)->execute($record);

                return redirect(CustomerResource::getUrl('view', ['record' => $customer]));
            });
    }

    public static function canConvert(Prospect $record): bool
    {
        $user = auth()->user();

        return $record->customer_id === null
            && $user?->can('update', $record)
            && $user->can('create', Customer::class);
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
            'index' => ListProspects::route('/'),
            'create' => CreateProspect::route('/create'),
            'view' => ViewProspect::route('/{record}'),
            'edit' => EditProspect::route('/{record}/edit'),
        ];
    }
}
