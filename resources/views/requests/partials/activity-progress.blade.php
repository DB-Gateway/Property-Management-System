@php($steps = $requestItem->activity_progress)
<div class="activity-progress {{ ($compact ?? false) ? 'activity-progress-compact' : '' }}" aria-label="Request activity progress">
    @foreach($steps as $step)
        <div class="activity-progress-step progress-{{ $step['status'] }}">
            <span class="activity-progress-dot" aria-hidden="true"></span>
            <span class="activity-progress-name">{{ $step['name'] }}</span>
            <span class="activity-progress-status">{{ $step['label'] }}</span>
        </div>
    @endforeach
</div>
