<?php

namespace App\Filament\Student\Resources\ProjectSubmissionResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\User;
use App\Models\ProjectSubmission;
use App\Models\ProjectSubmissionStatus;
use Illuminate\Database\Eloquent\Model;
 


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
               Tables\Columns\TextColumn::make('type'),
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
            ->defaultPaginationPageOption(5);
    }
}
