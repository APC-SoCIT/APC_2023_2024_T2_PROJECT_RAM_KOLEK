<?php

namespace App\Filament\Faculty\Pages;

use Filament\Pages\Page;

class PrivacyPolicy extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.faculty.pages.privacy-policy';
}
