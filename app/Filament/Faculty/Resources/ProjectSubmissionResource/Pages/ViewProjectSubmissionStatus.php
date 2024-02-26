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
use Illuminate\Database\Eloquent\Builder;


class ViewProjectSubmissionStatus extends ViewRecord
{
    protected static string $resource = ProjectSubmissionResource::class;


    protected function getHeaderActions(): array
    {

        return [
            //Action::make('login faculty')
            //->url('../../faculty/login'),
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
                    Fieldset::make('Project Submission Details')
                        ->schema([   
                            TextEntry::make('title'),
                            TextEntry::make('team.name'),
                            TextEntry::make('team.members')
                            ->label('Members')
                            ->listWithLineBreaks(),
                            TextEntry::make('attachments')
                            ->url(fn (ProjectSubmission $project): string => url('/storage/'.$project->attatchment))
                            ->listWithLineBreaks(),
                            
                        ]),
            ]);
    }
    
/*
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('statuses.project_submission_id', 'id' );
    }
*/
}
