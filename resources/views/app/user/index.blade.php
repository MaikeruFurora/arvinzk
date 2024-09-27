@extends('layout.app')
@section('content')
@if (!$checkUtilityNeeded['status']) 
    @include('layout.no-device-found', $checkUtilityNeeded)
@else
<div class="row">
    
    
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header p-1">
                <div class="d-flex justify-content-between">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <button type="button"  class="btn btn-dark btn-sm" id="upload">Upload Attendance</button>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="input-group">
                            <input type="date" name="from" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                            <input type="date" name="to" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                            <button  class="btn btn-dark btn-sm" id="filter">Filter</button>
                          </div> 
                    </div>
                </div>
                </div>
                <div class="card-body pb-1">
               
                    <table class="table table-hover table-sm" id="dataTable" 
                    data-list="{{ route('app.user.home.attendanceLog') }}"
                    data-upload="{{ route('app.user.home.uploadAttendanceLog') }}">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div> 
        </div>
    </div>
</div>
@include('app.user.includes.upload-attendance')
@endif
@endsection

@section('js')
<script>
    const table = $('#dataTable').DataTable({
        orderable: false,
        ajax: {
            url: $('#dataTable').data('list'), // The route to fetch attendance log data
            type: 'GET',
            data: function (d) {
                // Append the date range to the request
                d.from = $('input[name=from]').val();
                d.to = $('input[name=to]').val();
            },
            beforeSend: function () {
                config.overlay.style.display = 'block';
            },
            complete: function () {
                config.overlay.style.display = 'none';

            }
        },
        columns: [
            { data: 'user_name',
                render:function(data){
                    return data ?? ''
                }
            },
            { data: 'date' },
            { data: 'time' },
            { data: 'checklog' },
        ]
    });

    // Filter button click event
    $('#filter').on('click', function() {
        table.ajax.reload();
    });

    $("#upload").on('click',function(){
        const allData  = table.rows().data().toArray();
        const jsonData = JSON.stringify(allData);
        const url      = $('#dataTable').data('upload')
        const from     = $('input[name=from]').val();
        const to       = $('input[name=to]').val();
        config.overlay.style.display = 'block';
        $(this).text('Uploading...').attr('disabled', true);
        axios.post(url,{
            data: jsonData, _token: config.token,from,to 
        }).then(response => {
            Swal.fire({
                title: 'Success!',
                text: response.data.message,
                icon: 'success',
                confirmButtonText: 'Done'
            })
        }).catch(error => {
            Swal.fire({
                title: 'Warning!',
                text: error.response.data.message,
                icon: 'warning',
                confirmButtonText: 'Done'
            })
        }).finally(() => {
            $(this).text('Uploading Attendance').attr('disabled', false);
            config.overlay.style.display = 'none';
            table.ajax.reload();
        });
        
    })

</script>
@endsection
