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
        Schema::table('request_files', function (Blueprint $table) {
            $table->string('filename')->nullable()->after('url');
            $table->string('path')->nullable()->after('filename');
            $table->string('mime_type')->nullable()->after('path');
            $table->bigInteger('size')->nullable()->after('mime_type');
        });

        Schema::table('response_files', function (Blueprint $table) {
            $table->string('filename')->nullable()->after('url');
            $table->string('path')->nullable()->after('filename');
            $table->string('mime_type')->nullable()->after('path');
            $table->bigInteger('size')->nullable()->after('mime_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_files', function (Blueprint $table) {
            $table->dropColumn(['filename', 'path', 'mime_type', 'size']);
        });

        Schema::table('response_files', function (Blueprint $table) {
            $table->dropColumn(['filename', 'path', 'mime_type', 'size']);
        });
    }
};