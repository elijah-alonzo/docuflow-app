<?php

namespace App\Features\DocumentSubmissions\Services;

use App\Features\DocumentSubmissions\Models\DocumentSubmission;

class DocumentSubmissionStatusService
{
    public function markAsApproved(DocumentSubmission $document): void
    {
        $document->update(['status' => 'approved']);
    }

    public function markAsRejected(DocumentSubmission $document): void
    {
        $document->update(['status' => 'rejected']);
    }
}
