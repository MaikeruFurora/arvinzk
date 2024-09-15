@extends('layout.app')
@section('content')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <table id="dataTable" class="table table-hover table-sm" data-list="{{ route('app.admin.config.list') }}">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Group</th>
                            <th>Device Name</th>
                            <th>Serial Number</th>
                            <th>IP Address</th>
                            <th>Port</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    
        // Configuration for the DataTable
        const tableConfig = {
            tableId: 'dataTable',
            ajaxUrl: $('#dataTable').data('list'),
            columns: [
                { data: 'user_name' },
                { data: 'group_name' },
                { data: 'name' },
                { data: 'serial_number' },
                { data: 'ip' },
                { data: 'port' },
            ]
        };
    
        // Initialize the DataTable and form submission
        config.initializeDataTable(tableConfig);
    });
    </script>
@endsection