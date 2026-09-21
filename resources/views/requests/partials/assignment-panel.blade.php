@php
    $inHouse = $propertyRequest->isInHouse();
    $awaitingReview = $propertyRequest->isAwaitingPmReview();
    $canManageAssignment = $viewer->isManager();
    $inHouseCompleted = $inHouse && (bool) $propertyRequest->in_house_completed_at;
@endphp

<section id="request-assignment" class="panel workflow-card request-assignment-card">
    <div class="workflow-card-head">
        <span class="workflow-title-icon" aria-hidden="true">&#8644;</span>
        <h2>Request Assignment</h2>
        <span class="assignment-route-badge {{ $inHouse ? 'assignment-in-house' : '' }}">{{ $awaitingReview ? 'Awaiting PM Review' : ($inHouse ? 'In house' : 'Dial-A') }}</span>
    </div>

    <div class="assignment-panel-body">
        @if($awaitingReview)
            <p class="assignment-attribution">The PM team will decide the priority, explain the decision, and assign this request before work begins.</p>
        @elseif($propertyRequest->assignment_by_label)
            <p class="assignment-attribution">Assigned by <strong>{{ $propertyRequest->assignment_by_label }}</strong>
                @if($propertyRequest->assigned_at)<span>{{ $propertyRequest->assigned_at->format('M d, Y h:i A') }}</span>@endif
            </p>
        @else
            <p class="assignment-attribution">Assigned to Dial-A when the dealer submitted this request.</p>
        @endif

        @if($propertyRequest->pm_reviewed_at)
            <p class="assignment-attribution">Reviewed by <strong>{{ $propertyRequest->pm_reviewer_label }}</strong><span>{{ $propertyRequest->pm_reviewed_at->format('M d, Y h:i A') }}</span></p>
        @endif
        @if(!$awaitingReview && $propertyRequest->priority_remarks)
            <div class="in-house-work-order-text"><strong>PM decision: {{ $propertyRequest->priority_label }}</strong><br>{{ $propertyRequest->priority_remarks }}</div>
        @endif

        @if($canManageAssignment)
            <form method="POST" action="{{ route('requests.assignment.update', $propertyRequest) }}" class="assignment-form" data-pm-confirm-form data-pm-action="{{ $awaitingReview ? 'Confirm PM Review' : 'Confirm Assignment and Priority' }}">
                @csrf @method('PATCH')
                @if($awaitingReview)<input type="hidden" name="review_pending" value="1">@endif
                <div class="form-field">
                    <label for="review_priority">Request Priority</label>
                    <select id="review_priority" name="priority" required>
                        <option value="regular" @selected(old('priority', $propertyRequest->priority) === 'regular')>Regular</option>
                        <option value="urgent" @selected(old('priority', $propertyRequest->priority) === 'urgent')>Urgent</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="assignment_type">Assign to</label>
                    <select id="assignment_type" name="assignment_type" required>
                        <option value="dial_a" @selected(old('assignment_type', $propertyRequest->assignment_type) === 'dial_a')>Dial-A</option>
                        <option value="in_house" @selected(old('assignment_type', $propertyRequest->assignment_type) === 'in_house')>In house</option>
                    </select>
                </div>
                <div class="form-field assignment-remarks">
                    <label for="review_remarks">Decision remarks for the dealer</label>
                    <textarea id="review_remarks" name="remarks" rows="3" maxlength="1000" required placeholder="Explain why you chose this priority and assignment.">{{ old('remarks', $propertyRequest->priority_remarks) }}</textarea>
                </div>
                <button class="button button-primary" type="submit">{{ $awaitingReview ? 'Review & Assign' : 'Re-assign / Proceed' }}</button>
                <p class="assignment-form-help">Your decision and remarks will be visible to the dealer. Continue to confirm this action.</p>
            </form>
        @endif

        @if($inHouse)
            <div class="in-house-workflow">
                <div class="in-house-step">
                    <div class="in-house-step-heading"><span class="in-house-step-number">1</span><h3>Inspection Request</h3><span class="stage-status stage-complete">Requested</span></div>
                    <p class="assignment-attribution">Requested c/o <strong>{{ $propertyRequest->in_house_inspection_by_label }}</strong></p>
                    <p class="assignment-date">{{ $propertyRequest->in_house_requested_at?->format('M d, Y h:i A') }}</p>
                </div>

                <div class="in-house-step">
                    <div class="in-house-step-heading"><span class="in-house-step-number">2</span><h3>Work Order</h3><span class="stage-status {{ $propertyRequest->in_house_work_order_at ? 'stage-complete' : 'stage-waiting' }}">{{ $propertyRequest->in_house_work_order_at ? 'Saved' : 'Pending' }}</span></div>
                    @if($propertyRequest->in_house_work_order_by_label)
                        <p class="assignment-attribution">c/o <strong>{{ $propertyRequest->in_house_work_order_by_label }}</strong></p>
                        <p class="assignment-date">{{ $propertyRequest->in_house_work_order_at?->format('M d, Y h:i A') }}</p>
                    @endif
                    @if($canManageAssignment && !$inHouseCompleted)
                        <form method="POST" action="{{ route('requests.in-house.work-order', $propertyRequest) }}" class="in-house-detail-form" data-pm-confirm-form data-pm-action="Save Work Order">
                            @csrf @method('PATCH')
                            <div class="form-field">
                                <label for="in_house_work_order">Work Order details</label>
                                <textarea id="in_house_work_order" name="in_house_work_order" rows="4" maxlength="20000" required placeholder="Describe the work to be carried out…">{{ old('in_house_work_order', $propertyRequest->in_house_work_order) }}</textarea>
                            </div>
                            <p class="assignment-form-help">Saving records c/o {{ $viewer->role_label }} — {{ $viewer->name }}.</p>
                            <div class="in-house-form-actions">
                                <button class="button button-primary" type="submit">Save Work Order</button>
                            </div>
                        </form>
                    @else
                        <div class="in-house-work-order-text">{{ $propertyRequest->in_house_work_order ?: 'Work Order details have not been entered yet.' }}</div>
                    @endif
                </div>

                <div class="in-house-step">
                    <div class="in-house-step-heading"><span class="in-house-step-number">3</span><h3>Completion Report</h3><span class="stage-status {{ $inHouseCompleted ? 'stage-complete' : 'stage-waiting' }}">{{ $inHouseCompleted ? 'Completed' : 'Pending' }}</span></div>
                    @if($propertyRequest->in_house_completion_by_label)
                        <p class="assignment-attribution">{{ $inHouseCompleted ? 'c/o' : 'Previous report c/o' }} <strong>{{ $propertyRequest->in_house_completion_by_label }}</strong></p>
                        @if($inHouseCompleted)<p class="assignment-date">{{ $propertyRequest->in_house_completed_at->format('M d, Y h:i A') }}</p>@endif
                    @endif
                    @if($propertyRequest->inHouseCompletionFiles->isNotEmpty())
                        <div class="workflow-file-gallery compact-file-gallery">
                            @include('requests.partials.files', ['files' => $propertyRequest->inHouseCompletionFiles, 'gallery' => true])
                        </div>
                    @endif
                    @if($canManageAssignment && !$inHouseCompleted)
                        @if($propertyRequest->in_house_work_order_at)
                            <form method="POST" action="{{ route('requests.in-house.completion', $propertyRequest) }}" enctype="multipart/form-data" class="in-house-detail-form" data-pm-confirm-form data-pm-action="Upload Report & Complete Request">
                                @csrf
                                <div class="form-field">
                                    <label for="in_house_completion_files">Attach Completion Report</label>
                                    <div class="file-drop"><input id="in_house_completion_files" name="completion_files[]" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.xls,.xlsx,.csv" multiple required data-file-input data-max-files="5"></div>
                                    <small>Images, PDF, Word or Excel. Up to 5 files, 20 MB each.</small>
                                </div>
                                <p class="assignment-form-help">Uploading completes this request and records c/o {{ $viewer->role_label }} — {{ $viewer->name }}.</p>
                                <div class="in-house-form-actions">
                                    <button class="button button-primary" type="submit">Upload &amp; Complete Request</button>
                                </div>
                            </form>
                        @else
                            <p class="assignment-form-help">Save the Work Order to upload the Completion Report.</p>
                        @endif
                    @elseif(!$inHouseCompleted)
                        <p class="assignment-form-help">Awaiting the PM team's Completion Report.</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</section>
