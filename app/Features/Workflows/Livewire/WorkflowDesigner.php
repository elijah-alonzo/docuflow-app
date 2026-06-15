<?php

namespace App\Features\Workflows\Livewire;

use App\Features\Workflows\Models\Workflow;
use App\Features\Workflows\Models\WorkflowStep;
use App\Features\Roles\Models\Role;
use Livewire\Component;

class WorkflowDesigner extends Component
{
    public Workflow $workflow;
    public array $steps = [];
    public array $roles = [];

    public function mount(Workflow $workflow)
    {
        $this->workflow = $workflow;
        $this->roles = Role::orderBy('name')->get(['id', 'name'])->toArray();
        $this->loadSteps();
    }

    public function loadSteps()
    {
        $this->steps = WorkflowStep::where('workflow_id', $this->workflow->id)
            ->orderBy('step_order', 'asc')
            ->get()
            ->map(fn($step) => [
                'id' => $step->id,
                'step_name' => $step->step_name,
                'assigned_role_id' => $step->assigned_role_id,
                'action_label' => $step->action_label,
                'approve_status' => $step->approve_status,
                'reject_status' => $step->reject_status,
                'step_order' => $step->step_order,
            ])
            ->toArray();

        if (empty($this->steps)) {
            $this->addStep();
        }
    }

    public function addStep()
    {
        $newOrder = count($this->steps) + 1;
        $defaultRoleId = !empty($this->roles) ? $this->roles[0]['id'] : null;

        $this->steps[] = [
            'id' => null,
            'step_name' => 'New Stage',
            'assigned_role_id' => $defaultRoleId,
            'action_label' => 'Approve',
            'approve_status' => 'approved',
            'reject_status' => 'rejected',
            'step_order' => $newOrder,
        ];
    }

    public function removeStep($index)
    {
        unset($this->steps[$index]);
        $this->steps = array_values($this->steps);
        $this->reorderSteps();
    }

    public function moveStepUp($index)
    {
        if ($index <= 0) return;

        $temp = $this->steps[$index - 1];
        $this->steps[$index - 1] = $this->steps[$index];
        $this->steps[$index] = $temp;

        $this->reorderSteps();
    }

    public function moveStepDown($index)
    {
        if ($index >= count($this->steps) - 1) return;

        $temp = $this->steps[$index + 1];
        $this->steps[$index + 1] = $this->steps[$index];
        $this->steps[$index] = $temp;

        $this->reorderSteps();
    }

    protected function reorderSteps()
    {
        foreach ($this->steps as $index => &$step) {
            $step['step_order'] = $index + 1;
        }
    }

    public function save()
    {
        $this->validate([
            'steps.*.step_name' => 'required|string|max:255',
            'steps.*.assigned_role_id' => 'required|exists:roles,id',
            'steps.*.action_label' => 'required|string|max:255',
            'steps.*.approve_status' => 'required|string|max:255',
            'steps.*.reject_status' => 'required|string|max:255',
        ], [
            'steps.*.step_name.required' => 'The step name is required.',
            'steps.*.assigned_role_id.required' => 'Please select an assigned role.',
            'steps.*.action_label.required' => 'Action label is required.',
            'steps.*.approve_status.required' => 'Approve status is required.',
            'steps.*.reject_status.required' => 'Reject status is required.',
        ]);

        // Delete steps that are no longer in state
        $keepIds = collect($this->steps)->pluck('id')->filter()->toArray();
        WorkflowStep::where('workflow_id', $this->workflow->id)
            ->whereNotIn('id', $keepIds)
            ->delete();

        // Save or update current steps
        foreach ($this->steps as $stepData) {
            WorkflowStep::updateOrCreate(
                [
                    'id' => $stepData['id'],
                    'workflow_id' => $this->workflow->id,
                ],
                [
                    'step_order' => $stepData['step_order'],
                    'step_name' => $stepData['step_name'],
                    'assigned_role_id' => $stepData['assigned_role_id'],
                    'action_label' => $stepData['action_label'],
                    'approve_status' => $stepData['approve_status'],
                    'reject_status' => $stepData['reject_status'],
                ]
            );
        }

        $this->loadSteps();
        session()->flash('message', 'Workflow steps saved successfully.');
    }

    public function render()
    {
        return view('admin.workflowdesigner.page');
    }
}
