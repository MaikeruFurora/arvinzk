<div class="table-responsive">
    <table id="dataTable" class="table table-hover table-sm" 
data-list="{{ route('app.user.config.list') }}"
data-connect="{{ route('app.user.config.connect') }}"
>
    <thead>
        <tr>
            <th>IP Address</th>
            <th>Port</th>
            <th>Device Name</th>
            <th>Serial Number</th>
            <th width="15%">Action</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
</div>