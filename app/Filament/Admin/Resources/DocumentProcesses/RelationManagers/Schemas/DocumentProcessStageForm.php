<?php

namespace App\Filament\Admin\Resources\DocumentProcesses\RelationManagers\Schemas;

use App\Features\Roles\Models\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DocumentProcessStagesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('stage_name')
                    ->label('Stage Name')
                    ->placeholder('e.g. Manager Approval')
                    ->columnSpan(2)
                    ->required()
                    ->maxLength(255),

                Select::make('assigned_role_id')
                    ->label('Assigned Role')
                    ->options(Role::orderBy('name')->pluck('name', 'id'))
                    ->required()
                    ->searchable(),

                TextInput::make('action_label')
                    ->label('Action Button Label')
                    ->placeholder('e.g. Approve, Endorse')
                    ->required()
                    ->maxLength(255),

                TextInput::make('approve_status')
                    ->label('Approved Status')
                    ->placeholder('e.g. Approved by Manager')
                    ->required()
                    ->maxLength(255),

                TextInput::make('reject_status')
                    ->label('Rejected Status')
                    ->placeholder('e.g. Rejected by Manager')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}