<?php

namespace App\Http\Controllers;

use App\Enums\BillingStatus;
use App\Enums\ContactType;
use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Service;
use App\Http\Requests\ShipmentRequest;
use App\Models\Billing;
use App\Models\Contact;
use App\Models\ShipmentUpdate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
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
        $filters = [];
        $query = Shipment::query()
                            ->with('agent', 'shipper', 'receiver', 'service')
                            ->latest('awb');

        
         if ($request->filled('q')) {
            try {
                $filters = json_decode(Crypt::decryptString($request->q), true);
            } catch (\Exception $e) {
                return redirect()
                            ->route('admin.shipments.index')
                            ->with('error', 'Invalid filter data. Please try again.');
            }
        }

        //date range filter
        if (!empty($filters['date_range'])) {
            [$start, $end] = array_map('trim', explode('to', $filters['date_range']));
            
            $startDate = Carbon::createFromFormat('m-d-Y', $start)->startOfDay();
            $endDate   = Carbon::createFromFormat('m-d-Y', $end)->endOfDay();

            $query->whereBetween('received_at', [$startDate, $endDate]);
        }                          



        //filter by agent
        if(!empty($filters['agent']) && auth()->user()->can('admin'))
        {
            if($filters['agent'] != 'all'){
                $query->where('agent_id', $filters['agent']);    
            }
            
        }


        //filter by service
        if(!empty($filters['service']) && $filters['service'] != 'all')
        {
            $query->where('service_id', $filters['service']);
        }



        $shipments = $query->paginate(2)->withQueryString();
        $agents = auth()->user()->can('admin') ? Agent::all() : [];
        $services = Service::all();


        return view('admin.shipments.index', compact(['shipments', 'agents', 'services']));
    }



    /**
     * Shipment Filtering
     */

    public function filterShipments(Request $request)
    {
        $validated = $request->validate([
            'date_range' => ['nullable', function($attribute, $value, $fail){
                // Split by separator
                [$start, $end] = array_map('trim', explode('to', $value));
                // Parse using Carbon
                try {
                    $startDate = \Carbon\Carbon::createFromFormat('m-d-Y', $start);
                    $endDate   = \Carbon\Carbon::createFromFormat('m-d-Y', $end);
                } catch (\Exception $e) {
                    return $fail("The $attribute format is invalid.");
                }

                // Ensure order
                if ($startDate->gt($endDate)) {
                    return $fail("The start date must be before or equal to the end date.");
                }
            }],
            'service' => ['nullable'],
            'agent' => ['nullable'],
        ]);
        $encrypted = Crypt::encryptString(json_encode($validated));
        

        return redirect()->route('admin.shipments.index', ['q' => $encrypted]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        
        $agents = Agent::all();
        $services = Service::all();
        $shipment = (empty($request->get('clone'))) 
                                                ? new Shipment() 
                                                : Shipment::with(['shipper', 'receiver'])->find($request->get('clone'));
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
                  
            $shipment->saveQuietly();
            $shipment->setAwB()->save();

            //create billing
            $billing = (new Billing())->fillFromRequest($request->validated()['billing']);
            $shipment->billing()->save($billing);
            
            //create tracking update
            $shipment->addUpdate([
                'location'  => 'DHAKA-BD',
                'activity'  => 'Shipment booked',
                'datetime'  => now()
            ]);

            return $shipment;
        });

      return redirect()->route('admin.shipments.show', $shipment->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipment $shipment)
    {
        $shipment->load('billing');
        
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
        $shipment->load('billing', 'agent', 'service');
        
        return view('admin.shipments.edit')->with([
            'agents'    => $agents,
            'services'  => $services,
            'shipment'  => $shipment,
            'countries' => $countries
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShipmentRequest $request, Shipment $shipment)
    {
        $shipment->load('shipper', 'receiver', 'billing');
        
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
            $billing = $shipment->billing->fillFromRequest($request->validated()['billing']);
            
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
