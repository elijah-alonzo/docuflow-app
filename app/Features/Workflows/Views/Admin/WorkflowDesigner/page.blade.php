<div class="space-y-6">
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800 transition-all duration-300" role="alert">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-4">
        <div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Workflow Stages Configuration</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Define the approval steps, order, roles, and status transitions for this workflow.</p>
        </div>
        <button type="button" wire:click="addStep" class="fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-primary fi-btn-color-primary bg-primary-600 hover:bg-primary-500 text-white shadow-sm gap-1.5 px-3 py-2 text-sm inline-flex">
            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Stage
        </button>
    </div>

    <div class="space-y-4">
        @foreach ($steps as $index => $step)
            <div class="bg-white dark:bg-gray-950 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden transition-all duration-200 hover:border-primary-500 dark:hover:border-primary-500">
                <div class="p-5 flex flex-col md:flex-row gap-5 items-start">
                    <!-- Step Order / Reordering Controls -->
                    <div class="flex flex-row md:flex-col items-center justify-between md:justify-center gap-2 w-full md:w-auto">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-50 dark:bg-primary-950/40 text-primary-600 dark:text-primary-400 font-bold text-lg">
                            {{ $step['step_order'] }}
                        </div>
                        <div class="flex md:flex-col gap-1">
                            <button type="button" wire:click="moveStepUp({{ $index }})" class="p-1.5 rounded hover:bg-gray-100 dark:hover:bg-gray-900 text-gray-500 dark:text-gray-400 disabled:opacity-30" @disabled($index === 0) title="Move Up">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                            </button>
                            <button type="button" wire:click="moveStepDown({{ $index }})" class="p-1.5 rounded hover:bg-gray-100 dark:hover:bg-gray-900 text-gray-500 dark:text-gray-400 disabled:opacity-30" @disabled($index === count($steps) - 1) title="Move Down">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Fields Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 flex-1 w-full">
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">Stage Name</label>
                            <input type="text" wire:model.defer="steps.{{ $index }}.step_name" class="w-full bg-transparent border border-gray-300 dark:border-gray-800 rounded-lg px-3 py-1.5 text-sm dark:text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500" placeholder="e.g. Dean Approval" required>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">Assigned Role</label>
                            <select wire:model.defer="steps.{{ $index }}.assigned_role_id" class="w-full bg-transparent dark:bg-gray-950 border border-gray-300 dark:border-gray-800 rounded-lg px-3 py-1.5 text-sm dark:text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500" required>
                                <option value="" class="dark:bg-gray-950 text-gray-400">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role['id'] }}" class="dark:bg-gray-950 text-gray-900 dark:text-white">{{ $role['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">Action Button Label</label>
                            <input type="text" wire:model.defer="steps.{{ $index }}.action_label" class="w-full bg-transparent border border-gray-300 dark:border-gray-800 rounded-lg px-3 py-1.5 text-sm dark:text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500" placeholder="e.g. Endorse / Approve" required>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">Approved Status</label>
                            <input type="text" wire:model.defer="steps.{{ $index }}.approve_status" class="w-full bg-transparent border border-gray-300 dark:border-gray-800 rounded-lg px-3 py-1.5 text-sm dark:text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500" placeholder="e.g. approved / dean_signed" required>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">Rejected Status</label>
                            <input type="text" wire:model.defer="steps.{{ $index }}.reject_status" class="w-full bg-transparent border border-gray-300 dark:border-gray-800 rounded-lg px-3 py-1.5 text-sm dark:text-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500" placeholder="e.g. rejected / dean_returned" required>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end w-full md:w-auto self-stretch">
                        <button type="button" wire:click="removeStep({{ $index }})" class="p-2 text-danger-600 hover:bg-danger-50 dark:hover:bg-danger-950/20 rounded-lg transition" title="Delete Stage">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex items-center justify-end gap-3 border-t border-gray-200 dark:border-gray-800 pt-4">
        <button type="button" wire:click="save" class="fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-primary fi-btn-color-primary bg-primary-600 hover:bg-primary-500 text-white shadow-sm gap-1.5 px-4 py-2.5 text-sm inline-flex">
            <span wire:loading.remove wire:target="save">Save Changes</span>
            <span wire:loading wire:target="save" class="flex items-center gap-1.5">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Saving...
            </span>
        </button>
    </div>
</div>
