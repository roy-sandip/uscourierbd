@extends('layouts.admin')


@section('content_header')
    <h1 class="m-0 text-dark">Edit Service # {{$service->id}}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Service Information</h5>
                </div>
                <div class="card-body row">
                    <div class="col-md-4 offset-md-4">
                    <x-message/>
                        <form action="{{route('admin.services.update', $service->id)}}" method="POST">
                            @csrf
                            @method('put')

                            <div class="form-group required">
                                <label for="">Service Name</label>
                                <input type="text" name="label" class="form-control @error('label') is-invalid @enderror" value="{{old('label', $service->label)}}" required>
                            </div>

                            <div class="form-group required">
                                <label for="">Public Name</label>
                                <input type="text" name="public_label" class="form-control @error('public_label') is-invalid @enderror" value="{{old('public_label', $service->public_label)}}" required>
                            </div>

                            <div class="form-group required">
                                <label for="">Tracking Provider</label>
                                <select name="company" id="" class="form-control select2" data-placeholder="Select Company">
                                    <option value="" selected disabled></option>
                                    @foreach($companies as $item)
                                      <option value="{{$item->id}}" @if($service->company_id == $item->id) selected @endif >{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">Update</button>
                                <button class="btn btn-default" type="reset">Clear</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    $(document).ready(function(){
        $('.select2').select2();
    });
</script>
@endsection