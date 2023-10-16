<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClinicResource\Pages;
use App\Filament\Resources\ClinicResource\RelationManagers;
use App\Models\Clinic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClinicResource extends Resource
{
    protected static ?string $model = Clinic::class;
    protected static ?string $modelLabel = 'Consultorios';
    protected static ?string $navigationLabel = 'Consultorios';
    // protected static ?string $navigationGroup = 'Consultorios';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')->label('Nombre Del Ususario')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('clinic_number')->label('Numero Del Consultorio')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')->label('Estado')
                    ->required()
                    ->maxLength(255),
                
            ]);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Usuarios')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('clinic_number')->label('Numero Consultorio')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')->label('Estado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
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
            'index' => Pages\ListClinics::route('/'),
            'create' => Pages\CreateClinic::route('/create'),
            'edit' => Pages\EditClinic::route('/{record}/edit'),
        ];
    }    
}
