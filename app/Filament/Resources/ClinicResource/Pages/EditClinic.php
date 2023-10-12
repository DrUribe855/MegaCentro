<?php

namespace App\Filament\Resources\ClinicResource\Pages;

use App\Filament\Resources\ClinicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClinic extends EditRecord
{
    protected static string $resource = ClinicResource::class;
    protected static ?string $title = 'Editar Consultorios';
    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make()->label('Editar Consultorio'),
        ];
    }
}
