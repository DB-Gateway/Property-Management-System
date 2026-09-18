@if($viewer->isAdmin())
    <div class="workflow-undo-row">
        <span>Need to correct this step? Uploaded files and details will be kept.</span>
        <form method="POST"
              action="{{ route('requests.workflow.undo', ['propertyRequest' => $propertyRequest, 'stage' => $stage]) }}"
              data-undo-workflow-form
              data-stage-label="{{ $label }}"
              data-cascade-message="{{ $cascadeMessage }}">
            @csrf
            @method('PATCH')
            <button class="button button-undo" type="submit" aria-label="Undo {{ $label }}">
                <span aria-hidden="true">&#8630;</span> Undo {{ $label }}
            </button>
        </form>
    </div>
@endif
