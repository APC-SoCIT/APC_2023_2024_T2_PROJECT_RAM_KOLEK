<?php

namespace App\Filament\Faculty\Resources\ProofreadingRequestResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\User;
use App\Models\UserTeam;
use Illuminate\Database\Eloquent\Model;


class TeamMembers extends BaseWidget
{
    public ?Model $record;
    public function table(Table $table): Table
    {
        $users = UserTeam::where('team_id', $this->record->projectSubmission->team_id)->pluck('user_id')->toArray();
        return $table
            ->query(
                User::whereIn('id', $users)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email'),
            ])
            ->paginated(false);
    }
}
