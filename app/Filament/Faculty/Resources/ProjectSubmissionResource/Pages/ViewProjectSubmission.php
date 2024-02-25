<?php

namespace App\Filament\Faculty\Resources\ProjectSubmissionResource\Pages;

use App\Filament\Faculty\Resources\ProjectSubmissionResource;
use Filament\Actions;
use Filament\Actions\Action;
use App\Models\ProjectSubmissionStatus;
use App\Models\ProjectSubmission;
use App\Models\ProofreadingRequest;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;

class ViewProjectSubmission extends ViewRecord
{
    protected static string $resource = ProjectSubmissionResource::class;
    protected function getFooterWidgets(): array
    {
        return [
            ProjectSubmissionResource\Widgets\ProjectSubmissionStatusHistory::class,
            ProjectSubmissionResource\Widgets\TeamMembers::class,
        ];
    }
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if(!empty($this->record->proofreadingRequestStatus)){
            $data['proofreading_status'] = $this->record->proofreadingRequestStatus->status;
        }
    
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
                    Action::make('approve')
                    ->color('success')
                    ->requiresConfirmation()
                    ->form([
                        RichEditor::make('feedback')
                        ->maxLength('255')
                        ->disableAllToolbarButtons()
                    ])
                    ->action(function (array $data) {

                        return [ProjectSubmissionStatus::create([
                            'project_submission_id' => $this->record->id,
                            'user_id' => auth()->user()->id,
                            'status' => 'approved',
                            'type' => 'professor',
                            'feedback' => $data['feedback'],
                        ]),
                        ProjectSubmission::where('id',$this->record->id)->update([
                            'status' => 'approved',
                        ])];
                    })
                    ->visible(function (ProjectSubmission $project): bool {
                        $proofread_status = ProofreadingRequest::where('project_submission_id', $project->id)->pluck('status')->toArray();
                        if((in_array('finished', $proofread_status))&&($project->status!='approved')){
                            $visible = true;
                        }
                        else{
                            $visible = false;
                        }
                        return $visible;
                    }),

                    Action::make('return')
                    ->requiresConfirmation()
                    ->form([
                        RichEditor::make('feedback')
                        ->maxLength('255')
                        ->disableAllToolbarButtons()
                    ])
                    ->action(function (array $data) {
                        return [ProjectSubmissionStatus::create([
                            'project_submission_id' => $this->record->id,
                            'user_id' => auth()->user()->id,
                            'status' => 'returned',
                            'type' => 'professor',
                            'feedback' => $data['feedback'],
                        ]),
                        ProjectSubmission::where('id',$this->record->id)->update([
                            'status' => 'returned',
                        ])];
                    })
                    ->visible(
                        function (ProjectSubmission $project): bool {
                        $proofread_status = ProofreadingRequest::where('project_submission_id', $project->id)->pluck('status')->toArray();
                        if(in_array('finished', $proofread_status)){
                            $visible = true;
                        }
                        else{
                            $visible = false;
                        }
                        return $visible;
                    }),
        ];
    }
}
