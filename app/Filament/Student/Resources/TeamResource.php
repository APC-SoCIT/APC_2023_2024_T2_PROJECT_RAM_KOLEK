<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\TeamResource\Pages;
use App\Filament\Student\Resources\TeamResource\RelationManagers;
use App\Models\Team;
use App\Models\UserTeam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
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
        $teams = UserTeam::where('user_id', auth()->id())->pluck('team_id')->toArray();
        return parent::getEloquentQuery()->whereIn('id', $teams);
    }
}
