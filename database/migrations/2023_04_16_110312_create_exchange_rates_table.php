<?php

use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(ExchangeRate::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_currency_id')
                ->references('id')
                ->on(Currency::getTableName())
                ->onDelete('cascade');
            $table->foreignId('to_currency_id')
                ->references('id')
                ->on(Currency::getTableName())
                ->onDelete('cascade');
            $table->decimal('rate', 10, 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(ExchangeRate::getTableName());
    }
};
