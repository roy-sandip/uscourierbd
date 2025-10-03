@extends('layouts.admin')

@section('title', 'AdminLTE')

@section('content_header')
    <h1 class="m-0 text-dark">Shipments Archive</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <form action="{{route('admin.shipments.filter')}}" method="POST" class="row" id="shipmentsFilter">
                        @csrf
                        <div class="form-group col">
                            <label for="">Total: {{$shipments->total()}}</label>
                        </div>
                        <div class="form-group col-sm-4 col-md-3">
                            <div class="input-group">
                                <div class="input-daterange input-group input-group-sm" id="datepicker">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text">Date Range</span>
                                    </span>
                                    <input type="text" id="dateRangePicker" class="form-control-sm form-control" name="date_range" />
                                    <span class="input-group-append">
                                            <button class="btn btn-sm btn-warning" onclick="$('#dateRangePicker').val('')" type="button">Clear</button>
                                    </span>

                                    
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 col-md-2">
                            <select name="service" id="" class="form-control form-control-sm select2 @error('service') is-invalid @enderror" data-placeholder="Select Service" required>
                                <option selected value="all">All Service</option>
                                @foreach($services as $item)
                                <option value="{{$item->id}}" @if(request()->query('service') == $item->id) selected @endif >{{$item->label}}</option>
                                @endforeach
                            </select>
                        </div>
                        @can('admin')
                        <div class="form-group col-sm-4 col-md-2">
                          <select name="agent" id="" class="form-control form-control-sm select2 @error('agent') is-invalid @enderror" data-placeholder="Select Agent" required>
                                <option selected value="all">All Agents</option>
                                @foreach($agents as $item)
                                <option value="{{$item->id}}" @if(request()->query('agent') == $item->id) selected @endif >{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div> 

                       
                        @endcan

                        <div class="form-group col">
                            <button class="btn btn-primary btn-sm" type="submit">Filter</button>
                        </div>
                    </form>
                </div>


                <div class="card-body table-responsive">
                   <x-message/>
                    <table class="table table-stripped table-sm table-hover">
                        <thead>
                            <tr>
                                <th>AWB</th>
                                <th>Sender</th>
                                <th>Receiver</th>
                                <th>Destination</th>
                                <th>Item</th>
                                <th>Weight</th>
                                <th>Date</th>
                                <th>Service</th>
                                @can('mainbranch')
                                <th>Agent</th>
                                @endcan
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shipments as $item)
                            <tr>
                                <td>{{$item->awb}}</td>
                                <td>{{$item->shipper->name}}</td>
                                <td>{{$item->receiver->name}}</td>
                                <td>{{$item->receiver->country}}</td>
                                <td>{{Str::limit($item->description, 25)}}</td>
                                <td>{{$item->gross_weight}}KG</td>
                                <td>{{$item->bookingDate->format('d M Y')}}</td>
                                <td>{{$item->service->label}}</td>
                                @can('admin')
                                <td>{{$item->agent->name}}</td>
                                @endcan
                                <td>
                                    <a href="{{route('admin.shipments.show', $item->id)}}" class="btn btn-primary btn-sm">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
                <div class="card-footer">
                    {{$shipments->links()}}
                </div>
            </div>
        </div>
    </div>
@stop


@section('js')
<script>
    //submitForm("#shipmentsFilter");
</script>


<script>
    $(document).ready(function(){
        $('.select2').select2();
        // $('#datetimepicker1').datetimepicker({
        //     format: 'MMMM D, YYYY'
        // });

        // $('#datepicker').datepicker({
        //     autoclose: true,
        //     todayBtn: "linked"
        // });


    });
</script>

<script>
    $('#dateRangePicker').daterangepicker({
    "showDropdowns": true,
    "timePickerSeconds": true,
    "maxSpan": {
        "days": 180
    },
    ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    "locale": {
        "format": "MM-DD-YYYY",
        "separator": "to",
        "applyLabel": "Apply",
        "cancelLabel": "Clear",
        "fromLabel": "From",
        "toLabel": "To",
        "customRangeLabel": "Custom",
        "weekLabel": "W",
        "daysOfWeek": [
            "Su",
            "Mo",
            "Tu",
            "We",
            "Th",
            "Fr",
            "Sa"
        ],
        "monthNames": [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        ],
        "firstDay": 1
    },
    "alwaysShowCalendars": true,
    //"startDate": "09/01/2025",
    //"endDate": "{{now()->format('m/d/Y')}}"
    "maxDate" : moment()
}, function(start, end, label) {
    // $("#rangeFrom").val(start.format('YYYY-MM-DD'));
    // $("#rangeTo").val(end.format('YYYY-MM-DD'));
  console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
});

$("#dateRangePicker").on('cancel.daterangepicker', function(ev, picker) {
  //do something, like clearing an input
  $('#dateRangePicker').val('');
});

</script>
@endsection