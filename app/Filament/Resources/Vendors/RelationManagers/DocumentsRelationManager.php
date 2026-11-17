<?php

namespace App\Filament\Resources\Vendors\RelationManagers;

use App\Models\Vendor;
use App\Models\VendorDocument;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $title = 'Dokumen kerja sama';

    protected static string|\BackedEnum|null $icon = Heroicon::OutlinedDocumentText;

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        if (! $ownerRecord instanceof Vendor) {
            return null;
        }

        $count = $ownerRecord->documents()->count();

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
                TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                FileUpload::make('file_path')
                    ->label('Berkas')
                    ->disk('local')
                    ->directory('vendor-documents')
                    ->visibility('private')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    ])
                    ->maxSize(10240)
                    ->required()
                    ->storeFileNamesIn('original_name')
                    ->helperText('PDF, gambar, atau dokumen Word. Maksimum 10 MB.')
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
            ->recordTitleAttribute('title')
            ->modelLabel('dokumen')
            ->pluralModelLabel('dokumen')
            ->stackedOnMobile()
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable()->wrap(),
                TextColumn::make('original_name')->label('Berkas')->placeholder('—')->wrap(),
                TextColumn::make('created_at')->label('Diunggah')->dateTime('d M Y H:i'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                Action::make('download')
                    ->label('Unduh')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->visible(fn (VendorDocument $record): bool => filled($record->file_path) && Storage::disk('local')->exists($record->file_path))
                    ->action(fn (VendorDocument $record) => Storage::disk('local')->download(
                        $record->file_path,
                        $record->original_name ?: basename($record->file_path),
                    )),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
