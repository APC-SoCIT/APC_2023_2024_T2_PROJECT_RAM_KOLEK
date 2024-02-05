<?php

namespace App\Filament\Faculty\Resources\ProjectSubmissionResource\Pages;

use App\Filament\Faculty\Resources\ProjectSubmissionResource;
use Filament\Actions;
use Filament\Actions\Action;
use App\Models\ProjectSubmissionStatus;
use App\Models\ProjectSubmission;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\RepeatableEntry;

class ViewProjectSubmission extends ViewRecord
{
    protected static string $resource = ProjectSubmissionResource::class;
    protected static ?string $model = ProjectSubmission::class;

    protected function getHeaderActions(): array
    {

        return [
            Action::make('approve')
            ->color('success')
            ->requiresConfirmation()
            ->action(function (ProjectSubmission $project) {
                $user = auth()->user();
                return ProjectSubmissionStatus::create([
                    'project_submission_id' => $project->id,
                    'user_id' => $user->id,
                    'status' => 'approved',
                    'type' => 'professor',
                ]);
            }),
            Action::make('return')
            ->requiresConfirmation()
            ->action(function (ProjectSubmission $project) {
                $user = auth()->user();
                return ProjectSubmissionStatus::create([
                    'project_submission_id' => $project->id,
                    'user_id' => $user->id,
                    'status' => 'returned',
                    'type' => 'professor',
                ]);
            }),
            ];
    }
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Fieldset::make('Project Submission Status')
                    ->schema([   
                        TextEntry::make('status')
                        ->label('Completion')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'pending' => 'gray',
                            'returned' => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                        }), 
                            RepeatableEntry::make('statuses')
                            ->label('Approvals')
                            ->schema([
                                TextEntry::make('user.email')
                                ->label('Approver'),
                                TextEntry::make('type'),
                                TextEntry::make('status')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'pending' => 'gray',
                                    'returned' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                }),
                                TextEntry::make('updated_at')
                                ->dateTime()
                            ])
                            ->columns(4),

                        ])
                    ->columns(1),

            ]);
    }
    
}
