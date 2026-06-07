@php
    $steps = [
        [
            'key' => 'pending',
            'label' => 'Submitted',
            'description' => 'Grading sheet uploaded.',
        ],
        [
            'key' => 'to_endorse',
            'label' => 'Endorsed',
            'description' => 'Approved by Graduate School Staff',
        ],
        [
            'key' => 'to_verify',
            'label' => 'Verified',
            'description' => 'Confirmed and validated by University Registrar',
        ],
    ];

    $currentStatus = $current ?? $state ?? 'pending';
    $activeIndex = collect($steps)->search(fn (array $step) => $step['key'] === $currentStatus);
    $activeIndex = $activeIndex === false ? (int) ($currentStatus === 'submitted') * count($steps) : $activeIndex;
@endphp

<div class="fi-sc-wizard fi-contained">
    <ol class="fi-sc-wizard-header" role="list">
        @foreach ($steps as $index => $step)
            @php
                $isCompleted = $index < $activeIndex;
                $isActive = $index === $activeIndex;
                $stepClasses = $isCompleted ? 'fi-completed' : ($isActive ? 'fi-active' : '');
            @endphp

            <li class="fi-sc-wizard-header-step {{ $stepClasses }}">
                <div class="fi-sc-wizard-header-step-btn">
                    <div class="fi-sc-wizard-header-step-icon-ctn">
                        @if ($isCompleted)
                            <svg class="fi-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                        @else
                            <span class="fi-sc-wizard-header-step-number">
                                {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                            </span>
                        @endif
                    </div>

                    <div class="fi-sc-wizard-header-step-text">
                        <span class="fi-sc-wizard-header-step-label">
                            {{ $step['label'] }}
                        </span>

                        @if (!empty($step['description']))
                            <span class="fi-sc-wizard-header-step-description">
                                {{ $step['description'] }}
                            </span>
                        @endif
                    </div>
                </div>

                @if (! $loop->last)
                    <svg
                        fill="none"
                        preserveAspectRatio="none"
                        viewBox="0 0 22 80"
                        aria-hidden="true"
                        class="fi-sc-wizard-header-step-separator"
                    >
                        <path
                            d="M0 -2L20 40L0 82"
                            stroke-linejoin="round"
                            stroke="currentcolor"
                            vector-effect="non-scaling-stroke"
                        ></path>
                    </svg>
                @endif
            </li>
        @endforeach
    </ol>
</div>