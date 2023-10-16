<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //Campo de documento
                Forms\Components\TextInput::make('document')
                ->numeric()
                ->required()
                ->maxLength('15')
                ->label('Documento'),
                //Campo de nombres
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength('255')
                ->label('Nombres'),
                //Campo de apellidos
                Forms\Components\TextInput::make('last_name')
                ->required()
                ->maxLength('255')
                ->label('Apellidos'),
                //Campo de télefono
                Forms\Components\TextInput::make('phone')
                ->required()
                ->maxLength('11')
                ->label('Teléfono'),
                //Campo de correo electronico
                Forms\Components\TextInput::make('email')
                ->required()
                ->maxLength('255')
                ->label('Correo electronico'),
                //Campo de constrasenia
                Forms\Components\TextInput::make('password')
                ->required()
                ->password()
                ->label('Contraseña'),
                //Campo de rol
                Forms\Components\Select::make('rol')
                ->options([
                    'administrator' => 'Administrador',
                    'assistant' => 'Auxiliar contable',
                    'collector' => 'Recolector',
                ])
                ->required()
                ->label('Rol'),
                //Campo de estado de usuario
                // Forms\Components\Select::make('status')
                // ->options([
                //     'enabled' => 'Activo',
                //     'disabled' => 'Inactivo',
                // ])
                // ->required()
                // ->label('Estado'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('document')
                ->searchable()
                ->label('Documento'),
                Tables\Columns\TextColumn::make('name')
                ->label('Nombres'),
                Tables\Columns\TextColumn::make('last_name')
                ->label('Apellidos'),
                Tables\Columns\TextColumn::make('phone')
                ->label('Teléfono'),
                Tables\Columns\TextColumn::make('email')
                ->label('Correo electronico'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // public function save()
    // {
    //     $this->password = password_hash($this->password, PASSWORD_DEFAULT);

    //     parent::save();
    // }
}
