<?php

namespace App\Features\Documents\Services;

use App\Features\Documents\Models\Document;

class DocumentStatusService
{
    public function markAsApproved(Document $document): void
    {
        $document->update(['status' => 'approved']);
    }

    public function markAsRejected(Document $document): void
    {
        $document->update(['status' => 'rejected']);
    }
}
