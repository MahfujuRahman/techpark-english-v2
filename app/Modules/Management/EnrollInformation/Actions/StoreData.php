<?php

namespace App\Modules\Management\EnrollInformation\Actions;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class StoreData
{
    static $model = \App\Modules\Management\EnrollInformation\Models\Model::class;

    public static function execute($request)
    {
        try {
            DB::beginTransaction();

            $requestData = $request->validated();

            $course_id = $request->course_id;
            $batch_id = $request->batch_id;
            $student_id = $request->student_id;

            $isEnrolled = DB::table('course_batch_students')
                ->where('course_id', $course_id)
                ->where('batch_id', $batch_id)
                ->where('student_id', $student_id)
                ->first();

            if ($isEnrolled) {
                return messageResponse('Student already enrolled in this batch', [], 404, 'error');
            }

            $payment_type = $request->payment_type;
            $payment_by = $request->payment_by ?? null;
            $receipt_id = $request->receipt_id ?? null;
            $trx_id = $request->trx_id ?? null;
            $payment_status = $request->payment_status;
            $total_amount = $request->total_amount ?? null;
            $paid_amount = $request->paid_amount ?? null;

            $subtotal = $paid_amount;
            $total = $total_amount;
            $discount = round(($total - $subtotal) / $total * 100);

            // Insert into orders
            $orderId = DB::table('orders')->insertGetId([
                'order_no'       => time() . rand(100, 999),
                'user_id'        => auth()->id(),
                'order_date'     => now(),
                'payment_method' => 2, // 2 from super-admin
                'payment_status' => 1, // paid
                'trx_id'         => $trx_id,
                'sub_total'      => $subtotal,
                'discount'       => $discount,
                'total'          => $total,
                'slug'           => Str::slug('Tp' . '-' . time() . '-' . Str::random(6))
            ]);

            // Insert into order_details
            DB::table('order_details')->insert([
                'order_id'    => $orderId,
                'product_id'  => $batch_id,
                'qty'         => 1,
                'unit_price'  => $total,
                'total_price' => $total,
            ]);

            // Insert into order_payments
            DB::table('order_payments')->insert([
                'order_id'           => $orderId,
                'payment_through'    => "sslcommerz",
                'tran_id'            => $trx_id,
                'bank_tran_id'       => null,
                'val_id'             => null,
                'amount'             => $total,
                'card_type'          => 'super-admin',
                'store_amount'       => null,
                'card_no'            => null,
                'status'             => 'VALID',
                'tran_date'          => Carbon::now(),
                'currency'           => null,
                'card_issuer'        => null,
                'card_brand'         => null,
                'card_sub_brand'     => null,
                'card_issuer_country' => null,
                'created_at'         => Carbon::now()
            ]);

            // Insert into enroll_informations
            $data = self::$model::query()->create($requestData);

            // Insert into course_batch_students
            DB::table('course_batch_students')->insert([
                'course_id'   => $course_id,
                'batch_id'    => $batch_id,
                'student_id'  => $student_id,
                'is_complete' => 'incomplete',
                'course_percent' => 0,
                'slug'        => Str::slug('Tp' . '-' . time() . '-' . Str::random(6))
            ]);

            // ✅ If everything runs fine
            DB::commit();

            return messageResponse('Item added successfully', $data, 201);
        } catch (\Exception $e) {
            // ❌ If something fails, rollback
            DB::rollBack();
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
