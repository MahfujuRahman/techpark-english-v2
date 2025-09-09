<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/app/Modules/Management/CourseManagement/CourseBatch/Database/create_course_batches_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_batches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('course_id')->nullable();
            $table->string('batch_name', 100)->nullable();
            $table->date('admission_start_date')->nullable();
            $table->date('admission_end_date')->nullable();
            $table->bigInteger('batch_student_limit')->nullable();
            $table->integer('seat_booked')->nullable();
            $table->float('booked_percent')->nullable();
            $table->float('course_price')->nullable();
            $table->float('course_discount')->nullable();
            $table->float('after_discount_price')->nullable();
            $table->date('first_class_date')->nullable();
            $table->string('class_days', 255)->nullable();
            $table->time('class_start_time')->nullable();
            $table->time('class_end_time')->nullable();
            $table->enum('show_percentage_on_cards', ['yes','no'])->nullable();

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
        Schema::dropIfExists('course_batches');
    }
};