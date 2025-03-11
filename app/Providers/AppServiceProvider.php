<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
        
        // Personaliza el correo de verificación
        VerifyEmail::toMailUsing(function ($notifiable, $url) {

            // Aquí accedemos al 'name' del usuario
            $name = $notifiable->name;

            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Verifica tu correo electrónico')
                 ->greeting('¡Hola ' . $name . '!')
                ->line('Bienvenido a SIIU! Antes de comenzar, necesitamos verificar tu correo electrónica.')
                ->line('Por favor, haz clic en el botón siguiente para verificar tu dirección de correo electrónico.')
                ->action('Verificar correo', $url)
                ->line('Si no has solicitado esta verificación, por favor ignora este mensaje.');
        });
    }
}
