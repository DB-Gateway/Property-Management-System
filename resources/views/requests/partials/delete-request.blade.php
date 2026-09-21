<section class="panel admin-delete-section">
    <div class="workflow-card-head"><span class="workflow-title-icon" style="color:#d71938;">✕</span><h2>Delete Request</h2></div>
    <div class="admin-delete-body">
        <p>Permanently remove this request and all its attachments. <strong>This action cannot be undone.</strong></p>
        <form method="POST"
              action="{{ route('requests.destroy', $propertyRequest) }}"
              data-admin-password-form
              data-action-title="Delete Request {{ $propertyRequest->reference_no }}"
              data-action-description="This will permanently remove the request, all uploaded files, and related audit entries. This action cannot be undone.">
            @csrf
            @method('DELETE')
            <input type="hidden" name="current_password" data-admin-password-field>
            <button class="button button-danger" type="submit">
                <span aria-hidden="true">✕</span> Delete This Request
            </button>
        </form>
    </div>
</section>

