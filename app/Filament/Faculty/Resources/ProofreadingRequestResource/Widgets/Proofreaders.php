<?php

namespace App\Filament\Faculty\Resources\ProofreadingRequestResource\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\Summarizers\Count;
use Illuminate\Database\Query\Builder;

class Proofreaders extends BaseWidget
{
    public function table(Table $table): Table
    {
        $proofreaders = DB::table('model_has_roles')->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('roles.name', 'Proofreader')
        ->pluck('model_has_roles.model_id')
        ->toArray();

        return $table
            ->query(
                User::query()->whereIn('id', $proofreaders)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
    public static function canView(): bool 
    {
        $roles = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('users.id', Auth()->id())
        ->pluck('roles.name')
        ->toArray();
        
        return in_array('English Cluster Head', $roles);
    } 
}
