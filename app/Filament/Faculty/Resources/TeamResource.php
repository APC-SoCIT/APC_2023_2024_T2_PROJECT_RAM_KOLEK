<?php

namespace App\Filament\Faculty\Resources;

use App\Filament\Faculty\Resources\TeamResource\Pages;
use App\Filament\Faculty\Resources\TeamResource\RelationManagers;
use App\Models\Team;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Foundation\Auth;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $professors = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('roles.name', 'Professor')
        ->pluck('users.email', 'users.id')
        ->toArray();

        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Owner')
                    ->relationship('owner','email')
                    ->searchable()
                    ->default(Auth()->user()->id)
                    ->required()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->label('Team Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('members')
                    ->label('Members')
                    ->placeholder('Select Members')
                    ->searchable()
                    ->multiple()
                    ->relationship('members','email')
                    ->preload(),
            ]);
    }


    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('owner.email')
                    ->label('Owner')
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
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        $roles = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('users.id', Auth()->id())
        ->pluck('roles.name')
        ->toArray();

        if ((in_array('PBL Coordinator', $roles))||(in_array('Admin', $roles))){
            return parent::getEloquentQuery();       
        }
        elseif ((in_array('Student', $roles))){
            return parent::getEloquentQuery()->whereJsonContains('members', strval(auth()->id()));     
        }

        return parent::getEloquentQuery()->where('user_id', Auth()->id());
    }
}
