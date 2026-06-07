<?php

namespace App\Filament\Admin\Pages;

use App\Models\AcademicYear;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function getColumns(): int|array
    {
        return [
            'md' => 2,
            'xl' => 2,
        ];
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Academic Filters')
                    ->description('Filter the dashboard data by academic year and semester.')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('academic_year_id')
                            ->label('Academic Year')
                            ->options(fn (): array => AcademicYear::query()
                                ->orderByDesc('year')
                                ->pluck('year', 'id')
                                ->all())
                            ->searchable()
                            ->placeholder('All')
                            ->nullable()
                            ->preload()
                            ->native(false),
                        Select::make('term')
                            ->label('Semester')
                            ->options([
                                'First Semester' => 'First Semester',
                                'Second Semester' => 'Second Semester',
                                'Third Semester' => 'Third Semester',
                                'Summer Semester' => 'Summer Semester',
                            ])
                            ->placeholder('All')
                            ->nullable()
                            ->native(false),
                    ])
                    ->columns(2),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        $widgets = $this->getWidgets();

        return $schema
            ->components([
                Grid::make($this->getColumns())
                    ->schema(fn (): array => $this->getWidgetsSchemaComponents(array_slice($widgets, 0, 2))),
                $this->getFiltersFormContentComponent()
                    ->columnSpanFull(),
                Grid::make($this->getColumns())
                    ->schema(fn (): array => $this->getWidgetsSchemaComponents(array_slice($widgets, 2))),
            ]);
    }
}
