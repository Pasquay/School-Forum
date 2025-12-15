<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\InboxMessage;

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
        // Ensure PHP's timezone matches Laravel's config
        date_default_timezone_set(config('app.timezone'));

        // Share inbox messages with all views
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $unreadMessages = InboxMessage::forUser(Auth::id())
                    ->where('responded', false);

                $readMessages = InboxMessage::forUser(Auth::id())
                    ->where('responded', true);

                $view->with('unreadMessages', $unreadMessages);
                $view->with('readMessages', $readMessages);
            } else {
                $view->with('unreadMessages', collect());
                $view->with('readMessages', collect());
            }
        });
    }
}
