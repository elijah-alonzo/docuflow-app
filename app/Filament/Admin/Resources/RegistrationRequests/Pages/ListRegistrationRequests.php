<?php

namespace App\Filament\Admin\Resources\RegistrationRequests\Pages;

use App\Filament\Admin\Resources\RegistrationRequests\RegistrationRequestsResource;
use Filament\Resources\Pages\ListRecords;

class ListRegistrationRequests extends ListRecords
{
    protected static string $resource = RegistrationRequestsResource::class;

    protected ?string $subheading = 'Approve or reject new account registrations.';
}
