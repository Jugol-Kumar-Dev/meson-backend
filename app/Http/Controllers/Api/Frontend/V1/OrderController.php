<?php

namespace App\Http\Controllers\Api\Frontend\V1;


use App\Http\Controllers\Controller;
use App\Http\Controllers\PayPalPaymentController;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Transaction;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $order = Order::query()
            ->where('course_id', $request->course_id)
            ->where('user_id', Auth::id())
            ->first();

        if($order) return response()->json('Already Exist This Course In Your Account...', 404);

        $data = $request->validate([
            'course_id' => ['required', Rule::exists('courses', 'id')],
            'district' => ['nullable'],
            'full_address' => ['nullable'],
            'post_office' => ['nullable'],
            'price' => ['nullable'],
            'thana' => ['nullable']
        ]);

        $rand_id = rand(73862, 5632625);


        $order = new Order;
        $order->course_id = $request->course_id;
        $order->user_id = $request->input('is_assinged') ? $request->input('student_id') : Auth::user()->id;


        $order->duration = $request->access_time;
        $order->duration_type = $request->access_type;

        $order->payment_method = $request->input('paymentMethod') ?? 'Others';
        $order->total_amount = $request->input('price');
        $order->coupon_discount = $request->input('discount_amount') ?? 0.00;
        $order->pay_amount = $request->input('price');
        $order->currency = 'BDT';
        $order->status = 'pending';
        $order->is_show = $request->filled('is_show');
        $order->save();

        if ($request->coupon_id != null){
            Auth::user()->coupons()->attach($this->createArrayGroups($request));
        }

        $orderDetails = OrderDetails::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'dob' => $request->input('dob'),
            'phone' => $request->input('phone'),
            'full_address' => $request->input('full_address'),
            'post' => $request->input('post_office'),
            'thana' => $request->input('thana'),
            'district' => $request->input('district')
        ]);


        if ($request->input('paymentMethod') == 'ssl') {
            $ssl = new SSLComesarzController();
            $response = $ssl->orderSSLPHP($order, $data, $orderDetails);
            return response()->json([
                'message' => 'order created, now payment by ssl',
                'url' => $response
            ]);
        }

        throw new ServerException('Have an Error, Please Try Again...!');

        // if has order access time...


    }
    private function createArrayGroups($request): array
    {
        $added = array();
        $user_id = Auth::id();
        $added[$user_id] = [
            "coupon_id" => $request->coupon_id,
            "using" => 1
        ];
        return $added;
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


    public function applyCoupon(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate(['coupon' => 'required']);
        $code = Coupon::query()->where('code', $request->coupon)->first();
        if ($code != null) {
            $users = $code->users->where('pivot.user_id', Auth::id());
            if ($users->count() < $code->limit) {
                if ($code->status) {
                    if ($code->validate >= today()->format("Y-m-d")) {
                        $msg = "Coupon Applied Successful....";
                        return response()->json([
                            'msg'  => $msg,
                            'coupon' => $code
                        ]);
                    } else {
                        return response()->json('Coupon Code Is Expired..!', 500);
                    }
                } else {
                    return response()->json('Coupon Code Is Inactive..!', 500);
                }
            } else {
                return response()->json('Coupon Code Use Limit Expired...!', 500);
            }
        } else {
            return response()->json('Coupon Code is Not Valid..!', 500);
        }
    }
}
