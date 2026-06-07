<?php

namespace App\Filament\Admin\Resources\Loads\Pages;

use App\Filament\Admin\Resources\Loads\LoadsResource;
use App\Models\Load;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewLoads extends ViewRecord
{
    protected static string $resource = LoadsResource::class;

    protected ?string $subheading = 'View the details of the faculty load.';

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->visible(fn (): bool => static::getResource()::canEdit($this->record)),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Load Details')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('faculty')
                            ->label('Faculty')
                            ->content(fn (Load $record): string => $record->user?->full_name ?? 'Unassigned'),
                        Placeholder::make('program')
                            ->label('Program')
                            ->content(fn (Load $record): string => $record->program?->name ?? 'N/A'),
                        Placeholder::make('subject')
                            ->label('Subject')
                            ->content(fn (Load $record): string => $record->subject?->name ?? 'N/A'),
                        Placeholder::make('semester')
                            ->label('Semester')
                            ->content(fn (Load $record): string => (string) $record->term),
                        Placeholder::make('academic_year')
                            ->label('Academic Year')
                            ->content(fn (Load $record): string => $record->academicYear?->year ?? 'N/A'),
                    ]),
            ]);
    }
}
