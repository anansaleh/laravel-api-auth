<?php

namespace App\Http\Controllers\API;

use App\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerRequest;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\CustomerCollection as CustomerCollection;
use App\Http\Resources\CustomerResource as CustomerResource;
use Response;
class CustomersController extends Controller
{

    public function all(Request $request)
    {
        return response()->json(Customer::get(), 200);
    }

    public function pageList(Request $request)
    {

        try{
            $limit = ($request->input('per_page'))?? 10;
            $sortRules = ($request->input('sort')) ?? 'name|asc';
            //dd($limit);
            list($field, $dir) = explode('|', $sortRules);

            $customers = Customer::orderBy($field, $dir)->paginate($limit);
            return new CustomerCollection($customers);
            //return response()->json($response, 200);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage()
            ], 404);
        }
    }

    public function show(int $customer_id)
    {
        try{
            $customer = Customer::findOrFail($customer_id);
            $response = new CustomerResource($customer);
            return response()->json($response, 200);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage()
            ], 404);
        }
    }

    public function new(CustomerRequest $request)
    {
        try{
            $customer = Customer::create(array_merge($request->only(["name", "email", "phone"]),
                    [
                        'created_by'=>Auth::user()->user_id
                        , 'updated_by'=>Auth::user()->user_id
                    ]
                ));
            //Auth::user()->user_id;
            return response()->json([
                'success' => true,
                "customer_id" => $customer->customer_id
            ], 201);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage()
            ], 404);
        }
    }
    public function edit(CustomerRequest $request, int $customer_id)
    {
        try{
            $customer = Customer::findOrFail($customer_id);
            $customer->fill(array_merge($request->all(),[
                'updated_by'=>Auth::user()->user_id
            ]));
            $customer->save();
            return response()->json([
                'success' => true
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage()
            ], 404);
        }
    }
}
