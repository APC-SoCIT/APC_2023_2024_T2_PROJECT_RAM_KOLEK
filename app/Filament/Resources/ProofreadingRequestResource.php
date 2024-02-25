<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProofreadingRequestResource\Pages;
use App\Filament\Resources\ProofreadingRequestResource\RelationManagers;
use App\Models\ProofreadingRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;

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
                    ->required(),
                Forms\Components\TextInput::make('client_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('endorser')
                    ->relationship('endorser','email')
                    ->required(),
                Forms\Components\Select::make('executive_director')
                    ->relationship('executiveDirector','email')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('recieved_date')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('number_pages')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('number_words')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('proofreader')
                    ->relationship('proofreader','email'),
                Forms\Components\TextInput::make('released_date')
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('client_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('endorser')
                    ->searchable(),
                Tables\Columns\TextColumn::make('executive_director')
                    ->searchable(),
                Tables\Columns\TextColumn::make('doc_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('recieved_date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('copy_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('number_pages')
                    ->searchable(),
                Tables\Columns\TextColumn::make('number_words')
                    ->searchable(),
                Tables\Columns\TextColumn::make('proofreader')
                    ->searchable(),
                Tables\Columns\TextColumn::make('released_date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('attachments')
                    ->searchable(),
                Tables\Columns\TextColumn::make('attachments_names')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
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
            'index' => Pages\ListProofreadingRequests::route('/'),
            'create' => Pages\CreateProofreadingRequest::route('/create'),
            'edit' => Pages\EditProofreadingRequest::route('/{record}/edit'),
        ];
    }
}
