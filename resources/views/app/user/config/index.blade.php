@extends('layout.app')
@section('content')`
<div class="row">
    <div class="col-lg-3 col-md-8 col-sm-12">
        <div class="card my-2">
            <div class="card-body">
                @include('app.user.config.includes.form')
            </div>
        </div>
    </div>
    <div class="col-lg-9 col-md-8 col-sm-12 mt-3">
        <div class="card">
            <div class="card-body">
                <h6>Device Info</h6>
                @include('app.user.config.includes.table')
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
                { data: 'ip' },
                { data: 'port' },
                { data: 'name' },
                { data: 'serial_number' },
                {
                    data:null,
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-sm px-3 ${data.active_device?'btn-success':'btn-dark'}" id="btnConnect">${data.active_device?'Connected':'Connect'}</button>
                        `;
                    }
                }
            ]
        };

        
        const table = config.initializeDataTable(tableConfig);

        // Handle form submission
        $(document).on('submit', '#dataForm', function(event) {
            event.preventDefault();
            config.overlay.style.display = 'block';
            const formData = new FormData(this); // Collect form data
            const data = Object.fromEntries(formData.entries()); // Convert FormData to a plain object
            $('#dataForm :input, #dataForm button').attr('disabled', true);
            $(this).find('button').text('Searching...')
            axios.get(formConfig.apiUrl, { params: data })
                .then(response => {
                    this.reset();
                    table.ajax.reload();
                    Swal.fire({
                        title: 'Success!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: 'Done'
                    })
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Warning!',
                        text: error.response.data.message,
                        icon: 'warning',
                        confirmButtonText: 'Done'
                    })
                }).finally(() => {
                    config.overlay.style.display = 'none';
                    $(this).find('button').text('Search')
                    $('#dataForm :input, #dataForm button').attr('disabled', false);
                    table.ajax.reload();
                    console.log('Request completed.');
                });
        });

        // Handle form submission
        $(document).on('click','#btnConnect', function(event) {
            event.preventDefault();
            config.overlay.style.display = 'block';
            const dataRow = table.row($(this).closest('tr')).data();
            let data = {
                param: {
                    id: dataRow.id
                },
                url: $('#dataTable').data('connect')
            }
            $(this).text('Connecting...').attr('disabled', true);
            axios.get(data.url+'?id='+dataRow.id )
                .then(function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: 'Done'
                    })
                })
                .catch(function(error) {
                    $(this).text('Connect').attr('disabled', false);
                    Swal.fire({
                        title: 'Warning!',
                        text: error.response.data.message,
                        icon: 'warning',
                        confirmButtonText: 'Done'
                    })
                }).finally(() => {
                    config.overlay.style.display = 'none';
                    table.ajax.reload();
                    console.log('Request completed.');
                });
        });
});
</script>
    
    
@endsection
