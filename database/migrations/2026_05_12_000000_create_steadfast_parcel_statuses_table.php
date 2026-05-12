<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('steadfast_parcel_statuses', function (Blueprint $table): void {
            $table->id();
            $table->string('notification_type');
            $table->string('consignment_id')->index();
            $table->string('invoice')->nullable()->index();
            $table->decimal('cod_amount', 12, 2)->nullable();
            $table->string('status')->nullable()->index();
            $table->decimal('delivery_charge', 12, 2)->nullable();
            $table->text('tracking_message')->nullable();
            $table->timestamp('provider_updated_at')->nullable()->index();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('steadfast_parcel_statuses');
    }
};
