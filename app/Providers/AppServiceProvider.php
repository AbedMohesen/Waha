<?php

namespace App\Providers;

use App\Models\Condolence;
use App\Models\Martyr;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('condolences', function (Request $request) {
            $martyr = $request->route('martyr');
            $martyrKey = $martyr instanceof Martyr
                ? $martyr->getRouteKey()
                : (string) $martyr;

            return Limit::perMinute(3)
                ->by($request->ip() . '|' . $martyrKey)
                ->response(function (Request $request, array $headers) use ($martyr) {
                    return redirect()
                        ->route('martyr', $martyr)
                        ->withErrors(
                            ['content' => 'تم تجاوز عدد المحاولات المسموح بها. يرجى المحاولة بعد دقيقة.'],
                            'condolence',
                        )
                        ->withInput($request->only(['author_name', 'content']))
                        ->withHeaders($headers);
                });
        });

        View::composer('layouts.navigation', function ($view): void {
            $view->with(
                'pendingCondolencesCount',
                Condolence::query()->pending()->count(),
            );
        });

        \Illuminate\Support\Facades\URL::resolveMissingNamedRoutesUsing(function (string $name, array $parameters = [], bool $absolute = true) {
            if ($name === 'dashboard') {
                return route('dashboard.index', $parameters, $absolute);
            }

            return null;
        });
    }
}
