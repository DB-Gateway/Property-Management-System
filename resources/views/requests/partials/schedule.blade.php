<section class="panel workflow-card">
    <div class="workflow-card-head"><h2>Request Schedule</h2></div>
    <div class="workflow-card-body" style="padding: 18px;">
        @if($viewer->isDialA() && $propertyRequest->isAssignedTo($viewer) && !$isFullyCompleted)
            <form method="POST" action="{{ route('requests.schedule', $propertyRequest) }}">
                @csrf @method('PATCH')
                <div class="request-schedule-grid">
                    @foreach(\App\Services\RequestNotificationService::STAGES as [$label, $field, $completed])
                        <div class="form-field">
                            <label for="schedule_{{ $field }}">{{ $label }} date</label>
                            <input id="schedule_{{ $field }}" name="{{ $field }}" type="date" value="{{ old($field, $propertyRequest->$field?->format('Y-m-d')) }}" @disabled($propertyRequest->$completed)>
                        </div>
                    @endforeach
                </div>
                <button type="submit" class="button button-primary">Save schedule</button>
            </form>
        @else
            <div class="request-schedule-grid">
                @foreach(\App\Services\RequestNotificationService::STAGES as [$label, $field, $completed])
                    <div class="workflow-readonly"><span>{{ $label }} date</span><strong>{{ $propertyRequest->$field?->format('M d, Y') ?? 'Not scheduled' }}</strong></div>
                @endforeach
            </div>
        @endif
    </div>
</section>
