<section class="panel admin-delete-section">
    <div class="workflow-card-head"><span class="workflow-title-icon" style="color:#d71938;">✕</span><h2>Delete Request</h2></div>
    <div class="admin-delete-body">
        <p>Permanently delete this request, its attachments, and its related history. This action cannot be undone.</p>
        <form method="POST"
              action="{{ route('requests.destroy', $propertyRequest) }}"
              data-admin-password-form
              data-action-title="Delete Request {{ $propertyRequest->reference_no }}"
              data-action-description="This will permanently delete the request, its attachments, and its related history. This action cannot be undone.">
            @csrf
            @method('DELETE')
            <input type="hidden" name="current_password" data-admin-password-field>
            <button class="button button-danger" type="submit">
                <span aria-hidden="true">✕</span> Delete This Request
            </button>
        </form>
    </div>
</section>
