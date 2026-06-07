@php
    $user = auth()->user();
    $initials = collect([$user->first_name, $user->last_name])
        ->map(fn ($name) => str($name)->substr(0, 1)->upper())
        ->implode('');
@endphp

@include('app.home.styles')

<div class="home-dashboard-container">

    {{-- ── Left: Profile Card ── --}}
    <div>
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

                <div class="profile-actions">
                    <a href="{{ filament()->getProfileUrl() }}" class="profile-edit-btn">
                        <x-filament::icon icon="heroicon-m-user-circle" class="btn-icon" />
                        Manage Account
                    </a>

                    <form action="{{ filament()->getLogoutUrl() }}" method="POST">
                        @csrf
                        <button type="submit" class="profile-edit-btn logout-btn">
                            <x-filament::icon icon="heroicon-m-arrow-right-on-rectangle" class="btn-icon" />
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Right: Grading Sheets List ── --}}
    <div>
        @livewire('grading-sheet-manager')
    </div>
</div>