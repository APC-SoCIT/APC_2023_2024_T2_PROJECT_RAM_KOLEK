<?php

namespace App\Filament\Student\Resources\ProjectSubmissionResource\Pages;

use App\Filament\Student\Resources\ProjectSubmissionResource;
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

class ViewProjectSubmission extends ViewRecord
{
    protected static string $resource = ProjectSubmissionResource::class;
    protected static ?string $model = ProjectSubmission::class;

    protected function getFooterWidgets(): array
    {
        return [
            ProjectSubmissionResource\Widgets\ProjectSubmissionStatusHistory::class,
            ProjectSubmissionResource\Widgets\TeamMembers::class,
        ];
    }
}
