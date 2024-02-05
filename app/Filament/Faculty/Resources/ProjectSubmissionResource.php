<?php

namespace App\Filament\Faculty\Resources;

use App\Filament\Faculty\Resources\ProjectSubmissionResource\Pages;
use App\Filament\Faculty\Resources\ProjectSubmissionResource\RelationManagers;
use App\Models\User;
use App\Models\ProjectSubmission;
use App\Models\ProjectSubmissionStatus;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Carbon\Carbon;
use Filament\Tables\Filters\SelectFilter;


class ProjectSubmissionResource extends Resource
{
    protected static ?string $model = ProjectSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $options = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('roles.name', 'Professor')
        ->pluck('users.name', 'users.id')
        ->toArray();

        $teams = Team::pluck('name', 'id')
        ->toArray();

        $startYear = Carbon::now()->year;
        $endYear = $startYear+5;
        $academicYears = [];

        for ($year = $startYear; $year <= $endYear; $year++) {
            $academicYears["{$year}-" . ($year + 1)] = "{$year}-" . ($year + 1);
        }

        return $form
            ->schema([

                Forms\Components\TextInput::make('title'),
                Forms\Components\Select::make('team_id')
                ->label('Team')
                ->options($teams)
                ->searchable()
                ->required(),
                Forms\Components\Select::make('professor_id')
                ->label('Professor')
                ->options($options)
                ->searchable()
                ->required(),
                Forms\Components\Select::make('subject')
                ->options([
                    'csproj' => 'CSPROJ',
                    'softdev' => 'SOFTDEV',
                    'thesis' => 'THESIS',
                ]),
                Forms\Components\Select::make('academic_year')
                ->label('Academic Year:')
                ->default("{$startYear}-" . ($startYear + 1))
                ->options($academicYears)
                ->required(),
                Forms\Components\Select::make('term')
                ->label('Term')
                ->options([
                    '1' => '1st Term',
                    '2' => '2nd Term',
                    '3' => '3rd Term',
                ])
                ->required(),
                Forms\Components\MarkdownEditor::make('abstract')
                ->columnSpan(2),
                Forms\Components\Select::make('categories')
                ->options([
                    '1' => 'Artificial Intelligence and Machine Learning',
                    '2' => 'Systems and Networking',
                    '3' => 'Security and Privacy',
                ]),
                FileUpload::make('attachments')
                ->multiple()
                ->storeFileNamesIn('attachments_names')
                ->openable()
                ->downloadable()
                ->previewable(true)
                ->directory('project_files'),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('categories')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('professor.email')
                    ->label('Professor')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('proofreader_id')
                    ->label('Proofreader')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('team.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('academic_year')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('term')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListProjectSubmissions::route('/'),
            'create' => Pages\CreateProjectSubmission::route('/create'),
            'view' => Pages\ViewProjectSubmission::route('/{record}'),
            'edit' => Pages\EditProjectSubmission::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $roles = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('users.id', Auth()->id())
        ->pluck('roles.name')
        ->toArray();

        if ((in_array('PBL Coordinator', $roles))){
            return parent::getEloquentQuery();       
        }
        return parent::getEloquentQuery()->where('professor_id', auth()->id())
            ->orWhere('proofreader_id', auth()->id());
    }
}
