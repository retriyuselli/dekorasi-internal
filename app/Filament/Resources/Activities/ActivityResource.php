<?php

namespace App\Filament\Resources\Activities;

use App\Filament\Resources\Activities\Pages\ListActivities;
use App\Filament\Resources\Activities\Pages\ViewActivity;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationLabel = 'Log aktivitas';

    protected static ?string $modelLabel = 'log aktivitas';

    protected static ?string $pluralModelLabel = 'Log aktivitas';

    protected static string|\UnitEnum|null $navigationGroup = 'Audit';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'log-aktivitas';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Perubahan')
                    ->schema([
                        TextEntry::make('log_name')->label('Log')->placeholder('-'),
                        TextEntry::make('event')->label('Peristiwa')->badge()->placeholder('-'),
                        TextEntry::make('description')->label('Deskripsi')->columnSpanFull(),
                        TextEntry::make('subject_type')->label('Subjek')->placeholder('-'),
                        TextEntry::make('subject_id')->label('ID subjek')->placeholder('-'),
                        TextEntry::make('causer.name')->label('Oleh')->placeholder('Sistem'),
                        TextEntry::make('created_at')->label('Waktu')->dateTime('d M Y H:i'),
                        TextEntry::make('properties')
                            ->label('Detail')
                            ->formatStateUsing(function (mixed $state): string {
                                if ($state instanceof Collection) {
                                    $state = $state->toArray();
                                }

                                $encoded = json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                                return $encoded === false || $encoded === 'null' ? '-' : $encoded;
                            })
                            ->columnSpanFull(),
                    ])
                    ->columns(['default' => 1, 'md' => 2]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->label('Waktu')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('event')->label('Peristiwa')->badge()->placeholder('-'),
                TextColumn::make('description')->label('Deskripsi')->wrap()->searchable(),
                TextColumn::make('subject_type')->label('Subjek')->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '-')->wrap(),
                TextColumn::make('causer.name')->label('Oleh')->placeholder('Sistem')->wrap(),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivities::route('/'),
            'view' => ViewActivity::route('/{record}'),
        ];
    }
}
