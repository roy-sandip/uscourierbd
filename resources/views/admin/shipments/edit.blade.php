@extends('layouts.admin')

@section('content')
<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-message/>
                <form action="{{route('admin.shipments.update', $shipment->id)}}" method="POST" autocomplete="false">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-stripped text-dark">
                                <thead>
                                    <tr>
                                        <th>Reference</th>                                        
                                        <th>Pieces</th>                                        
                                        <th>Agent</th>
                                        <th>Service</th>
                                        <th>Gross Weight</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" name="reference" class="form-control form-control-sm @error('reference') is-invalid @enderror" value="{{old('reference', $shipment->reference)}}">
                                        </td>
                                        <td>
                                            <input type="number" min="1" max="100" name="pieces" class="form-control form-control-sm @error('reference') is-invalid @enderror" value="{{old('pieces', $shipment->pieces)}}">
                                        </td>
                                        
                                        <td>
                                            <select name="agent_id" id="" class="form-control form-control-sm select2 @error('agent_id') is-invalid @enderror" data-placeholder="Select Agent" required>
                                                <option disabled selected></option>
                                                @foreach($agents as $item)
                                                <option value="{{$item->id}}" @if(old('agent_id', $shipment->agent_id) == $item->id) selected @endif >{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        
                                         <td>
                                            <select name="service_id" id="" class="form-control form-control-sm select2 @error('service_id') is-invalid @enderror" data-placeholder="Select Service" required>
                                                <option disabled selected></option>
                                                @foreach($services as $item)
                                                <option value="{{$item->id}}" @if(old('service_id', $shipment->service_id) == $item->id) selected @endif >{{$item->label}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <x-input-field class="gross_weight weight" style="max-width: 100px;" min="0" step="0.01" name="gross_weight" type="number" :value="$shipment->gross_weight" append="KG" /> 
                                        </td>
                                    </tr>
                                </tbody>
                            </table>   
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-info border border-info">
                                <div class="card-header">
                                    <h5 class="card-title">Sender</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <input name="shipper[name]" type="text" class="form-control form-control-sm @error('shipper.name') is-invalid @enderror" placeholder="Sender Name" value="{{old('shipper.name', $shipment->shipper->name)}}" required>
                                    </div>
                                    <div class="form-group">
                                      <textarea name="shipper[street]" cols="30" rows="5" class="form-control form-control-sm @error('shipper.street') is-invalid @enderror" placeholder="Street Address">{{old('shipper.street', $shipment->shipper->street)}}</textarea>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-6">
                                            <input name="shipper[city]" type="text" class="form-control form-control-sm @error('shipper.city') is-invalid @enderror" placeholder="City" value="{{old('shipper.city', ($shipment->shipper->city ?? 'DHAKA'))}}">
                                        </div>
                                        <div class="col-6">
                                            <input name="shipper[zip]" type="text" class="form-control form-control-sm @error('shipper.zip') is-invalid @enderror" placeholder="Post Code" value="{{old('shipper.zip', ($shipment->shipper->zip ?? 1200))}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <select name="shipper[country]" id="" class="form-control form-control-sm selectpicker  @error('shipper.country') is-invalid @enderror" data-placeholder="Select Agent" data-live-search="true" data-size="5" liveSearchNormalize="true"   required>
                                                        <option disabled>Country</option>
                                                        @foreach($countries as $item)
                                                        <option 
                                                            data-tokens="{{$item['code']}} @isset($item['alt']) $item['alt'] @endisset" 
                                                            value="{{$item['code']}}" 
                                                            data-content="<span class='badge badge-default'>{{$item['code']}}</span> {{$item['name']}}"
                                                            @if(old('shipper.country', 'BD') == $item['code']) selected @endif >
                                                            
                                                        </option>
                                                        @endforeach
                                        </select>
                                    </div>
                                    

                                    <x-input-field name="shipper.primary_contact" type="text" :value="$shipment->shipper->primary_contact" placeholder="Contact no" prepend="icon:fas fa-phone-alt" required="true" />

                                    <x-input-field name="shipper.email" type="email" :value="$shipment->shipper->email" placeholder="Email" prepend="icon:far fa-envelope" />

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-info border border-info">
                                <div class="card-header">
                                    <h5 class="card-title">Receiver</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <input name="receiver[name]" type="text" class="form-control form-control-sm @error('receiver.name') is-invalid @enderror" placeholder="Receiver Name" value="{{old('receiver.name', $shipment->receiver->name)}}" required>
                                    </div>
                                    <div class="form-group">
                                      <textarea name="receiver[street]" cols="30" rows="5" class="form-control form-control-sm @error('receiver.street') is-invalid @enderror" placeholder="Street Address">{{old('receiver.street', $shipment->receiver->street)}}</textarea>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-6">
                                            <input name="receiver[city]" type="text" class="form-control form-control-sm @error('receiver.city') is-invalid @enderror" placeholder="City" value="{{old('receiver.city', $shipment->receiver->city)}}">
                                        </div>
                                        <div class="col-6">
                                            <input name="receiver[zip]" type="text" class="form-control form-control-sm @error('receiver.zip') is-invalid @enderror" placeholder="ZIP" value="{{old('receiver.zip', $shipment->receiver->zip)}}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                             <x-input-field name="receiver.state" type="text" :value="$shipment->receiver->state"  placeholder="State" />
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                 <select name="receiver[country]" id="" class="form-control form-control-sm selectpicker  @error('receiver.country') is-invalid @enderror" data-placeholder="Select Agent" data-live-search="true" data-size="5" liveSearchNormalize="true"   required>
                                                        <option disabled selected>Country</option>
                                                        @foreach($countries as $item)
                                                        <option 
                                                            data-tokens="{{$item['code']}} @isset($item['alt']) $item['alt'] @endisset" 
                                                            value="{{$item['code']}}" 
                                                            data-content="<span class='badge badge-default'>{{$item['code']}}</span> {{$item['name']}}"
                                                            @if(old('receiver.country', $shipment->receiver->country) == $item['code']) selected @endif >
                                                            
                                                        </option>
                                                        @endforeach
                                                </select>
                                            </div>  
                                        </div>

                                    </div>

                                    

                                    <x-input-field name="receiver.primary_contact" type="text" :value="$shipment->receiver->primary_contact" placeholder="Contact no" prepend="icon:fas fa-phone-alt" required="true" />

                                    <x-input-field name="receiver.email" type="email" :value="$shipment->receiver->email" placeholder="Email" prepend="icon:far fa-envelope" />

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-12">
                                <label for="">Item Description</label> <span class="text-red">Separate each item with comma (,)</span>
                                <textarea name="description" id="" cols="30" rows="5" class="form-control form-control-sm @error('description') is-invalid @enderror">{{old('description', $shipment->description)}}</textarea>
                            </div>
                            <hr>
                            <div class="col-12">
                               <table class="table">
                                    <thead>
                                        <tr colspan="4"><b>Dimensions (L x W x H)cm</b></tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>    
                                                <x-input-field class="dimensions length" min="0" step="0.01" name="dimensions.length" type="number" :value="$shipment->dimensions->length" placeholder="Length"  />
                                            </td>
                                            <td>
                                                <x-input-field  class="dimensions width" min="0.00" name="dimensions.width" type="number" :value="$shipment->dimensions->width" placeholder="Width" />
                                            </td>
                                            <td>
                                                <x-input-field  class="dimensions height" min="0.00" name="dimensions.height" type="number" :value="$shipment->dimensions->height" placeholder="Height" />
                                            </td>
                                            <td>
                                                 <x-input-field class="volumetric_wt weight" id="volumetric_wt" style="text-align: right;" min="0.00" name="volumetric_weight" type="number" :value="$shipment->volumetric_weight" placeholder="0.00" append="KG" disabled />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>    
                            </div>
                        </div>

                        @can('admin')
                        <div class="col-md-4 offset-md-1">
                            <b>Billing Information</b>
                            <hr>
                            <x-input-field class="billed_weight"  min="0" step="0.01" name="billing.billed_weight" type="number" :value="$shipment->billing->billed_weight" inline="true" row="true" label="Billed Weight" append="KG" />
                             <div class="form-group row">
                                 <label for="" class="col-sm-2 col-md-4">Net Bill</label>
                                 <div class="input-group input-group-sm col-sm-10 col-md-8">
                                    <input type="number" name="billing[net_bill]" max="999999" class="form-control form-control-sm @error('billing.net_bill') is-invalid @enderror netBill billing" value="{{old('billing.net_bill', $shipment->billing->net_bill)}}">
                                    <div class="input-group-append"><span class="input-group-text">Taka</span></div> 
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label for="" class="col-sm-2 col-md-4">Extra Charge</label>
                                 <div class="input-group input-group-sm col-sm-10 col-md-8">
                                    <input type="number" name="billing[extra_charge]" max="99999" class="form-control form-control-sm @error('billing.extra_charge') is-invalid @enderror extraCharge billing" value="{{old('billing.extra_charge',$shipment->billing->extra_charge)}}">
                                    <div class="input-group-append"><span class="input-group-text">Taka</span></div> 
                                 </div>
                             </div>
                             <hr>
                             <div class="form-group row">
                                  <label for="" class="col-sm-2 col-md-4">Total Bill</label>
                                 <div class="input-group input-group-sm col-sm-10 col-md-8">
                                    <input type="number" max="999999" value="{{$shipment->billing->getBill()}}" class="form-control form-control-sm totalBill" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                        Taka
                                         </span>
                                    </div> 
                                 </div>
                             </div>
                             <hr>
                             <div class="form-group row">
                                 <label for="" class="col-sm-2 col-md-4">Paid</label>
                                 <div class="input-group input-group-sm col-sm-10 col-md-8">
                                    <input type="number" name="billing[total_paid]" max="999999" class="form-control form-control-sm @error('billing.total_paid') is-invalid @enderror totalPaid billing" value="{{old('billing.total_paid', $shipment->billing->getPaid())}}">
                                    <div class="input-group-append"><span class="input-group-text">Taka</span></div> 
                                 </div>
                             </div>
                            <!--  <div class="form-group row">
                                 <label for="" class="col-sm-2 col-md-4">Discount</label>
                                 <div class="input-group col-sm-10 col-md-8">
                                    <input type="number" max="99999999" class="form-control form-control-sm">
                                    <div class="input-group-append"><span class="input-group-text">Taka</span></div> 
                                 </div>
                             </div> -->
                             <div class="form-group row">
                                 <label for="" class="col-sm-2 col-md-4">Due</label>
                                 <div class="input-group input-group-sm col-sm-10 col-md-8">
                                    <input type="number" max="99999" value="{{$shipment->billing->getDue()}}" class="form-control form-control-sm totalDue" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                        Taka
                                         </span>
                                    </div> 
                                 </div>
                             </div> 



                           

                             <div class="form-group row">
                                 <label for="" class="col-sm-2 col-md-4">Comment</label>
                                 <div class="input-group input-group-sm col-sm-10 col-md-8">
                                    <input type="text"  name="billing[remark]" max="190" class="form-control form-control-sm" value="{{old('billing.remark', $shipment->billing->remark)}}">
                                 </div>
                             </div>
                        </div>
                        @endcan
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group row">
                                <label for="" class="col-sm-4">Operator</label>
                                <input type="text" name="received_by" class="form-control form-control-sm col-sm-8 @error('received_by') is-invalid @enderror" value="{{old('received_by', $shipment->received_by)}}">
                            </div>
                        </div>

                        <div class="col-md-3 offset-md-1">
                             <x-input-field name="received_at" class="received_at" prepend="Date" :value="$shipment->received_at->format('d/m/Y')" placeholder="Date" append="icon:far fa-calendar" />
                        </div>
                        <div class="col-md-3 offset-md-1">
                             <x-input-field name="est_delivery_date" class="est_delivery_date" prepend="Est. Delivery Date" :value="$shipment->est_delivery_date" placeholder="Date" value="{{now()->addDays(7)->format('d/m/Y')}}" append="icon:far fa-calendar" />
                        </div>
                    </div> 

                    <hr>

                    <div class="row">
                        <div class="col-12 text-center">
                            <button class="btn btn-primary" type="submit">Submit</button>
                            <button class="btn btn-default" type="reset">Clear</button>
                        </div>
                    </div>

                </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')

<script>
    $(document).ready(function(){
        $('.select2').select2();

        $('.received_at').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoApply: true,
            minYear: parseInt(moment().subtract(1, 'year').format('YYYY'),10),
            maxYear: parseInt(moment().add(6, 'month').format('YYYY'),10),
            drops: 'up',
            locale: {
              format: 'DD/MM/YYYY'
            }
        });

        $('.est_delivery_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoApply: true,
            minYear: parseInt(moment().subtract(1, 'year').format('YYYY'),10),
            maxYear: parseInt(moment().add(6, 'month').format('YYYY'),10),
            drops: 'up',
            locale: {
              format: 'DD/MM/YYYY'
            }
        });



        
      $(".billing").on('keyup', function (e) {
            var netBill = parseFloat($('.netBill').val()) || 0;
            var extraCharge = parseFloat($('.extraCharge').val()) || 0;
            var totalBill = (netBill + extraCharge);
            var totalPaid = parseFloat($('.totalPaid').val()) || 0;
            var due = totalBill - totalPaid;
            
            $('.totalDue').val(due);
            $('.totalBill').val(totalBill);

            // Update billing status
            if (due === 0 && totalBill > 0) {
                $(".billingStatus").val('paid');
            } else if (due > 0 && totalPaid > 0) {
                $(".billingStatus").val('due');
            } else if (totalPaid === 0 && totalBill > 0) {
                $(".billingStatus").val('pending');
            } else {
                $(".billingStatus").val('pending');
            }
        });


function calculateWeights() {
    var length = parseFloat($(".length").val()) || 0;
    var width  = parseFloat($(".width").val()) || 0;
    var height = parseFloat($(".height").val()) || 0;
    var net_wt = parseFloat($(".gross_weight").val()) || 0;

    // volumetric weight
    var v_wt = (length * width * height) / 5000;
    $("#volumetric_wt").val(v_wt.toFixed(2));

    // billed weight
    var billed_wt = (v_wt > net_wt) ? v_wt : net_wt;
    $(".billed_weight").val(billed_wt.toFixed(2));
}

// trigger calc on any change
$(".dimensions, .weight").on("change keyup", calculateWeights);



    });
</script>
@endsection