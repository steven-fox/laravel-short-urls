<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use StevenFox\Larashurl\Concerns\UsesLarashurlModels;

return new class extends Migration
{
    use UsesLarashurlModels;

    public function up(): void
    {
        $shortUrlModel = self::newShortUrlModel();
        $shortUrlVisitsModel = self::newShortUrlVisitModel();
        $shortUrlCampaignModel = self::newShortUrlCampaignModel();

        Schema::connection($shortUrlCampaignModel->getConnectionName())
            ->create($shortUrlCampaignModel->getTable(), function (Blueprint $table) {
                $table->id();

                $table->string('name')->index();
                $table->string('description')->nullable();
                $table->datetime('active_at')->nullable()->index();
                $table->datetime('expires_at')->nullable()->index();
                $table->json('options');

                $table->timestamps();
            });

        Schema::connection($shortUrlModel->getConnectionName())
            ->create($shortUrlModel->getTable(), function (Blueprint $table) use ($shortUrlModel, $shortUrlCampaignModel) {
                $table->id();

                $shortUrlCampaignId = $table->foreignId('short_url_campaign_id')->nullable();

                if ($shortUrlModel->getConnectionName() === $shortUrlCampaignModel->getConnectionName()) {
                    $shortUrlCampaignId->constrained($shortUrlCampaignModel->getTable())->nullOnDelete();
                }

                $table->string('response_type', 20);
                $table->text('destination_url')->index();
                $table->string('url_key', 50)->unique();
                $table->datetime('active_at')->nullable()->index();
                $table->datetime('expires_at')->nullable()->index();
                $table->json('options');

                $table->timestamps();
            });

        Schema::connection($shortUrlVisitsModel->getConnectionName())
            ->create($shortUrlVisitsModel->getTable(), function (Blueprint $table) use ($shortUrlVisitsModel, $shortUrlModel) {
                $table->id();

                $shortUrlId = $table->foreignId('short_url_id');

                if ($shortUrlVisitsModel->getConnectionName() === $shortUrlModel->getConnectionName()) {
                    $shortUrlId->constrained($shortUrlModel->getTable())->cascadeOnDelete();
                }

                $table->nullableMorphs('visitor');

                $table->string('ip_address')->nullable();
                $table->string('operating_system')->nullable();
                $table->string('operating_system_version')->nullable();
                $table->string('browser')->nullable();
                $table->string('browser_version')->nullable();
                $table->string('referer_url')->nullable();
                $table->string('device_type')->nullable();
                $table->string('user_agent')->nullable();
                $table->string('query_params')->nullable();
                $table->datetime('visited_at')->index();

                $table->timestamps();
            });
    }
};
