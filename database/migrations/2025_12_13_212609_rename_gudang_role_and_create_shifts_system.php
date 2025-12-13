<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Create shifts table
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Shift Pagi", "Shift Sore", "Shift Malam"
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Step 2: Create shift_members pivot table
        Schema::create('shift_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained('shifts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Unique: user can only be in one shift at a time
            $table->unique('user_id');
        });

        // Step 3: Update working_hours to reference shift instead of just role
        Schema::table('working_hours', function (Blueprint $table) {
            // Add shift_id (nullable for backward compatibility)
            $table->foreignId('shift_id')->nullable()->after('id')->constrained('shifts')->onDelete('cascade');
            
            // Make role nullable (when shift is set, role is not needed)
            $table->enum('role', ['admin', 'gudang', 'pemilik', 'staff_operasional'])->nullable()->change();
        });

        // Step 4: Rename role 'gudang' to 'staff_operasional' in all tables
        
        // Update users table
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'gudang', 'pemilik', 'staff_operasional') NOT NULL");
        DB::table('users')->where('role', 'gudang')->update(['role' => 'staff_operasional']);
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff_operasional', 'pemilik') NOT NULL");
        
        // Update working_hours table
        DB::table('working_hours')->where('role', 'gudang')->update(['role' => 'staff_operasional']);
        DB::statement("ALTER TABLE working_hours MODIFY COLUMN role ENUM('admin', 'staff_operasional', 'pemilik') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the role rename first
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'gudang', 'pemilik', 'staff_operasional') NOT NULL");
        DB::table('users')->where('role', 'staff_operasional')->update(['role' => 'gudang']);
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'gudang', 'pemilik') NOT NULL");
        
        DB::table('working_hours')->where('role', 'staff_operasional')->update(['role' => 'gudang']);
        DB::statement("ALTER TABLE working_hours MODIFY COLUMN role ENUM('admin', 'gudang', 'pemilik') NULL");
        
        // Drop shift columns
        Schema::table('working_hours', function (Blueprint $table) {
            $table->dropForeign(['shift_id']);
            $table->dropColumn('shift_id');
        });
        
        // Drop shift tables
        Schema::dropIfExists('shift_members');
        Schema::dropIfExists('shifts');
    }
};
