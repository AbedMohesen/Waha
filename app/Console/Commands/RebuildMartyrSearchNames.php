<?php

namespace App\Console\Commands;

use App\Models\Martyr;
use App\Support\ArabicText;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class RebuildMartyrSearchNames extends Command
{
    protected $signature = 'martyrs:rebuild-search-names
                            {--chunk=500 : Number of martyrs processed per database batch}';

    protected $description = 'Rebuild normalized Arabic martyr names used by public search';

    public function handle(): int
    {
        $chunkSize = filter_var($this->option('chunk'), FILTER_VALIDATE_INT, [
            'options' => [
                'min_range' => 1,
                'max_range' => 5000,
            ],
        ]);

        if ($chunkSize === false) {
            $this->error('The --chunk option must be an integer between 1 and 5000.');

            return self::INVALID;
        }

        if (! Schema::hasColumn('martyrs', 'name_ar_normalized')) {
            $this->error('The martyrs.name_ar_normalized column does not exist. Run the pending migrations first.');

            return self::FAILURE;
        }

        try {
            $total = Martyr::query()->count();
            $changed = 0;
            $progress = $this->output->createProgressBar($total);
            $progress->start();

            Martyr::query()
                ->select(['id', 'name_ar', 'name_ar_normalized'])
                ->orderBy('id')
                ->chunkById($chunkSize, function ($martyrs) use (&$changed, $progress): void {
                    $updates = [];

                    foreach ($martyrs as $martyr) {
                        $normalizedName = ArabicText::normalize($martyr->name_ar);

                        if ($martyr->name_ar_normalized !== $normalizedName) {
                            $updates[] = [
                                'id' => $martyr->getKey(),
                                'name_ar_normalized' => $normalizedName,
                            ];
                        }
                    }

                    if ($updates !== []) {
                        DB::table('martyrs')->upsert(
                            $updates,
                            ['id'],
                            ['name_ar_normalized'],
                        );

                        $changed += count($updates);
                    }

                    $progress->advance($martyrs->count());
                });

            $progress->finish();
            $this->newLine(2);
            $this->info("Rebuilt search names successfully. Processed: {$total}; updated: {$changed}.");

            return self::SUCCESS;
        } catch (Throwable $exception) {
            report($exception);
            $this->error('Unable to rebuild martyr search names. Check the application log for details.');

            return self::FAILURE;
        }
    }
}
