<?php

namespace MultiTenantLaravel\App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('multi-tenant');
    }

    /**
     * Show the user dashboard with option to select which tenant to manage
     * or redirect them to their currently active tenant
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->owns()->count() === 1 || session()->has('tenant.id')) {
            return view('multi-tenant::tenant.home');
        }

        return view('multi-tenant::dashboard');
    }

    /**
     * Add the selected tenant to the users session
     */
    public function selectTenant()
    {
        $id = request()->get('id');

        $tenant = config('multi-tenant.tenant_class')::findOrFail($id);

        request()->session()->put('tenant', [
            'id' => $tenant->id
        ]);

        return redirect()->back();
    }

    /**
     * Remove the selected tenant from the users session
     */
    public function changeTenant()
    {
        request()->session()->forget('tenant');

        return redirect('/');
    }
}
