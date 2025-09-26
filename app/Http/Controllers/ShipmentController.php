<?php

namespace App\Http\Controllers;

use App\Enums\BillingStatus;
use App\Enums\ContactType;
use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Service;
use App\Http\Requests\ShipmentRequest;
use App\Models\AgentBilling;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ShipmentController extends Controller
{
     protected $rules = [
  

        ];
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
         $request->validate([
            'start,' => ['nullable', 'date_format:Y-m-d', 'required_with:end'],
            'end'   => ['nullable', 'date_format:Y-m-d', 'lte:start'],
        ]);      

        $query = Shipment::query()
                            ->with('agent', 'shipper', 'receiver', 'service')
                            ->latest('awb');
        if(auth()->user()->can('admin'))                            
        {
            $request->whenFilled('agent', function($input)use($query){
                if($input == 'all')
                {
                    return;
                }
                $input = (int) $input;
                $query->where('agent_id', $input);
            });

        }

        $request->whenFilled('service', function($input)use($query){
            if($input == 'all')
            {
                return;
            }
            $input = (int) $input;
            $query->where('service_id', $input);
        });

        $request->whenFilled('start', function($input)use($query){
            $start = new Carbon($input);
            $query->where('received_at', '>=' ,$start);
        });
        $request->whenFilled('end', function($input)use($query){
            $end = new Carbon($input);
            $query->where('received_at', '<=' ,$end);
        });
        $shipments = $query->paginate(5)->withQueryString();
        $agents = auth()->user()->can('admin') ? Agent::all() : [];
        $services = Service::all();


        return view('admin.shipments.index', compact(['shipments', 'agents', 'services']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $agents = Agent::all();
        $services = Service::all();
        $shipment = Shipment::with(['shipper', 'receiver'])->find($request->get('clone')) ?? new Shipment();
        $countries = config('countries');
        
        return view('admin.shipments.create')->with([
            'agents'    => $agents,
            'services'  => $services,
            'shipment'  => $shipment,
            'countries' => $countries,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShipmentRequest $request)
    {
        
        
       //start storing records
      $shipment =  DB::transaction(function()use($request){
            //create addresses
            $shipper = (new Contact())->fillFromRequest($request->validated()['shipper'], ContactType::SHIPPER);
            $shipper->save();
            $receiver = (new Contact())->fillFromRequest($request->validated()['receiver'], ContactType::RECEIVER);
            $receiver->save();
            

            $shipment = (new Shipment())->fillFromRequest($request->validated());
            $shipment->sender_id = $shipper->id;
            $shipment->receiver_id = $receiver->id;            
            $shipment->save();
            $shipment->setAwB()->save();
            
            //create billing
            $billing = (new AgentBilling())->fillFromRequest($request->validated()['billing']);
            $shipment->agentBilling()->save($billing);
            return $shipment;
        });

      return redirect()->route('admin.shipments.show', $shipment->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipment $shipment)
    {

        return view('admin.shipments.show', compact('shipment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipment $shipment)
    {
        $agents = Agent::all();
        $services = Service::all();
        $countries = config('countries');
        $billing = $shipment->agentBilling;
        
        return view('admin.shipments.edit')->with([
            'agents'    => $agents,
            'services'  => $services,
            'shipment'  => $shipment,
            'countries' => $countries,
            'billing'   => $billing,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShipmentRequest $request, Shipment $shipment)
    {
        $shipment->load('shipper', 'receiver', 'agentBilling');
        
       //start storing records
      $shipment =  DB::transaction(function()use($request, $shipment){
            //create addresses
            $shipper = $shipment->shipper->fillFromRequest($request->validated()['shipper'], ContactType::SHIPPER);
            $shipper->save();
            $receiver = $shipment->receiver->fillFromRequest($request->validated()['receiver'], ContactType::RECEIVER);
            $receiver->save();
            

            $shipment->fillFromRequest($request->validated());            
            $shipment->save();
            
            //create billing
            $billing = $shipment->agentBilling->fillFromRequest($request->validated()['billing']);
            
            $billing->save();
            return $shipment;
        });

      return redirect()->route('admin.shipments.show', $shipment->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipment $shipment)
    {
        //
    }
}
