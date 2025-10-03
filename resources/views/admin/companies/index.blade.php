@extends('layouts.admin')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Tracking Companies</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        
                        <button class="btn btn-tool" type="button" id="sync-service">
                            <i class="fas fa-sync"></i>
                        </button>

                        <a class="btn btn-tool" href="{{route('admin.services.index')}}">
                            <i class="fas fa-caret-left"></i> Services
                        </a>
                        
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <x-message/>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Key</th>
                                <th>URL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companies as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->tracking_key}}</td>
                                <td>{{$item->url}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$companies->links()}}
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    $(document).ready(function(){
        $('#sync-service').on('click', function(event){
            event.preventDefault();

            $.ajax({
                url: "{{route('admin.companies.sync')}}",
                type: 'get',
                dataType: 'json',
                beforeSend: function(){
                    $("#sync-service i").addClass('fa-spin');
                    $("#sync-service").attr('disabled', true);
                },
                complete: function(){
                    $("#sync-service i").removeClass('fa-spin');
                    $("#sync-service").removeAttr('disabled');
                },
                success: function(response){
                    // Swal.fire({
                    //           title: 'Error!',
                    //           text: 'Do you want to continue',
                    //           icon: 'error',
                    //           toast: true,
                    //           confirmButtonText: 'Cool'
                    //         })
                    Toast.fire({
                              icon: "success",
                              title: "Tracking companies synced with Binary IT Lab"
                            });
                },
                error: function(error){
                    toastr.error('Failed to sync. Contact your developer.');
                    console.log(error);
                }
            });


        });
    });
</script>
@endsection