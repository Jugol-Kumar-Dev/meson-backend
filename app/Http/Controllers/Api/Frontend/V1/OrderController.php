<?php

namespace App\Http\Controllers\Api\Frontend\V1;


use App\Http\Controllers\Controller;
use App\Http\Controllers\PayPalPaymentController;
use App\Models\Order;
use App\Models\Transaction;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function store(Request $request)
    {

        $data = $request->validate([
            'course_id' => ['required', Rule::exists('courses', 'id')],
            'district' => ['required'],
            'full_address' => ['required'],
            'post_office' => ['required'],
            'price' => ['required'],
            'thana' => ['required']
        ]);

        $rand_id = rand(73862, 5632625);

        $order = new Order;
        $order->course_id = $request->course_id;
        $order->user_id = $request->input('is_assinged') ? $request->input('student_id') : Auth::user()->id;
//        $order->transaction_id = $rand_id;
        $order->payment_method = $request->input('paymentMethod') ?? 'Others';
        $order->total_amount = $request->input('price');
        $order->coupon_discount = $request->input('discount_amount') ??  0.00 ;
        $order->pay_amount = $request->input('price');
        $order->currency = 'BDT';
        $order->status = 'pending';
        $order->is_show = $request->filled('is_show');
        $order->save();


        if($request->input('paymentMethod') == 'ssl'){
            $ssl = new SSLComesarzController();
            $response = $ssl->orderSSLPHP($order, $data);
            return response()->json([
                'message' => 'order created, now payment by ssl',
                'url' => $response
            ]);
        }

        throw new ServerException('Have an Error, Please Try Again...!' );

        // if has order access time...
//        $order->duration = $request->access_time;
//        $order->duration_type = $request->access_type;
//
//        $order->enroll_start = date(Properties::$enrollDateFormat, strtotime($request->enroll_start));
//        $order->enroll_expire = date(Properties::$enrollDateFormat, strtotime($request->enroll_end));



    }

    public function getConfirmOrder($id): \Illuminate\Http\JsonResponse
    {
        $tranx = Transaction::query()
            ->with('order')
            ->where('trx', $id)
            ->first();

        return response()->json([
            'trx' => $tranx
        ]);
    }

}
