@php
    $load = null;
    if (isset($record) && $record instanceof \App\Models\Load) {
        $load = $record;
        $user = $record->user;
    } else {
        $user = $record ?? auth()->user();
    }

    $initials = $user
        ? collect([$user->first_name, $user->last_name])
            ->map(fn ($name) => str($name)->substr(0, 1)->upper())
            ->implode('')
        : '';
@endphp

@include('admin.view.styles')

@if ($user)
    <div class="profile-card">
        <div class="profile-banner"></div>
        <div class="profile-body">
            <div class="profile-avatar-wrapper">
                @if ($user->avatar)
                    <img src="{{ Storage::disk('public')->url($user->avatar) }}" alt="{{ $user->full_name }}" class="profile-avatar-img" />
                @else
                    <div class="profile-avatar-gradient">{{ $initials }}</div>
                @endif
            </div>

            <h2 class="profile-name">{{ $user->full_name }}</h2>
            <span class="profile-role-badge">{{ $user->roles->first()?->name ?? 'Faculty Member' }}</span>

            <div class="profile-details-list">
                <div class="profile-detail-item" title="{{ $user->email }}">
                    <x-filament::icon icon="heroicon-o-envelope" class="profile-detail-icon" />
                    <span class="profile-detail-text">{{ $user->email }}</span>
                </div>

                <div class="profile-detail-item">
                    <x-filament::icon icon="heroicon-o-phone" class="profile-detail-icon" />
                    <span class="profile-detail-text">{{ $user->contact_number ?? 'No contact info' }}</span>
                </div>

                <div class="profile-detail-item" title="{{ $user->program?->name ?? 'No assigned program' }}">
                    <x-filament::icon icon="heroicon-o-academic-cap" class="profile-detail-icon" />
                    <span class="profile-detail-text">{{ $user->program?->name ?? 'Unassigned Program' }}</span>
                </div>
            </div>

            @if ($load)
                <div class="card-divider"></div>
                <h3 class="merged-details-title">Grading Sheet</h3>
                
                <div class="merged-details-list">
                    <div class="merged-detail-row">
                        <span class="merged-detail-label">Program: </span>
                        <span class="merged-detail-val">{{ $load->program?->name ?? 'N/A' }}</span>
                    </div>
                    <div class="merged-detail-row">
                        <span class="merged-detail-label">Subject: </span>
                        <span class="merged-detail-val">{{ $load->subject?->name ?? 'N/A' }}</span>
                    </div>
                    <div class="merged-detail-row">
                        <span class="merged-detail-label">Semester: </span>
                        <span class="merged-detail-val">{{ $load->term }}</span>
                    </div>
                    <div class="merged-detail-row">
                        <span class="merged-detail-label">Academic Year: </span>
                        <span class="merged-detail-val">{{ $load->academicYear?->year ?? 'N/A' }}</span>
                    </div>
                    <div class="merged-detail-row">
                        <span class="merged-detail-label">Status: </span>
                        <span class="merged-detail-val">
                            <span class="status-badge status-{{ $load->grading_sheet_status }}">
                                {{ str($load->submission_status)->title() }}
                            </span>
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
