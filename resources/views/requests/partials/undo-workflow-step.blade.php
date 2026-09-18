@if($viewer->isAdmin())
    <div class="workflow-undo-row">
        <span>Need to correct this step? Uploaded files and details will be kept.</span>
        <form method="POST"
              action="{{ route('requests.workflow.undo', ['propertyRequest' => $propertyRequest, 'stage' => $stage]) }}"
              data-admin-password-form
              data-action-title="Undo {{ $label }}"
              data-action-description="{{ $cascadeMessage }} Uploaded files and entered details will be kept.">
            @csrf
            @method('PATCH')
            <input type="hidden" name="current_password" data-admin-password-field>
            <button class="button button-undo" type="submit" aria-label="Undo {{ $label }}">
                <span aria-hidden="true">&#8630;</span> Undo {{ $label }}
            </button>
        </form>
    </div>
@endif
