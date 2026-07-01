<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BillingAnalyticsService;



class DashboardController extends Controller
{
    //
    protected $analytics;

    public function __construct(BillingAnalyticsService $analytics)
    {
        $this->analytics = $analytics;
    }

    public function index()
    {
        $metrics = $this->analytics->getDashboardMetrics();
        return view('admin.dashboard', $metrics);
    }
}
