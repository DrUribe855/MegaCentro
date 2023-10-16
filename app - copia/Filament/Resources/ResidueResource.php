<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResidueResource\Pages;
use App\Filament\Resources\ResidueResource\RelationManagers;
use App\Models\Residue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResidueResource extends Resource
{
    protected static ?string $model = Residue::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('residue_type_id')->label('Categoria Del Residuo')
                    ->relationship('residue_type', 'residue_type')
                    ->required(),
                Forms\Components\Select::make('residue_name')->label('Nombre Del Ususario')
                    ->options([
                        'Biodegradables' => 'Biodegradables',
                        'Reciclables' => 'Reciclables',
                        'Inertes' => 'Inertes',
                        'Ordinarios' => 'Ordinarios',
                        'Biosanitarios' => 'Biosanitarios',
                        'Anatomopafológicos' => 'Anatomopafológicos',
                        'Cortupunzantes' => 'Cortupunzantes',
                        'Animales' => 'Animales',
                        'Farmacos' => 'Farmacos',
                        'Citotóxicos' => 'Citotóxicos',
                        'Metales Pesados' => 'Metales Pesados',
                        'Reactivos' => 'Reactivos',
                        'Contenedores Presurizados' => 'Contenedores Presurizados',
                        'Aceites Usados' => 'Aceites Usados',
                        'Fuestes Abiesrtas' => 'Fuestes Abiesrtas',
                        'Fuentes Cerradas' => 'Fuentes Cerradas'  
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('residue_type.residue_category')->label('Categoria')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('residue_type.residue_type')->label('Tipo')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('residue_name')->label('Nombre')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResidues::route('/'),
            'create' => Pages\CreateResidue::route('/create'),
            'edit' => Pages\EditResidue::route('/{record}/edit'),
        ];
    }    
}
