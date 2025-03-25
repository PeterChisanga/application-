<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('status', 10); // P, A, SUN, SP, FL, SL
            $table->float('overtime_hours')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'date']); // Ensure one record per employee per day
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_records');
    }
}