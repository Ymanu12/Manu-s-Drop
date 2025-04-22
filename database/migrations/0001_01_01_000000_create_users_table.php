<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 191)->unique();
            $table->string('mobile', 191)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('utype')->nullable()->default('USR')->comment('ADM for admin and USR for user or Customer');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 191)->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug', 191)->default('default-slug')->unique(); // Default slug
            $table->string('image')->nullable();
            $table->string('parent_id')->nullable();
            $table->timestamps();
        });

        Schema::create('sales', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('sale_code');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Correction
            $table->timestamps();
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug', 191)->default('default-slug')->unique();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug', 191)->unique();
            $table->string('short_description')->nullable();
            $table->text('description');
            $table->decimal('regular_price');
            $table->decimal('sale_price')->nullable();
            $table->string('SKU');
            $table->enum('stock_status', ['instock', 'outofstock']);
            $table->boolean('featured')->default(false);
            $table->unsignedInteger('quantity')->default(10);
            $table->string('image')->nullable(); // ✅ ligne corrigée
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->bigInteger('brand_id')->unsigned()->nullable();
            $table->text('images')->nullable();
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->decimal('regular_price', 10, 2)->change();
            $table->decimal('sale_price', 10, 2)->change();
        });

        Schema::create('bills', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->integer('qty');
            $table->string('sale_code');
            $table->integer('sale_price');
            $table->integer('total');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Correction de pro_id
            $table->timestamps();
        });

        Schema::create('paymethods', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->string('pay_name');
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->string('id', 191)->primary();
            $table->foreignId('paymethod_id')->constrained('paymethods')->onDelete('cascade');
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('products');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('bills');
        Schema::dropIfExists('paymethods');
        Schema::dropIfExists('payments');
    }
};