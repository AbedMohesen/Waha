<?php

use App\Support\ArabicText;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('martyrs', function (Blueprint $table) {
            $table->string('name_ar_normalized')
                ->nullable()
                ->after('name_ar')
                ->index();
        });

        DB::table('martyrs')
            ->select(['id', 'name_ar'])
            ->orderBy('id')
            ->chunkById(500, function ($martyrs): void {
                $updates = $martyrs
                    ->map(fn ($martyr): array => [
                        'id' => $martyr->id,
                        'name_ar_normalized' => ArabicText::normalize($martyr->name_ar),
                    ])
                    ->all();

                DB::table('martyrs')->upsert(
                    $updates,
                    ['id'],
                    ['name_ar_normalized']
                );
            });
    }

    public function down(): void
    {
        Schema::table('martyrs', function (Blueprint $table) {
            $table->dropIndex(['name_ar_normalized']);
            $table->dropColumn('name_ar_normalized');
        });
    }
};
