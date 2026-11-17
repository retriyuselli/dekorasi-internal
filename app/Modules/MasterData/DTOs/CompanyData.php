<?php

namespace App\Modules\MasterData\DTOs;

final class CompanyData
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $legalName,
        public readonly ?string $email,
        public readonly ?string $phone,
        public readonly ?string $address,
        public readonly ?string $logoPath,
        public readonly ?string $taxId,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $logo = $data['logo_path'] ?? null;

        if (is_array($logo)) {
            $logo = $logo[0] ?? null;
        }

        return new self(
            name: (string) $data['name'],
            legalName: filled($data['legal_name'] ?? null) ? (string) $data['legal_name'] : null,
            email: filled($data['email'] ?? null) ? (string) $data['email'] : null,
            phone: filled($data['phone'] ?? null) ? (string) $data['phone'] : null,
            address: filled($data['address'] ?? null) ? (string) $data['address'] : null,
            logoPath: filled($logo) ? (string) $logo : null,
            taxId: filled($data['tax_id'] ?? null) ? (string) $data['tax_id'] : null,
        );
    }

    /**
     * @return array<string, string|null>
     */
    public function toAttributes(): array
    {
        return [
            'name' => $this->name,
            'legal_name' => $this->legalName,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'logo_path' => $this->logoPath,
            'tax_id' => $this->taxId,
        ];
    }
}
