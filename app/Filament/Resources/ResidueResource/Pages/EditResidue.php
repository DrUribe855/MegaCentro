<?php

namespace App\Filament\Resources\ResidueResource\Pages;

use App\Filament\Resources\ResidueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResidue extends EditRecord
{
    protected static string $resource = ResidueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
