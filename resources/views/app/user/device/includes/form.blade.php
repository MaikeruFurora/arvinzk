<form id="dataForm" action="{{ route('app.user.config.user.device.store') }}" autocomplete="off">
    @csrf
    <input type="hidden" name="id">
    <div class="mb-1">
        <label for="name" class="form-label">User id (Employee ID)</label>
        <input type="text" name="userid" class="form-control form-control-sm" required maxlength="9">
    </div>
    <div class="mb-1">
        <label for="name" class="form-label">Name (Max Length = 24)</label>
        <input type="text" name="name" class="form-control form-control-sm" required maxlength="24">
    </div>
    <button type="submit" class="btn btn-dark btn-sm my-3 w-100">Save</button>
</form>