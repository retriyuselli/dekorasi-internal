<?php

namespace App\Filament\Pages;

use App\Models\Company;
use App\Models\User;
use App\Modules\MasterData\Actions\ResolveCompany;
use App\Modules\MasterData\Actions\SaveCompany;
use App\Support\ShieldPermission;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Livewire\Attributes\Locked;

/**
 * @property-read Schema $form
 */
class CompanySettings extends Page
{
    #[Locked]
    public ?int $companyId = null;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    protected static ?string $navigationLabel = 'Perusahaan';

    protected static ?string $title = 'Pengaturan perusahaan';

    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'pengaturan-perusahaan';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->can(ShieldPermission::make('view', 'Company'));
    }

    public function mount(ResolveCompany $resolveCompany): void
    {
        $company = $resolveCompany->execute();
        $this->companyId = $company->id;
        $this->form->fill($company->attributesToArray());
    }

    public function save(SaveCompany $saveCompany): void
    {
        $company = Company::query()->findOrFail($this->companyId);
        abort_unless(auth()->user()?->can('update', $company), 403);

        $saveCompany->execute($this->form->getState());

        Notification::make()
            ->title('Pengaturan perusahaan disimpan')
            ->success()
            ->send();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas perusahaan')
                    ->schema([
                        TextInput::make('name')->label('Nama')->required()->maxLength(255),
                        TextInput::make('legal_name')->label('Nama legal')->maxLength(255),
                        TextInput::make('email')->label('Email')->email()->maxLength(255),
                        TextInput::make('phone')->label('Telepon')->tel()->maxLength(50),
                        TextInput::make('tax_id')->label('NPWP')->maxLength(50),
                        Textarea::make('address')->label('Alamat')->rows(3)->columnSpanFull(),
                        FileUpload::make('logo_path')
                            ->label('Logo')
                            ->image()
                            ->disk('public')
                            ->directory('company-logos')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ])
                    ->columns(['default' => 1, 'md' => 2]),
            ])
            ->statePath('data')
            ->model(Company::class);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Simpan')
                                ->submit('save')
                                ->visible(function (): bool {
                                    $company = Company::query()->find($this->companyId);

                                    return $company !== null && (auth()->user()?->can('update', $company) ?? false);
                                }),
                        ])->key('form-actions'),
                    ]),
            ]);
    }
}
