<?php

namespace App\Http\Controllers\Api\Frontend\V1;


use App\Http\Controllers\Controller;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class SSLComesarzController extends Controller
{

    public function orderSSLPHP($order, $data, $orderDetails)
    {
        $uniqueId = uniqid();
        /* PHP */
        $post_data = array();
        $post_data['total_amount'] = $order->total_amount;
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] =  $uniqueId;// tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = Auth::user()?->name;
        $post_data['cus_email'] = Auth::user()?->email;
        $post_data['cus_add1'] = $data['full_address'] ?? "dhaka";
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = Auth::user()?->phone;
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Dhaka";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "Dhaka";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";


        $sslc = new SslCommerzNotification();
        $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');
        $res = json_decode($payment_options);

        if ($res->status == 'fail'){
            return $res;
        }else{
            $payment = new Transaction;
            $payment->order_id = $order->id;
            $payment->course_id = $order->course_id;
            $payment->trx = $uniqueId;
            $payment->user_id = Auth::user()->id;
            $payment->amount = $order->total_amount;
            $payment->method = 'ssl';
            $payment->status = 'pending';
            $payment->save();


            return $res;
        }
    }

    public function success()
    {


        $tran_id = Request::input('tran_id');
        $amount = Request::input('amount');
        $currency = Request::input('currency');
        $sslc = new SslCommerzNotification();


        $validation = $sslc->orderValidate(Request::all(), $tran_id, $amount, $currency);
        if ($validation) {
            $trx = Transaction::query()->with('course')
                ->where('trx', Request::input('tran_id'))
                ->first();

            if($trx){
                $enroll_start = now();
                $enroll_end = now()->add($trx->course->access_type, $trx->course->access_time);
                $trx->update([
                    'status' => 'Success',
                    'payment_status' => 'paid'
                ]);
                $trx->order->update([
                    'status' => 'approved',
                    'enroll_start' => date("Y-m-d", strtotime($enroll_start)),
                    'enroll_expire' => date("Y-m-d", strtotime($enroll_end)),
                    'is_show' => true,
                ]);
                return redirect()->to(env('FRONTEND_URL')."/payment/success?trx_id=$trx->trx");
            }
        }

        return response([
            "status" => 500,
            'message' => "Order Complate, Payment Not Success...!"
        ], 500);
    }

    public function fail()
    {
        $trx = Transaction::query()->with('course:id')
            ->where('trx', Request::input('tran_id'))
            ->first();
        if($trx){
            $trx->delete();
            $trx->order->delete();
            $trx->order->orderDetails->delete();
        }
        return redirect()->to(env('FRONTEND_URL')."/payment/failed");
    }

    public function cancel(Request $request)
    {

        return[
            "message"=> "canclled route called",
            'data'=> \request()->all()
        ];
        $tran_id = $request->input('tran_id');
        $order_details = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'grand_total')->first();

        if ($order_details->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Canceled']);
            return \redirect()->route('orderComplete');
        } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
            return \redirect()->route('orderComplete');
        } else {
            return \redirect()->route('orderComplete');
        }
    }

}

