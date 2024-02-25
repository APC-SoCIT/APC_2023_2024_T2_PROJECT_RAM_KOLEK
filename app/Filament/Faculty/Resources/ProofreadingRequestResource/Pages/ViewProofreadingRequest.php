<?php

namespace App\Filament\Faculty\Resources\ProofreadingRequestResource\Pages;

use App\Filament\Faculty\Resources\ProofreadingRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Models\ProofreadingRequest;
use App\Models\User;
use App\Models\ProofreadingRequestStatus;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Actions\Action;


class ViewProofreadingRequest extends ViewRecord
{
    protected static string $resource = ProofreadingRequestResource::class;
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if(!empty($this->record->latestStatus)){
            $data['proofreading_status'] = $this->record->latestStatus->status;
            $data['feedback'] = $this->record->latestStatus->feedback;
        }
        
        return $data;
    }
    protected function getHeaderActions(): array
    {
        $roles = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('users.id', Auth()->id())
        ->pluck('roles.name')
        ->toArray();
    
        return [
                    Action::make('endorse')
                    ->color('success')
                    ->requiresConfirmation()
                    ->form([
                        Select::make('executive_director_id')
                        ->relationship('executive_director', 'email')
                        ->required(),
                    ])
                    ->action(function (array $data) {
                        return [ProofreadingRequestStatus::create([
                            'proofreading_request_id' => $this->record->id,
                            'user_id' => auth()->user()->id,
                            'status' => 'endorsed',
                            'type' => 'professor',
                        ]),
                        ProofreadingRequest::where('id',$this->record->id)->update([
                            'status' => 'endorsed',
                            'executive_director_id' => $data['executive_director_id'],
                        ])];
                    })
                    ->visible(function (ProofreadingRequest $request): bool {
                        if((($this->record->latestStatus->status == 'pending')||($this->record->latestStatus->status == 'returned for endorsement'))&&($request->endorser_id == auth()->user()->id)){
                            $visible = true;
                        }
                        else{
                            $visible = false;
                        }
                        return $visible;
                    }),
                    Action::make('approve')
                    ->color('success')
                    ->requiresConfirmation()
                    ->form([
                        RichEditor::make('feedback')
                        ->maxLength('255')
                        ->disableAllToolbarButtons()
                    ])
                    ->action(function (array $data) {

                        return [ProofreadingRequestStatus::create([
                            'proofreading_request_id' => $this->record->id,
                            'user_id' => auth()->user()->id,
                            'status' => 'approved',
                            'type' => 'executive director',
                            'feedback' => $data['feedback'],
                        ]),
                        ProofreadingRequest::where('id',$this->record->id)->update([
                            'status' => 'approved',
                        ])];
                    })
                    ->visible(function (ProofreadingRequest $request): bool {
                        if(($this->record->latestStatus->status == 'endorsed')&&($request->executive_director_id==auth()->user()->id)){
                            $visible = true;
                        }
                        else{
                            $visible = false;
                        }
                        return $visible;
                    }),
                    Action::make('return')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->form([
                        RichEditor::make('feedback')
                        ->maxLength('255')
                        ->disableAllToolbarButtons()
                    ])
                    ->action(function (array $data) {
                        return [ProofreadingRequestStatus::create([
                            'proofreading_request_id' => $this->record->id,
                            'user_id' => auth()->user()->id,
                            'status' => 'returned for endorsement',
                            'type' => 'professor',
                            'feedback' => $data['feedback'],
                        ]),
                        ProofreadingRequest::where('id',$this->record->id)->update([
                            'status' => 'endorsed',
                        ])];
                    })
                    ->visible(function (ProofreadingRequest $request): bool {
                        if((($this->record->latestStatus->status == 'pending')||($this->record->latestStatus->status == 'returned for endorsement')||($this->record->latestStatus->status == 'endorsed'))&&($request->endorser_id == auth()->user()->id)){
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
