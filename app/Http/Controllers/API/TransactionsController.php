<?php

namespace App\Http\Controllers\API;

use App\Transaction;
use App\TransactionsDailySum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TransactionRequest;

use App\Http\Resources\TransactionCollection as TransactionCollection;
use App\Http\Resources\TransactionResource as TransactionResource;
class TransactionsController extends Controller
{
    /**
     * @param Request $request
     * @param $transactions Transaction collections
     * @return \Illuminate\Http\JsonResponse
     */
    private function listFilters(Request $request, $transactions){
        // filter
        if($request->has('filter_transaction_id')){
            $transactions = $transactions->where('transaction_id', $request->input('filter_transaction_id'));
        }elseif ($request->has('filter_customer_id')){
            $transactions = $transactions->where('customer_id', $request->input('filter_customer_id'));
            if ($request->has('filter_amount')){
                $transactions = $transactions->where('amount',$request->input('filter_amount_operation'),$request->input('filter_amount') );
            }
            if ($request->has('filter_date_from')){
                $transactions = $transactions->where('created_at','>=',$request->input('filter_date_from') );
            }
            if ($request->has('filter_date_to')){
                $transactions = $transactions->where('created_at','<=',$request->input('filter_date_to') );
            }
        }else{
            if ($request->has('filter_customer_name')){
                $cname= $request->input('filter_customer_name');
                $transactions = $transactions->whereHas('customer', function ($query) use ($cname) {
                    $query->where('name', 'like', "%".$cname."%");
                });
            }
            if ($request->has('filter_amount')){
                $transactions = $transactions->where('amount',$request->input('filter_amount_operation'),$request->input('filter_amount') );
            }
            if ($request->has('filter_date_from')){
                $transactions = $transactions->where('created_at','>=',$request->input('filter_date_from') );
            }
            if ($request->has('filter_date_to')){
                $transactions = $transactions->where('created_at','<=',$request->input('filter_date_to') );
            }
        }
        $limit = $request->input('per_page');
        $transactions = $transactions->paginate($limit);

        return new TransactionCollection($transactions);
//        return response()->json(
//            $transactions
//            , 200);
    }


    /**
     * getList
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request){

        if ($request->has('sort')) {
            $sortRules = $request->input('sort');
            list($field, $dir) = explode('|', $sortRules);
            if ($field =='customer_name'){
                $transactions= Transaction::with('customer')
                    ->select('transactions.*', DB::raw('(SELECT name FROM customers WHERE transactions.customer_id = customers.customer_id ) as customer_name'))
                    ->orderBy('customer_name', $dir);
            }else{
                $transactions= Transaction::orderBy($field, $dir);
            }

        }else{
            $transactions= Transaction::orderBy('created_at', 'desc');
        }
        return $this->listFilters($request, $transactions);
        //return $transactions->paginate();
    }

    /**
     * getListByCustomer
     *
     * @param Request $request
     * @param $customer_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListByCustomer(Request $request, int $customer_id){
        $transactions = Transaction::where('customer_id', $customer_id);
        if ($request->has('sort')) {
            $sortRules = $request->input('sort');
            list($field, $dir) = explode('|', $sortRules);
            if ($field =='customer_name'){
                $transactions= $transactions->with('customer')
                    ->select('transactions.*', DB::raw('(SELECT name FROM customers WHERE transactions.customer_id = customers.customer_id ) as customer_name'))
                    ->orderBy('customer_name', $dir);
            }else{
                $transactions= $transactions->orderBy($field, $dir);
            }

        }else{
            $transactions= $transactions->orderBy('created_at', 'desc');
        }
        return $this->listFilters($request, $transactions);
    }

    public function getDailySum(Request $request){
        $limit = 10;

        if ($request->has('sort')) {
            $sortRules = $request->input('sort');
            list($field, $dir) = explode('|', $sortRules);
        }else{
            $field='date_from';
            $dir='desc';
        }

        if ($request->has('per_page')) {
            $limit = $request->input('per_page');
        }
        $trans_sum =TransactionsDailySum::orderBy($field, $dir)
                    ->paginate($limit);

        return response()->json(
            $trans_sum
            , 200);

    }

    /**
     * getTransaction
     *
     * @param Request $request
     * @param $transaction_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransaction(int $transaction_id)
    {
        try{
            $transaction = Transaction::find($transaction_id);
            return new TransactionResource($transaction);
            //return response()->json($transaction, 200)
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage()
            ], 404);
        }
    }

    /**
     * getTransactionByCustomer
     *
     * @param Request $request
     * @param $customer_id
     * @param $transaction_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactionByCustomer(Request $request, int $customer_id, int $transaction_id)
    {
        try{
            $transaction = Transaction::where('transaction_id', $transaction_id)
                ->where('customer_id', $customer_id)
                ->first();
            return new TransactionResource($transaction);
            //return response()->json($transaction, 200);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage()
            ], 404);
        }
    }

    public function addCustomerTransaction(TransactionRequest $request, int $customer_id){
        //return $request->;
        try{
            //$amount= $request->input('amount');
            $transaction = new Transaction();
            $transaction->customer_id= $customer_id;
            $transaction->amount= $request->input('amount');
            $transaction->created_by= Auth::user()->user_id;
            $transaction->updated_by= Auth::user()->user_id;
            $transaction->save();
            $response= new TransactionResource($transaction);
            return response()->json([
                "success" => true,
                "data"=>$response
            ], 201);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage()
            ], 404);
        }
//        return response()->json($request->all(), 200);
    }
    public function updateCustomerTransaction(TransactionRequest $request, int $customer_id){
        try{
            $transaction = Transaction::where('transaction_id', $request->input('transaction_id'))
                ->where('customer_id', $customer_id)
                ->first();
            $transaction->amount= $request->input('amount');
            $transaction->updated_by= Auth::user()->user_id;
            $transaction->save();

            $response= new TransactionResource($transaction);
            return response()->json([
                "success" => true,
                "data"=>$response
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage()
            ], 404);
        }
    }


//    public function newTransaction(Request $request){
//        return response()->json($request->all(), 200);
//    }
//    public function editTransaction(Request $request){
//        return response()->json($request->all(), 200);
//    }

    public function deleteTransaction(Request $request, int $customer_id){
        try{
            Transaction::where('transaction_id', $request->input('transaction_id'))
                ->where('customer_id', $customer_id)
                ->delete();
            return response()->json([
                "success" => true
            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' =>  $e->getMessage()
            ], 404);
        }
    }
}
