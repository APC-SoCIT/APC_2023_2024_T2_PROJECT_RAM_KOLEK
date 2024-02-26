<?php

namespace App\Filament\Faculty\Resources\ProofreadingRequestResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\User;
use App\Models\ProofreadingRequest;
use App\Models\ProofreadingRequestStatus;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms;
use Filament\Forms\Form;
 


class ProofreadingRequestStatusHistory extends BaseWidget
{
    public ?Model $record;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ProofreadingRequestStatus::query()->where('proofreading_request_id', $this->record->id)
            )
            ->columns([
               Tables\Columns\TextColumn::make('user.email'),
               Tables\Columns\TextColumn::make('type'),
               Tables\Columns\TextColumn::make('status')
               ->badge()
               ->color(fn (string $state): string => match ($state) {
                    'pending' => 'gray',
                    'endorsed' => 'info',
                    'approved' => 'info',
                    'assigned' => 'info',
                    'returned for endorsement' => 'warning',
                    'returned for approval' => 'warning',
                    'returned for assignment' => 'warning',
                    'completed' => 'success',
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
                        Forms\Components\DateTimePicker::make('created_at')
                        ,
                        Forms\Components\MarkdownEditor::make('feedback')
                    ]),
            ])
            ->defaultPaginationPageOption(5);
    }
}
