<?php

namespace App\Http\Controllers\Payment;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Controllers\CheckoutController;
use App\Http\Gateways\SSLCommerz\SSLCommerz;
use App\Modules\Management\CourseManagement\Course\Models\Model as Course;
use App\Modules\Management\CourseManagement\CourseBatch\Models\Model as CourseBatches;

class PaymentController extends Controller
{
    public function order()
    {
        $sslc = new SSLCommerz();
        $sslc->amount(request()->input('total'))
            ->trxid(time() . Str::random(5))
            ->product('Products From TechPark English')
            ->customer(request()->input('customer_name'), request()->input('customer_email'));

        return $sslc->make_payment();
    }


    public function success(Request $request)
    {
        $trx_id = $request->tran_id;
        $course_slug = $request->value_a;
        $batch_id = $request->value_b;

        // Get course and batch details
        $course = Course::active()->where('slug', $course_slug)->select('id', 'slug', 'title')->first();
        $batch = CourseBatches::active()->where('id', $batch_id)->first();

        $subtotal = $batch->course_price ?? 0;
        $discount = $batch->course_discount ?? 0;
        $total    = round($batch->after_discount_price ?? 0);

        $validate = SSLCommerz::validate_payment($request);

        if ($validate) {
            try {
                DB::beginTransaction();

                $bankID = $request->bank_tran_id;

                // Insert into orders
                $orderId = DB::table('orders')->insertGetId([
                    'order_no'       => time() . rand(100, 999),
                    'user_id'        => auth()->id(),
                    'order_date'     => now(),
                    'payment_method' => 1, // sslcommerz
                    'payment_status' => 1, // paid
                    'trx_id'         => $trx_id,
                    'sub_total'      => $subtotal,
                    'discount'       => $discount,
                    'total'          => $total,
                    'slug'           => Str::slug($course->title . '-' . time() . '-' . Str::random(6))
                ]);

                // Insert into order_details
                DB::table('order_details')->insert([
                    'order_id'    => $orderId,
                    'product_id'  => $batch->id,
                    'qty'         => 1,
                    'unit_price'  => $total,
                    'total_price' => $total,
                ]);

                // Insert into order_payments
                DB::table('order_payments')->insert([
                    'order_id'           => $orderId,
                    'payment_through'    => "sslcommerz",
                    'tran_id'            => $request->tran_id,
                    'bank_tran_id'       => $bankID,
                    'val_id'             => $request->val_id,
                    'amount'             => $request->amount,
                    'card_type'          => $request->card_type,
                    'store_amount'       => $request->store_amount,
                    'card_no'            => $request->card_no,
                    'status'             => $request->status,
                    'tran_date'          => $request->tran_date,
                    'currency'           => $request->currency,
                    'card_issuer'        => $request->card_issuer,
                    'card_brand'         => $request->card_brand,
                    'card_sub_brand'     => $request->card_sub_brand,
                    'card_issuer_country' => $request->card_issuer_country,
                    'created_at'         => Carbon::now()
                ]);

                // Insert into enroll_informations
                DB::table('enroll_informations')->insert([
                    'course_id'     => $course->id,
                    'student_id'    => auth()->id(),
                    'batch_id'      => $batch->id,
                    'trx_id'        => $trx_id,
                    'payment_type'  => 'online',
                    'payment_by'    => auth()->id(),
                    'payment_status' => 'paid',
                    'total_amount'  => $total,
                    'paid_amount'   => $request->amount,
                    'slug'          => Str::slug($course->title . '-' . time() . '-' . Str::random(6))
                ]);

                // Insert into course_batch_students
                DB::table('course_batch_students')->insert([
                    'course_id'   => $course->id,
                    'batch_id'    => $batch->id,
                    'student_id'  => auth()->id(),
                    'is_complete' => 'incomplete',
                    'slug'        => Str::slug($course->title . '-' . time() . '-' . Str::random(6))
                ]);

                // ✅ If everything runs fine
                DB::commit();

                return redirect('my-course/' . $course_slug)->with('success', 'You are enrolled!');
            } catch (\Exception $e) {
                // ❌ If something fails, rollback
                DB::rollBack();
                return redirect()->back()->with('error', 'Payment succeeded but order creation failed: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Payment validation failed.');
    }

    public function failure(Request $request)
    {
        $auth = auth()->user();
        $course_slug = $request->value_a;

        if (!$auth) {
            $this->cancel($request);
            return redirect('/login');
        }

        return redirect('course/enroll/' . $course_slug)->with('error', 'Payment failed! Please try again.');
    }

    public function cancel(Request $request)
    {
        $course_slug = $request->value_a;

        return redirect('course/enroll/' . $course_slug)->with('error', 'Order Canceled! Please try again.');
    }

    public function refund($bankID)
    {
        /**
         * SSLCommerz::refund($bank_trans_id, $amount [,$reason])
         */

        $refund = SSLCommerz::refund($bankID, 1500); // 1500 => refund amount

        if ($refund->status) {
            /**
             * States:
             * success : Refund request is initiated successfully
             * failed : Refund request is failed to initiate
             * processing : The refund has been initiated already
             */

            $state  = $refund->refund_state;

            /**
             * RefID will be used for post-refund status checking
             */

            $refID  = $refund->ref_id;

            /**
             *  To get all the outputs
             */

            dd($refund->output);
        } else {
            return $refund->message;
        }
    }

    public function check_refund_status($refID)
    {
        $refund = SSLCommerz::query_refund($refID);

        if ($refund->status) {
            /**
             * States:
             * refunded : Refund request has been proceeded successfully
             * processing : Refund request is under processing
             * cancelled : Refund request has been proceeded successfully
             */

            $state  = $refund->refund_state;

            /**
             * RefID will be used for post-refund status checking
             */

            $refID  = $refund->ref_id;

            /**
             *  To get all the outputs
             */

            dd($refund->output);
        } else {
            return $refund->message;
        }
    }

    public function get_transaction_status($trxID)
    {
        $query = SSLCommerz::query_transaction($trxID);

        if ($query->status) {
            dd($query->output);
        } else {
            $query->message;
        }
    }

    // public function ipn(Request $request)
    // {
    //     #Received all the payement information from the gateway
    //     if ($request->input('tran_id')) #Check transation id is posted or not.
    //     {

    //         $tran_id = $request->input('tran_id');

    //         #Check order status in order tabel against the transaction id or order id.
    //         $order_details = DB::table('orders')
    //             ->where('transaction_id', $tran_id)
    //             ->select('transaction_id', 'status', 'currency', 'amount')->first();

    //         if ($order_details->status == 'Pending') {
    //             $sslc = new SslCommerzNotification();
    //             $validation = $sslc->orderValidate($request->all(), $tran_id, $order_details->amount, $order_details->currency);
    //             if ($validation == TRUE) {
    //                 /*
    //                 That means IPN worked. Here you need to update order status
    //                 in order table as Processing or Complete.
    //                 Here you can also sent sms or email for successful transaction to customer
    //                 */
    //                 $update_product = DB::table('orders')
    //                     ->where('transaction_id', $tran_id)
    //                     ->update(['status' => 'Processing']);

    //                 echo "Transaction is successfully Completed";
    //             }
    //         } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {

    //             #That means Order status already updated. No need to udate database.

    //             echo "Transaction is already successfully Completed";
    //         } else {
    //             #That means something wrong happened. You can redirect customer to your product page.

    //             echo "Invalid Transaction";
    //         }
    //     } else {
    //         echo "Invalid Data";
    //     }
    // }
}
