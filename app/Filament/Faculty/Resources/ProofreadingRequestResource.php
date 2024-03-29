<?php

namespace App\Filament\Faculty\Resources;

use App\Filament\Faculty\Resources\ProofreadingRequestResource\Pages;
use App\Filament\Faculty\Resources\ProofreadingRequestResource\RelationManagers;
use App\Models\ProofreadingRequest;
use App\Models\Team;
use App\Models\UserTeam;
use App\Models\User;
use App\Models\ProjectSubmission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth;

class ProofreadingRequestResource extends Resource
{
    protected static ?string $model = ProofreadingRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    public static function form(Form $form): Form
    {
        $roles = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('users.id', Auth()->id())
        ->pluck('roles.name')
        ->toArray();
        
        return $form
            ->schema([
                Tabs::make('Tabs')
                ->tabs([
                    Tabs\Tab::make('Proofreading Request')
                        ->schema([
                            Forms\Components\Select::make('team_id')
                            ->label('Team')
                            ->relationship('team','name')
                            ->hiddenOn(['create'])
                            ->disabledOn(['edit']),
                        Forms\Components\TextInput::make('phone_number')
                            ->tel()
                            ->required()
                            ->maxLength(15),
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
                        Forms\Components\Select::make('endorser_id')
                            ->label('Professor')
                            ->relationship('user','email')
                            ->hiddenOn(['create'])
                            ->disabledOn(['edit']),
                        Forms\Components\Select::make('executive_director_id')
                            ->label('Executive Director')
                            ->relationship('user','email')
                            ->hiddenOn(['create'])
                            ->disabledOn(['edit']),
                        Forms\Components\Select::make('proofreader_id')
                            ->label('Proofreader')
                            ->relationship('user','email')
                            ->hiddenOn(['create'])
                            ->disabledOn(['edit']),
                        Forms\Components\TextInput::make('number_pages')
                            ->label('Number of Pages')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(999999999),
                        Forms\Components\TextInput::make('number_words')
                            ->label('Number of Words')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(999999999),
    
                        FileUpload::make('attachments')
                            ->multiple()
                            ->storeFileNamesIn('attachments_names')
                            ->openable()
                            ->downloadable()
                            ->previewable(true)
                            ->directory('proofreading_files')
                            ->minFiles(1)
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
                            ->content(fn (ProofreadingRequest $record): string => $record->created_at->toFormattedDateString()),
                            Placeholder::make('updated on')
                            ->content(fn (ProofreadingRequest $record): string => $record->updated_at->toFormattedDateString()),

                            Forms\Components\TextInput::make('proofreading_status')
                            ->label('Current Status'),
                            Forms\Components\MarkdownEditor::make('feedback')
                            ->label('Feedback')
                            ->columnSpanFull(),
                            Forms\Components\FileUpload::make('attachments')
                            ->multiple()
                            ->storeFileNamesIn('attachments_names')
                            ->openable()
                            ->downloadable()
                            ->previewable(true)
                            ->directory('proofreading_files')
                            ->columnSpanFull(),
                        ])
                        ->hiddenOn(['create','edit'])
                        ->columns(2),
                    ]),
                ])
                ->columns(1);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('projectSubmission.title')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('team.name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('endorser.email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('executiveDirector.email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('proofreader.email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('school')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('program')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('academic_year')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('received_date')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('released_date')
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
                Tables\Columns\TextColumn::make('latestStatus.status')
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
            ])
            ->filters([
                //
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
            ProofreadingRequestResource\Widgets\ProofreadingRequestStatusHistory::class,
            ProofreadingRequestResource\Widgets\TeamMembers::class,
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
            'index' => Pages\ListProofreadingRequests::route('/'),
            'create' => Pages\CreateProofreadingRequest::route('/create'),
            'edit' => Pages\EditProofreadingRequest::route('/{record}/edit'),
            'view' => Pages\ViewProofreadingRequest::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {


        $roles = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('users.id', Auth()->id())
        ->pluck('roles.name')
        ->toArray();

        if ((in_array('Proofreader', $roles)))
        {
            return parent::getEloquentQuery()->where('proofreader_id', Auth()->id());     
        }
        elseif((in_array('Student', $roles)))
        {
            $teams = UserTeam::where('user_id', auth()->id())->pluck('team_id')->toArray();
            $projects = ProjectSubmission::whereIn('team_id', $teams)->pluck('id');
            return parent::getEloquentQuery()->whereIn('project_submission_id', $projects);    
        }
        elseif ((in_array('Professor', $roles)))
        {
            return parent::getEloquentQuery()->where('endorser_id', Auth()->id()); 
        }
        elseif ((in_array('Executive Director', $roles)))
        {
            return parent::getEloquentQuery()->where('executive_director_id', Auth()->id());
        }
        elseif ((in_array('English Cluster Head', $roles)))
        {
            return parent::getEloquentQuery()->whereIn('status', ['approved','returned for assignment','assigned','returned for assignment','completed']);
        }
        else{
            return parent::getEloquentQuery(); 
        }
    }
}
