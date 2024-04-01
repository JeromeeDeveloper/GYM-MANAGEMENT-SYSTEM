<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Route;
use Request;

class CustomSidebarServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->addSidebarContent();
    }

    protected function addSidebarContent()
    {
        \View::composer('backpack::layout.sidebar', function ($view) {
            $nav = config('backpack.base.sidebar_content');

            $restrictedEntries = [
                // Add any other CRUD entries you want to exclude from the sidebar here
                \App\Http\Controllers\Admin\SubscriptionPlanCrudController::class,
            ];

            foreach ($restrictedEntries as $restrictedEntry) {
                if (($key = array_search($restrictedEntry, $nav)) !== false) {
                    unset($nav[$key]);
                }
            }

            $view->with('nav', $nav);
        });
    }
}
