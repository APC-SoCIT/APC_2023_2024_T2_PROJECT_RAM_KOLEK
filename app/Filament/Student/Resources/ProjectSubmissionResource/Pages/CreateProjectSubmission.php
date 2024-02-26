<?php

namespace App\Filament\Student\Resources\ProjectSubmissionResource\Pages;

use App\Filament\Student\Resources\ProjectSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Models\User;

class CreateProjectSubmission extends CreateRecord
{
    protected static string $resource = ProjectSubmissionResource::class;

}
