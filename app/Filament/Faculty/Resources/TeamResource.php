<?php

namespace App\Filament\Faculty\Resources;

use App\Filament\Faculty\Resources\TeamResource\Pages;
use App\Filament\Faculty\Resources\TeamResource\RelationManagers;
use App\Models\Team;
use App\Models\User;
use App\Models\UserTeam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $startYear = Carbon::now()->year;
        $endYear = $startYear+5;
        $academicYears = [];

        for ($year = $startYear; $year <= $endYear; $year++) {
            $academicYears["{$year}-" . ($year + 1)] = "{$year}-" . ($year + 1);
        }
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Owner')
                    ->relationship('getAllowed','email')
                    ->searchable()
                    ->default(Auth()->user()->id)
                    ->required()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->label('Team Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('school')
                    ->placeholder('Select School')
                    ->options([
                        'School of Computing and Information Technology'  => 'School of Computing and Information Technology' 
                    ])
                    ->required(),
                Forms\Components\Select::make('program')
                ->placeholder('Select Program')
                ->options([
                    'BS Computer Science' => 'BS Computer Science',
                    'BS Information Technology' => 'BS Information Technology' 
                ])
                ->required(),
                Forms\Components\Select::make('academic_year')
                ->label('Academic Year:')
                ->default("{$startYear}-" . ($startYear + 1))
                ->options($academicYears)
                ->required(),
                Forms\Components\TextInput::make('section')
                ->maxLength(255)
                ->required(),
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
                Tables\Columns\TextColumn::make('school')
                    ->searchable(),
                Tables\Columns\TextColumn::make('program')
                    ->searchable(),
                Tables\Columns\TextColumn::make('section')
                    ->searchable(),
                Tables\Columns\TextColumn::make('academic_year')
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

            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ->hidden(fn ($record) => $record->trashed()),
                Tables\Actions\EditAction::make()
                ->hidden(fn ($record) => $record->trashed()),
                Tables\Actions\RestoreAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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

        if ((in_array('Professor', $roles)))
        {
            return parent::getEloquentQuery()->where('user_id', Auth()->id());
        }
        elseif ((in_array('Student', $roles)))
        {
            $teams = UserTeam::where('user_id', auth()->id())->pluck('team_id')->toArray();;
        }
        else{
            return parent::getEloquentQuery();
        }

    }
}
