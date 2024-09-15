@extends('layout.app')
@section('content')
<div class="row">
    <div class="col-lg-3 col-md-4 col-sm-12">
        <div class="card">
            <div class="card-body">
                @include('app.admin.group.includes.form')
            </div>
        </div>
    </div>
    <div class="col-lg-9 col-md-8 col-sm-12">
        <div class="card">
            <div class="card-body">
                @include('app.admin.group.includes.table')
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuration for the form
        const formConfig = {
            formId: 'dataForm',
            apiUrl: document.getElementById('dataForm').action,
        };
    
        // Configuration for the DataTable
        const tableConfig = {
            tableId: 'dataTable',
            ajaxUrl: $('#dataTable').data('list'),
            columns: [
                { data: 'name' },
                { data: 'active',
                    render: function(data, type, row) {
                        return data ? 'YES' : 'NO';
                    }
                },
                {
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return '<button class="btn btn-dark btn-sm px-3">Edit</button>';
                    }
                }
            ]
        };
    
        // Initialize the DataTable and form submission
        const table = config.initializeDataTable(tableConfig);
        config.handleFormSubmission(formConfig, table);
    });
</script>
    
    
@endsection