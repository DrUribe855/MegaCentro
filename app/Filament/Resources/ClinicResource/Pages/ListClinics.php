<?php

namespace App\Filament\Resources\ClinicResource\Pages;

use App\Filament\Resources\ClinicResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClinics extends ListRecords
{
    protected static string $resource = ClinicResource::class;
    protected static ?string $title = 'Lista De los Consultorios';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Rgistrar Un Consultorio'),
        ];
    }
}
