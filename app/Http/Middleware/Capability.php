<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Capability
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('backpack')->user();

        if ($request->is('/')) {
            return $next($request);
        }

        if ($request->is('admin/login')) {
            return $next($request);
        }


        if ($request->is('admin/logout')) {
            return $next($request);
        }

        // If the user is not authenticated, deny access to the route
        if (!$user) {
            abort(403);
        }

        $capabilities = explode(',', $user->capabilities);

        // Define the capabilities required for each route
        $routePermissions = [
            'user.index' => 1,
            'user.store' => 1,
            'user.create' => 1,
            'user.search' => 1,
            'user.update' => 1,
            'user.destroy' => 1,
            'user.showDetailsRow' => 1,
            'user.edit' => 1,
            'user.show' => 1,  // Add New Users
            // 'admin.payments' => [2, 3],  // Accept Payments, View Payments
            // 'admin.payments.create' => 3,
             // View Payments
             'payments.index' => 2,
             'payments.store' => 2,
             'payments.create' => 2,
             'payments.search' => 2,
             'payments.update' => 2,
             'payments.destroy' => 2,
             'payments.showDetailsRow' => 2,
             'payments.edit' => 2,
             'payments.show' => 2,

             'member.payment' => 3,

            'reports.index' => 4,    // View Reports
            'reports.index' => 5,        // Checkins
            'dmember.index' => 6,    // Members
            'pay.index' => 7,        // Payments
            'cashflow.index' => 8,   // Cash Flow
        ];

        $routeName = $request->route()->getName();

        // If the route requires specific capabilities, check if the user has them
        if (isset($routePermissions[$routeName])) {
            $requiredCapabilities = $routePermissions[$routeName];
            if (is_array($requiredCapabilities)) {
                foreach ($requiredCapabilities as $capability) {
                    if (in_array($capability, $capabilities)) {
                        return $next($request); // Allow access to the route
                    }
                }
            } elseif (in_array($requiredCapabilities, $capabilities)) {
                return $next($request); // Allow access to the route
            }
            // If the user does not have the required capabilities, deny access
            abort(403);
        }

        // If the route is not listed in the permissions, allow access
        return $next($request);
    }
}
