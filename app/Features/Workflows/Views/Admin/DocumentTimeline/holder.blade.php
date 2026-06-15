<div class="bg-white dark:bg-gray-950 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm space-y-6">
    <div>
        <h3 class="text-base font-bold text-gray-900 dark:text-white">Approval Timeline</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400">Track the real-time routing and decision history of this document.</p>
    </div>

    <div class="relative pl-6 border-l border-gray-200 dark:border-gray-800 space-y-8">
        <!-- 1. Submission Step -->
        <div class="relative">
            <span class="absolute -left-10 top-0 flex items-center justify-center w-8 h-8 rounded-full bg-success-50 dark:bg-success-950/30 text-success-600 dark:text-success-400 border-4 border-white dark:border-gray-950 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </span>
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold text-gray-900 dark:text-white">Document Submitted</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-success-100 dark:bg-success-900/40 text-success-800 dark:text-success-300 font-medium">Initiated</span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Submitted by <strong class="text-gray-700 dark:text-gray-300">{{ $record->submittedBy->full_name }}</strong>
                </p>
                <div class="text-[10px] text-gray-400 dark:text-gray-500">
                    {{ $record->created_at->format('M d, Y h:i A') }}
                </div>
            </div>
        </div>

        <!-- 2. Workflow Steps -->
        @if ($record->workflow && $record->workflow->steps)
            @foreach ($record->workflow->steps->sortBy('step_order') as $step)
                @php
                    $approval = $record->approvals->where('workflow_step_id', $step->id)->first();
                    $isCurrent = $record->current_step_id === $step->id && $record->status === 'pending';
                    $isUpcoming = !$approval && !$isCurrent;
                @endphp

                <div class="relative">
                    @if ($approval)
                        @if ($approval->status === $step->approve_status)
                            <!-- Approved Step -->
                            <span class="absolute -left-10 top-0 flex items-center justify-center w-8 h-8 rounded-full bg-success-50 dark:bg-success-950/30 text-success-600 dark:text-success-400 border-4 border-white dark:border-gray-950 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </span>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $step->step_name }}</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-success-100 dark:bg-success-900/40 text-success-800 dark:text-success-300 font-medium">Approved</span>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Acted by <strong class="text-gray-700 dark:text-gray-300">{{ $approval->user?->full_name ?? 'System' }}</strong> ({{ $step->role?->name }})
                                </p>
                                @if ($approval->remarks)
                                    <div class="p-2.5 rounded-lg bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 text-xs text-gray-600 dark:text-gray-400 italic">
                                        "{{ $approval->remarks }}"
                                    </div>
                                @endif
                                <div class="text-[10px] text-gray-400 dark:text-gray-500">
                                    {{ $approval->acted_at->format('M d, Y h:i A') }}
                                </div>
                            </div>
                        @else
                            <!-- Rejected/Returned Step -->
                            <span class="absolute -left-10 top-0 flex items-center justify-center w-8 h-8 rounded-full bg-danger-50 dark:bg-danger-950/30 text-danger-600 dark:text-danger-400 border-4 border-white dark:border-gray-950 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </span>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $step->step_name }}</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-danger-100 dark:bg-danger-900/40 text-danger-800 dark:text-danger-300 font-medium">Returned / Rejected</span>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Acted by <strong class="text-gray-700 dark:text-gray-300">{{ $approval->user?->full_name ?? 'System' }}</strong> ({{ $step->role?->name }})
                                </p>
                                @if ($approval->remarks)
                                    <div class="p-2.5 rounded-lg bg-danger-50/50 dark:bg-danger-950/10 border border-danger-100 dark:border-danger-900/30 text-xs text-danger-600 dark:text-danger-400 italic">
                                        "{{ $approval->remarks }}"
                                    </div>
                                @endif
                                <div class="text-[10px] text-gray-400 dark:text-gray-500">
                                    {{ $approval->acted_at->format('M d, Y h:i A') }}
                                </div>
                            </div>
                        @endif
                    @elseif ($isCurrent)
                        <!-- Current Active Step -->
                        <span class="absolute -left-10 top-0 flex items-center justify-center w-8 h-8 rounded-full bg-primary-50 dark:bg-primary-950/30 text-primary-600 dark:text-primary-400 border-4 border-white dark:border-gray-950 shadow-sm">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-primary-500"></span>
                            </span>
                        </span>
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $step->step_name }}</span>
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-800 dark:text-primary-300 font-medium animate-pulse">Pending</span>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                Waiting for review from: <strong class="text-primary-600 dark:text-primary-400">{{ $step->role?->name }}</strong>
                            </p>
                        </div>
                    @else
                        <!-- Upcoming Step -->
                        <span class="absolute -left-10 top-0 flex items-center justify-center w-8 h-8 rounded-full bg-gray-50 dark:bg-gray-900 text-gray-400 dark:text-gray-600 border-4 border-white dark:border-gray-950 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </span>
                        <div class="space-y-1 opacity-60">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-500 dark:text-gray-400">{{ $step->step_name }}</span>
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 font-medium">Scheduled</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Assigned to: {{ $step->role?->name }}
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif

        <!-- 3. Final Step (Document Approved / Rejected) -->
        @if ($record->status === 'approved')
            <div class="relative">
                <span class="absolute -left-10 top-0 flex items-center justify-center w-8 h-8 rounded-full bg-success-600 text-white border-4 border-white dark:border-gray-950 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">Workflow Completed</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-success-600 text-white font-medium">Approved</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        The document has been fully signed and approved by all stages.
                    </p>
                </div>
            </div>
        @elseif ($record->status === 'rejected')
            <div class="relative">
                <span class="absolute -left-10 top-0 flex items-center justify-center w-8 h-8 rounded-full bg-danger-600 text-white border-4 border-white dark:border-gray-950 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </span>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">Workflow Terminated</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-danger-600 text-white font-medium">Rejected</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        The document has been rejected and returned.
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
