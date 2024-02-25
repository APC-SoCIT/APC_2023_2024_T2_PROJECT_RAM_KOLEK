<?php

namespace App\Filament\Resources\ProjectSubmissionResource\Pages;

use App\Filament\Resources\ProjectSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class CreateProjectSubmission extends CreateRecord
{
    protected static string $resource = ProjectSubmissionResource::class;

    
    public function databaseNotif(){
            Notification::make()
            ->title('Saved successfully')
            ->success()
            ->body('Changes to the post have been saved.')
            ->actions([
                Action::make('view')
                    ->button()
                    ->markAsRead(),
            ])
            ->send();
    }

    
}
