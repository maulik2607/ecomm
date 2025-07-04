<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Users and Authentication
class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->unique();
            $table->string('otp')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('users'); }
}

class CreatePasswordResetsTable extends Migration
{
    public function up()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }
    public function down() { Schema::dropIfExists('password_resets'); }
}

// Roles & Permissions (RBAC)
class CreateRolesTable extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('guard_name')->default('web');
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('roles'); }
}

class CreatePermissionsTable extends Migration
{
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('guard_name')->default('web');
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('permissions'); }
}

class CreateRoleUserPivotTable extends Migration
{
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->primary(['role_id','user_id']);
        });
    }
    public function down() { Schema::dropIfExists('role_user'); }
}

class CreatePermissionRolePivotTable extends Migration
{
    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->primary(['permission_id','role_id']);
        });
    }
    public function down() { Schema::dropIfExists('permission_role'); }
}

// Products & Catalog
class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('categories'); }
}

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->foreignId('category_id')->constrained()->onDelete('set null');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('cost', 12, 2)->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('status')->default('draft'); // draft, published, archived
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('products'); }
}

class CreateProductImagesTable extends Migration
{
    public function up()
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('url');
            $table->boolean('is_primary')->default(false);
            $table->integer('position')->default(0);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('product_images'); }
}

class CreateAttributesTable extends Migration
{
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('select'); // select, text, checkbox etc.
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('attributes'); }
}

class CreateAttributeOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('attribute_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained()->onDelete('cascade');
            $table->string('value');
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('attribute_options'); }
}

class CreateProductAttributePivotTable extends Migration
{
    public function up()
    {
        Schema::create('attribute_product', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('attribute_id')->constrained()->onDelete('cascade');
            $table->primary(['product_id','attribute_id']);
        });
    }
    public function down() { Schema::dropIfExists('attribute_product'); }
}

class CreateProductVariantTable extends Migration
{
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('sku')->unique();
            $table->decimal('price', 12, 2);
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('product_variants'); }
}

class CreateProductVariantOptionPivotTable extends Migration
{
    public function up()
    {
        Schema::create('attribute_option_variant', function (Blueprint $table) {
            $table->foreignId('variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->foreignId('attribute_option_id')->constrained()->onDelete('cascade');
            $table->primary(['variant_id','attribute_option_id']);
        });
    }
    public function down() { Schema::dropIfExists('attribute_option_variant'); }
}

// Carts & Wishlists
class CreateCartsTable extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('carts'); }
}

class CreateCartItemsTable extends Migration
{
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('cart_items'); }
}

class CreateWishlistsTable extends Migration
{
    public function up()
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name')->default('default');
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('wishlists'); }
}

class CreateWishlistItemsTable extends Migration
{
    public function up()
    {
        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wishlist_id')->constrained()->onDelete('cascade');
            $table->foreignId('variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('wishlist_items'); }
}

// Orders & Transactions
class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax', 12, 2);
            $table->decimal('shipping_cost', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->string('status')->default('pending'); // pending, processing, completed, cancelled
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('orders'); }
}

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 12, 2);
            $table->decimal('total', 12, 2);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('order_items'); }
}

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('payment_method');
            $table->string('transaction_id')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('pending'); // pending, succeeded, failed
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('payments'); }
}

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // sale, refund, hold
            $table->decimal('amount', 12, 2);
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->string('details')->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('transactions'); }
}

class CreateReturnsTable extends Migration
{
    public function up()
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained('order_items')->onDelete('cascade');
            $table->integer('quantity');
            $table->string('reason')->nullable();
            $table->string('status')->default('requested'); // requested, approved, rejected, refunded
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('returns'); }
}

// Shipping & Tax
class CreateShippingMethodsTable extends Migration
{
    public function up()
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('cost', 12, 2);
            $table->json('regions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('shipping_methods'); }
}

class CreateTaxRatesTable extends Migration
{
    public function up()
    {
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('rate', 5, 2);
            $table->json('regions')->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('tax_rates'); }
}

// Addresses
class CreateAddressesTable extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('label')->nullable();
            $table->string('line1');
            $table->string('line2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->string('country');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('addresses'); }
}

// Promotions & Coupons
class CreateCouponsTable extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('discount_amount', 12, 2)->nullable();
            $table->decimal('discount_percent', 5, 2)->nullable();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('coupons'); }
}

class CreateCouponUserPivotTable extends Migration
{
    public function up()
    {
        Schema::create('coupon_user', function (Blueprint $table) {
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->primary(['coupon_id','user_id']);
            $table->timestamp('redeemed_at');
        });
    }
    public function down() { Schema::dropIfExists('coupon_user'); }
}

// Reviews & Ratings
class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating');
            $table->text('comment')->nullable();
            $table->boolean('approved')->default(false);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('reviews'); }
}

// Notifications
class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->morphs('notifiable');
            $table->string('type');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('notifications'); }
}

// Inventory & Suppliers
class CreateSuppliersTable extends Migration
{
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('suppliers'); }
}

class CreateInventoryTable extends Migration
{
    public function up()
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('quantity')->default(0);
            $table->decimal('cost_price', 12, 2)->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('inventory'); }
}

// SEO & Metadata
class CreateSeoMetadataTable extends Migration
{
    public function up()
    {
        Schema::create('seo_metadata', function (Blueprint $table) {
            $table->id();
            $table->string('metable_type');
            $table->unsignedBigInteger('metable_id');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->json('keywords')->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('seo_metadata'); }
}
