<?php

namespace App\Providers;

use App\Actions\User\CreateNewUser;
use App\Actions\User\ResetUserPassword;
use App\Actions\User\UpdateUserPassword;
use App\Actions\User\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::loginView(function () {
            return inertia('Auth/Login');
        });

        Fortify::registerView(function () {
            return config('auth.register') ? inertia('Auth/Register') : abort(404);
        });

        Fortify::verifyEmailView(function () {
            return inertia('Auth/VerifyEmail');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return inertia('Auth/ForgotPassword');
        });

        Fortify::resetPasswordView(function () {
            return inertia('Auth/ResetPassword');
        });

        Fortify::confirmPasswordView(function () {
            return inertia('Auth/ConfirmPassword');
        });

        Fortify::twoFactorChallengeView(function () {
            return inertia('Auth/TwoFactorChallenge');
        });
    }
}
