<form id="dataForm" action="{{ route('app.admin.user.store') }}" method="POST" autocomplete="off">
    @csrf
    <input type="hidden" name="id">
    <div class="mb-1">
        <label for="name" class="form-label">Name</label>
        <input type="text" name="name" class="form-control form-control-sm" required>
    </div>
    <div class="mb-1">
        <label for="name" class="form-label">Username</label>
        <input type="text" name="username" class="form-control form-control-sm" required>
    </div>
    <div class="mb-1">
        <label for="user_type_id" class="form-label">Type</label>
        <select name="user_type_id" id="user_type_id" class="form-select form-select-sm" required>
            <option value=""></option>
            @foreach ($user_types as $user_type)
            <option value="{{ $user_type->id }}">{{ strtoUpper($user_type->name) }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-1">
        <label for="group_id" class="form-label">Group</label>
        <select name="group_id" id="group_id" class="form-select form-select-sm" required>
            <option value=""></option>
            @foreach ($groups as $group)
            <option value="{{ $group->id }}">{{ $group->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-1">
        <label for="active" class="form-label">Active</label>
        <select name="active" id="active" class="form-select form-select-sm">
            <option value="1">YES</option>
            <option value="0">NO</option>
        </select>
    </div>
    <div class="mb-1">
        <label for="name" class="form-label">Password</label>
        <input type="password" name="password" class="form-control form-control-sm">
    </div>
    <button type="submit" class="btn btn-dark btn-sm my-3 w-100">Save</button>
</form>