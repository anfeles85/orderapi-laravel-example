<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    private $rules = [
        'legalization_date' => 'required|date|date_format:Y-m-d',
        'address' => 'required|string|max:50|min:3',
        'city' => 'required|string|max:50|min:3',
        'causal_id' => 'required|numeric',
        'observation_id' => 'numeric',
    ];

    private $traductionAttributes = [
        'legalization_date' => 'fecha de legalización',
        'address' => 'dirección',
        'city' => 'ciudad',
        'causal_id' => 'causal',
        'observation_id' => 'observación'
    ];

    public function applyValidator(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);
        $validator->setAttributeNames($this->traductionAttributes);
        $data = [];
        if ($validator->fails()) {
            $data = response()->json([                
                'errors' => $validator->errors(),
                'data' => $request->all()
            ],  Response::HTTP_BAD_REQUEST);
        }

        return $data;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();  
        $orders->load(['causal', 'observation']);
        return response()->json($orders, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->applyValidator($request);
        if (!empty($data)) {
            return $data;
        }

        $order = Order::create($request->all());
        $response = [
            'message' => 'Registro creado exitosamente',
            'order'  => $order
        ];

        return response()->json($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['causal', 'observation']);
        return response()->json($order, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $data = $this->applyValidator($request);
        if (!empty($data)) {
            return $data;
        }

        $order->update($request->all());
        $response = [
            'message' => 'Registro modificado exitosamente',
            'order'  => $order
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        $data = [
            'message' => 'Registro eliminado exitosamente',
            'order'  => $order->id
        ];

        return response()->json($data, Response::HTTP_OK);
    }
}
