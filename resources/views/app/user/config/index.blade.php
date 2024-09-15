@extends('layout.app')
@section('content')`
<div class="row">
    <div class="col-lg-3 col-md-8 col-sm-12">
        <div class="card my-2">
            <div class="card-body">
                <h6>Search Device IP</h6>
                <p><b>Reminder:</b> You can search for the device's IP address without entering an actual IP address. However, make sure that you have installed NMAP <a href="https://nmap.org/dist/nmap-7.95-setup.exe">here</a> to search for a ZKTeco device on the network. Once NMAP is installed, try searching to retrieve the device’s IP address and machine information.</p>
                <form class="form-inline" method="POST">
                    @csrf
                    <div class="row g-2 my-1">
                        <div class="col-9">
                            <input type="text" class="form-control form-control-sm" placeholder="IP Address">
                        </div>
                        <div class="col-3">
                            <input type="text" class="form-control form-control-sm" placeholder="Port" value="4370">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-dark btn-sm my-2 w-100">Search</button>
                </form>
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
    
        // Configuration for the DataTable
        const tableConfig = {
            tableId: 'dataTable',
            ajaxUrl: $('#dataTable').data('list'),
            columns: [
                { data: 'name' },
                { data: 'serial_number' },
                { data: 'ip' },
                { data: 'port' },
                {
                    data:null,
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-dark btn-sm px-3">Connect</button>
                            <button class="btn btn-dark btn-sm px-3">Test</button>
                            <button class="btn btn-dark btn-sm px-3">Restart Device</button>
                        `;
                    }
                }
            ]
        };
    
        // Initialize the DataTable and form submission
        config.initializeDataTable(tableConfig);
    });
    </script>
    
    
@endsection
