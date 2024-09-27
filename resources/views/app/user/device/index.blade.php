@extends('layout.app')
@section('content')
@if (!$checkUtilityNeeded['status'])
    @include('layout.no-device-found', $checkUtilityNeeded)
@else
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Maintenance!</strong>
            <small>This page is under maintenance</small>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-12">
        <div class="card">
            <div class="card-body">
                @include('app.user.device.includes.form')
            </div>
        </div>
    </div>
    <div class="col-lg-9 col-md-9 col-sm-12">
        <div class="card">
            <div class="card-body">
                @include('app.user.device.includes.table')
            </div>
        </div>
    </div>
</div>
@endif
@endsection
@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = $("#dataTable").DataTable({
            orderable: false,
            ajax: {
                url: $('#dataTable').data('list'), // The route to fetch attendance log data
                type: 'GET',
            },
            columns: [
                { data: 'uid' },
                { data: 'userid' },
                { data: 'name' },
            ]
        })
        $("#dataForm").on('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission
                const formData = new FormData(this); // Collect form data
                const data     = Object.fromEntries(formData.entries()); // Convert FormData to a plain object
                const url      = document.getElementById('dataForm').action
                // Send the data using Axios
                axios.post(url, data).then(response => { 
                    this.reset(); 
                    Swal.fire({
                        title: 'Success!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: 'Done'
                    })
                    table.ajax.reload();
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Warning!',
                        text: error.response.data.message,
                        icon: 'warning',
                        confirmButtonText: 'Done'
                    })
                }).finally(() => {
                    
                    console.log('Request completed.');
                });
                
        });
    });

    </script>
@endsection