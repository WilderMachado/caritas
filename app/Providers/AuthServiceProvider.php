<?php

namespace caritas\Providers;

//use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //'caritas\Model' => 'caritas\Policies\ModelPolicy',
        'caritas\Instituicao'=>'caritas\Policies\InstituicaoPolicy',
        'caritas\Membro'=>'caritas\Policies\MembroPolicy',
        'caritas\User'=>'caritas\Policies\UserPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
