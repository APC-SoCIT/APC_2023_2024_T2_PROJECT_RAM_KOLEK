<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\ProofreadingRequestResource\Pages;
use App\Filament\Student\Resources\ProofreadingRequestResource\RelationManagers;
use App\Models\ProofreadingRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Fieldset;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth;

class ProofreadingRequestResource extends Resource
{
    protected static ?string $model = ProofreadingRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('project_submission_id')
                    ->relationship('projectSubmission', 'title')
                    ->required()
                    ->live(),
                Forms\Components\TextInput::make('client_name')
                    ->required()
                    ->maxLength(255)
                    ,
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('endorser')
                    ->relationship('endorser','email'),
                Forms\Components\Select::make('executive_director')
                    ->relationship('executiveDirector','email'),
                Forms\Components\Select::make('proofreader')
                    ->relationship('proofreader','email')
                    ->disabled(),
                Forms\Components\TextInput::make('number_pages')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('number_words')
                    ->required()
                    ->maxLength(255),

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
                Tables\Columns\TextColumn::make('projectSubmission.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('endorser.email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('executive_director.email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('recieved_date')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('number_pages')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('number_words')
                    ->searchable(),
                Tables\Columns\TextColumn::make('proofreader')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('released_date')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'returned' => 'warning',
                        'finished' => 'success',
                        'rejected' => 'danger',
                    }),
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
            'index' => Pages\ListProofreadingRequests::route('/'),
            'create' => Pages\CreateProofreadingRequest::route('/create'),
            'edit' => Pages\EditProofreadingRequest::route('/{record}/edit'),
        ];
    }
}
