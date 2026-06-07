<?php

namespace App\Filament\Admin\Resources\Programs;

use App\Filament\Admin\Resources\Programs\Pages\CreatePrograms;
use App\Filament\Admin\Resources\Programs\Pages\EditPrograms;
use App\Filament\Admin\Resources\Programs\Pages\ListPrograms;
use App\Filament\Admin\Resources\Programs\Pages\ViewPrograms;
use App\Filament\Admin\Resources\Programs\RelationManagers\SubjectsRelationManager;
use App\Filament\Admin\Resources\Programs\Schemas\ProgramsForm;
use App\Filament\Admin\Resources\Programs\Tables\ProgramsTable;
use App\Models\Program;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ProgramsResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static UnitEnum|string|null $navigationGroup = 'Academic Management';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return ProgramsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProgramsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user?->hasRole('Registrar') && $user->program_id) {
            return $query->whereKey($user->program_id);
        }

        return $query;
    }

    public static function getRelations(): array
    {
        return [
            SubjectsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPrograms::route('/'),
            'create' => CreatePrograms::route('/create'),
            'edit' => EditPrograms::route('/{record}/edit'),
            'view' => ViewPrograms::route('/{record}'),
        ];
    }
}
