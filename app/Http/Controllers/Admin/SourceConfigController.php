<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use \App\Http\Requests\Admin\SourceConfigItem\StoreRequest;
use App\Models\Source;
use App\Models\SourceConfig;
use App\Services\Admin\SourceConfigService;
use Illuminate\Http\Request;

class SourceConfigController extends Controller
{

    protected $service;

    public function __construct(SourceConfigService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return view('admin.source_config.index');
    }

    public function create(Request $request)
    {
        $sources = Source::all();
        return view('admin.source_config.create', compact('sources'));
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
