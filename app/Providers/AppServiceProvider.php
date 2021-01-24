<?php

namespace App\Providers;

use App\Policies\UserPolicy; 
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;  
use App\Header;
use App\Category;
use App\Info;
use View;
use DateTime;
class AppServiceProvider extends AuthServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
 

    public function boot()
    {   
         
        $this->registerPolicies();

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        View::share('activeHeader', Header::where('set_default',1)->first()  );    


        View::share('categories', Category::all()  );   
            

    }

    
}
