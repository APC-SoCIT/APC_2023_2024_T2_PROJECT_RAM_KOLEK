<?php

namespace App\Filament\Student\Resources\ProjectSubmissionResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\User;
use App\Models\ProjectSubmission;
use App\Models\ProjectSubmissionStatus;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms;
use Filament\Forms\Form;
 


class ProjectSubmissionStatusHistory extends BaseWidget
{
    public ?Model $record;
    public function table(Table $table): Table
    {
        return $table
            ->query(
                ProjectSubmissionStatus::query()->where('project_submission_id', $this->record->id)
            )
            ->columns([
               Tables\Columns\TextColumn::make('user.email'),
               Tables\Columns\TextColumn::make('status')
               ->badge()
               ->color(fn (string $state): string => match ($state) {
                    'pending' => 'gray',
                    'returned' => 'warning',
                    'approved' => 'success',
               }),
               Tables\Columns\TextColumn::make('created_at')
               ->dateTime()
               ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->record($this->record)
                    ->form([
                        Forms\Components\TextInput::make('status'),
                        Forms\Components\Select::make('user')
                        ->relationship('user','name')
                        ->label('Name'),
                        Forms\Components\Select::make('user')
                        ->relationship('user','email')
                        ->label('Email'),
                        Forms\Components\TextInput::make('created_at'),
                        Forms\Components\MarkdownEditor::make('feedback')
                    ]),
            ])
            ->defaultPaginationPageOption(5);
    }
}
