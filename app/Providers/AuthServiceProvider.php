<?php

namespace App\Providers;

 use App\Models\Note;
 use App\Models\User;
 use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('update-note', function (User $user, Note $note) {
            return $user->id === $note->user_id;
        });
        Gate::define('delete-note', function (User $user, Note $note) {
            return $user->id === $note->user_id;
        });
    }
}
