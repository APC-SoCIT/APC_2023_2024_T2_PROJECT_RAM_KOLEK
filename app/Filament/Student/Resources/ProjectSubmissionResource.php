<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\ProjectSubmissionResource\Pages;
use App\Filament\Student\Resources\ProjectSubmissionResource\RelationManagers;
use App\Models\User;
use App\Models\UserTeam;
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
use Filament\Forms\Components\Tabs;


class ProjectSubmissionResource extends Resource
{
    protected static ?string $model = ProjectSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

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
                Tabs\Tab::make('Project Submission')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull()
                        ->disabledOn(['create', 'edit', 'view']),
                        Forms\Components\Select::make('team_id')
                        ->label('Team')
                        ->options($teams)
                        ->searchable()
                        ->required()
                        ->disabledOn(['create', 'edit', 'view']),
                        Forms\Components\Select::make('professor_id')
                        ->label('Professor')
                        ->options($options)
                        ->searchable()
                        ->default(Auth()->user()->id)
                        ->required()
                        ->disabledOn(['create', 'edit', 'view']),
                        Forms\Components\TextInput::make('school')
                        ->label('School')
                        ->hiddenOn(['create'])
                        ->disabledOn(['create', 'edit', 'view']),
  
                        Forms\Components\TextInput::make('program')
                        ->label('Program')
                        ->hiddenOn(['create'])
                        ->disabledOn(['create', 'edit', 'view']),

                        Forms\Components\TextInput::make('section')
                        ->label('Section')
                        ->hiddenOn(['create'])
                        ->disabledOn(['create', 'edit', 'view']),
                        Forms\Components\TextInput::make('academic_year')
                        ->label('Academic Year')
                        ->hiddenOn(['create'])
                        ->disabledOn(['create', 'edit', 'view']),

                        Forms\Components\Select::make('subject')
                        ->options([
                            'INTSDEV' => 'INTSDEV',
                            'SYADD' => 'SYADD',
                            'CSPROJ' => 'CSPROJ',
                        ])
                        ->required()
                        ->disabledOn(['create', 'edit', 'view']),
                        Forms\Components\Select::make('term')
                        ->label('Term')
                        ->options([
                            '1' => '1st Term',
                            '2' => '2nd Term',
                            '3' => '3rd Term',
                        ])
                        ->default('1')
                        ->required()
                        ->disabledOn(['create', 'edit', 'view']),
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
                        ->maxFiles(5)

                        ->acceptedFileTypes(['application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/pdf'])
                        ->validationMessages([
                            'acceptedFileTypes' => 'The :attribute .doc, .docx, and .pdf files are accepted.',
                            'maxFiles' => 'You cannot upload more than 5 files.',
                            'maxSize' => 'You cannot upload files more than 50mb in size.',
                        ])
                        ->live()
                        ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, Forms\Components\FileUpload $component) { 
                            $livewire->validateOnly($component->getStatePath());
                        }), 
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
                Tables\Columns\TextColumn::make('school')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('program')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('section')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('academic_year')
                    ->searchable()
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
                Tables\Columns\TextColumn::make('proofreadingRequestStatus.status')
                    ->label('Proofreading Status')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'endorsed' => 'info',
                        'approved' => 'info',
                        'assigned' => 'info',
                        'returned for endorsement' => 'warning',
                        'returned for approval' => 'warning',
                        'returned for assignment' => 'warning',
                        'completed' => 'success',
                    }),
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
                Tables\Actions\ViewAction::make()
                ->hidden(fn ($record) => $record->trashed()),
                Tables\Actions\EditAction::make()
                ->hidden(fn ($record) => $record->trashed()),
                Tables\Actions\RestoreAction::make()

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'view-status' => Pages\ViewProjectSubmissionStatus::route('/{record}/view-status'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $teams = UserTeam::where('user_id', auth()->id())->pluck('team_id')->toArray();
        return parent::getEloquentQuery()->whereIn('team_id', $teams)
; 
    }

}
