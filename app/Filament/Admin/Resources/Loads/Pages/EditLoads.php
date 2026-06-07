<?php

namespace App\Filament\Admin\Resources\Loads\Pages;

use App\Filament\Admin\Resources\Loads\LoadsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLoads extends EditRecord
{
    protected static string $resource = LoadsResource::class;

    protected ?string $subheading = 'Edit faculty load details.';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn (): bool => static::getResource()::canDelete($this->getRecord())),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
