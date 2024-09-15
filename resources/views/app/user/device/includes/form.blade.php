<form id="dataForm" action="{{ route('app.admin.user.store') }}" method="POST" autocomplete="off">
    @csrf
    <input type="hidden" name="id">
    <div class="mb-1">
        <label for="name" class="form-label">User id</label>
        <input type="text" name="name" class="form-control form-control-sm" required>
    </div>
    <div class="mb-1">
        <label for="name" class="form-label">Name (Max Length = 24)</label>
        <input type="text" name="username" class="form-control form-control-sm" required maxlength="24">
    </div>
    <div class="mb-1">
        <label for="name" class="form-label">Role</label>
        <input type="number" name="role" class="form-control form-control-sm" required>
    </div>
    <button type="submit" class="btn btn-dark btn-sm my-3 w-100">Save</button>
</form>