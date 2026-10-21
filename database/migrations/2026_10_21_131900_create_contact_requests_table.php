<?php

declare(strict_types=1);

use App\Support\CmsSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->char('locale', 2);
            $table->string('status', 32)->default('new');
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });

        CmsSchema::checkLocale('contact_requests');
        CmsSchema::checkIn('contact_requests', 'status', [
            'new',
            'in_progress',
            'responded',
            'closed',
            'spam',
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_requests');
    }
};
