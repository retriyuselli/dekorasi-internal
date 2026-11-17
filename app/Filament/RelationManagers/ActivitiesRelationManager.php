<?php

namespace App\Filament\RelationManagers;

use App\Filament\Resources\Activities\ActivityResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $relatedResource = ActivityResource::class;

    protected static ?string $title = 'Log aktivitas';

    protected static string|\BackedEnum|null $icon = Heroicon::OutlinedClipboardDocumentList;

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->stackedOnMobile()
            ->columns([
                TextColumn::make('created_at')->label('Waktu')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('event')->label('Peristiwa')->badge()->placeholder('-'),
                TextColumn::make('description')->label('Deskripsi')->wrap()->searchable(),
                TextColumn::make('causer.name')->label('Oleh')->placeholder('Sistem')->wrap(),
            ]);
    }
}
