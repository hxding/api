<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Event;

class AppServiceProvider extends ServiceProvider
{

    public function boot()
    {
    	if ( env('APP_ENV') === 'local' ) {
		    DB::connection()->enableQueryLog();
		    Event::listen('kernel.handled', function ( $request, $response ) {
		        if ( $request->has('sql-debug') ) {
		            $queries = DB::getQueryLog()；
		            if (!empty($queries)) {
		                foreach ($queries as &$query) {
		                    $query['full_query'] = vsprintf(str_replace('?', '%s', $query['query']), $query['bindings']);
		                }
		            }
		            dd($queries);
		        }
		    });
		}
    }

    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
