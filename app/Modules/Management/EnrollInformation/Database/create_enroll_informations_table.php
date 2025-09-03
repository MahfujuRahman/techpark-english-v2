<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/app/Modules/Management/EnrollInformation/Database/create_enroll_informations_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enroll_informations', function (Blueprint $table) {
            $table->id();
            $table->string('course_id', 100)->nullable();
            $table->string('student_id', 100)->nullable();
            $table->string('batch_id', 100)->nullable();
            $table->enum('payment_type', ['offline','online'])->nullable();
            $table->string('payment_by', 50)->nullable();
            $table->string('receipt_id', 255)->nullable();
            $table->string('trx_id', 255)->nullable();
            $table->enum('payment_status', ['paid','unpaid','failed'])->nullable();
            $table->float('total_amount')->nullable();
            $table->float('paid_amount')->nullable();

            $table->bigInteger('creator')->unsigned()->nullable();
            $table->string('slug', 50)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enroll_informations');
    }
};