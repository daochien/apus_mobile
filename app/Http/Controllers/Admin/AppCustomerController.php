<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AppCustomer\StoreRequest;
use App\Models\Package;
use App\Services\Admin\AppCustomerService;
use Illuminate\Http\Request;

class AppCustomerController extends Controller
{
    protected $service;

    public function __construct(AppCustomerService $service)
    {
        $this->service = $service;
    }

    public function index()
    {

    }

    public function create()
    {
        $packages = Package::query()->orderBy('id', 'DESC')->get();
        return view('admin.app_customer.create', compact('packages'));
    }

    public function store(StoreRequest $request)
    {
        $result = $this->service->store($request->all(), $msg);
        if (!$result) {
            return response_error($msg);
        }
        return response_success('success', $result);
    }
}
