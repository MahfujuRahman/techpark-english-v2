<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/app/Modules/Management/CourseManagement/CourseModuleClass/Database/create_course_module_task_complete_by_users_table.php' 
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_module_task_complete_by_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('course_id')->nullable();
            $table->bigInteger('milestone_id')->nullable();
            $table->bigInteger('module_id')->nullable();
            $table->bigInteger('class_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('content_id')->nullable();
            $table->bigInteger('quiz_id')->nullable();
            $table->bigInteger('exam_id')->nullable();

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
        Schema::dropIfExists('course_module_task_complete_by_users');
    }
};