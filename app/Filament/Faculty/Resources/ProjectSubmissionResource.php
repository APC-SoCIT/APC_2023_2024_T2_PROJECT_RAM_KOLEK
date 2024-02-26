<?php

namespace App\Filament\Faculty\Resources;

use App\Filament\Faculty\Resources\ProjectSubmissionResource\Pages;
use App\Filament\Faculty\Resources\ProjectSubmissionResource\RelationManagers;
use App\Models\User;
use App\Models\ProjectSubmission;
use App\Models\ProjectSubmissionStatus;
use App\Models\ProofreadingRequest;
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
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Tabs;

class ProjectSubmissionResource extends Resource
{
    protected static ?string $model = ProjectSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $options = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('roles.name', 'Professor')
        ->pluck('users.email', 'users.id')
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
                Tabs::make('Tabs')
                ->tabs([
                Tabs\Tab::make('Proofreading Request')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                        ->columnSpanFull(),
                        Forms\Components\Select::make('team_id')
                        ->label('Team')
                        ->options($teams)
                        ->searchable()
                        ->required(),
                        Forms\Components\Select::make('professor_id')
                        ->label('Professor')
                        ->options($options)
                        ->searchable()
                        ->default(Auth()->user()->id)
                        ->required(),
                        Forms\Components\Select::make('subject')
                        ->options([
                            'intsdev' => 'INTSDEV',
                            'syadd' => 'SYADD',
                            'csproj' => 'CSPROJ',
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
                        ->default('1')
                        ->required(),
                        Forms\Components\Select::make('categories')
                        ->relationship('categories','name')
                        ->multiple()
                        ->preload(),
                        Forms\Components\MarkdownEditor::make('abstract')
                        ->columnSpanFull(),
                        FileUpload::make('attachments')
                        ->multiple()
                        ->storeFileNamesIn('attachments_names')
                        ->openable()
                        ->downloadable()
                        ->previewable(true)
                        ->directory('project_files')
                        ->acceptedFileTypes(['application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/pdf']),
                        ])
                        ->columns(2),
                    
                Tabs\Tab::make('Status')
                    ->schema([
                        Placeholder::make('created on')
                        ->content(fn (ProjectSubmission $record): string => $record->created_at->toFormattedDateString()),
                        Placeholder::make('updated on')
                        ->content(fn (ProjectSubmission $record): string => $record->updated_at->toFormattedDateString()),
                        Forms\Components\TextInput::make('status')
                        ->label('Current Status'),
                        Forms\Components\TextInput::make('proofreading_status')
                        ->label('Proofreading Status'),
                        Forms\Components\MarkdownEditor::make('feedback')
                        ->label('Feedback')
                        ->columnSpanFull(),
                    ])
                    ->hiddenOn(['create','edit'])
                    ->columns(2),
                ])
                ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->limit(20),
                Tables\Columns\TextColumn::make('categories')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('professor.email')
                    ->label('Professor')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('team.name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('academic_year')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('term')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'returned' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
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

    protected function getProjectId(ProjectSubmission $project) 
    {
        return $project->id;
    }

    public static function getWidgets(): array
    {
        return [
            ProjectSubmissionResource\Widgets\ProjectSubmissionStatusHistory::class,
            ProjectSubmissionResource\Widgets\TeamMembers::class,
        ];
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectSubmissions::route('/'),
            'create' => Pages\CreateProjectSubmission::route('/create'),
            'view' => Pages\ViewProjectSubmission::route('/{record}'),
            'edit' => Pages\EditProjectSubmission::route('/{record}/edit'),
            'view-status' => Pages\ViewProjectSubmissionStatus::route('/{record}/view-status'),
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
            return parent::getEloquentQuery()->where('professor_id', Auth()->id());
        }
        elseif ((in_array('Librarian', $roles)))
        {
            return parent::getEloquentQuery()->where('status', 'approved');
        }
        else{
            return parent::getEloquentQuery(); 
        }
    }

}
