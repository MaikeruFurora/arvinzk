<form id="dataForm" action="{{ route('app.admin.group.store') }}" method="POST" autocomplete="off">
    @csrf
    <input type="hidden" name="id">
    <label for="name" class="form-label">Name</label>
    <input type="text" name="name" class="form-control form-control-sm">
    <label for="active" class="form-label">Active</label>
    <select name="active" id="active" class="form-select form-select-sm">
        <option value="1">YES</option>
        <option value="0">NO</option>
    </select>
    <button type="submit" class="btn btn-dark btn-sm my-3 w-100">Save</button>
</form>