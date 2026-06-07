<div>
    {{-- Action modals --}}
    @if ($this->uploadAction)
        <x-filament-actions::modals />
    @endif

    <div class="list-section-col">
        <div class="section-header">
            <div>
                <span class="section-title">My Grading Sheets</span>
                <p class="section-subtitle">Track and manage your grading sheet submissions.</p>
            </div>
        </div>

        <div class="grading-sheets-grid">
            @forelse ($loads->filter(fn ($l) => $l->grading_sheet_status !== 'submitted') as $load)
                <div class="grading-sheet-card">
                    <div class="grading-sheet-card-header">
                        <div style="min-width:0; flex:1">
                            <p class="text-sm font-semibold text-gray-950 dark:text-white truncate">
                                {{ $load->subject->name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                {{ $load->program->name }} • {{ $load->term }}
                            </p>
                        </div>
                        <div class="grading-sheet-card-header-actions">
                            @if ($load->grading_sheet_status === 'pending')
                                <button
                                    wire:click="openUpload({{ $load->id }})"
                                    class="cta-button upload-btn"
                                >
                                    <x-filament::icon icon="heroicon-m-arrow-up-tray" class="btn-icon" />
                                    Upload
                                </button>
                            @else
                                <button
                                    wire:click="openReupload({{ $load->id }})"
                                    class="cta-button view-btn"
                                >
                                    <x-filament::icon icon="heroicon-m-arrow-path" class="btn-icon" />
                                    Re-upload
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="grading-sheet-card-body">
                        @include('public.progress', [
                            'current' => $load->grading_sheet_status
                        ])
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-state-content">
                        <div class="empty-state-icon-wrapper">
                            <x-filament::icon
                                icon="heroicon-o-clipboard-document-list"
                                class="empty-state-icon"
                            />
                        </div>
                        <h3 class="empty-state-title">You're all caught up</h3>
                        <p class="empty-state-description">
                            You have no grading sheets to upload. Once teaching
                            loads are assigned and grading sheets are generated, they
                            will automatically appear here.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>