<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use \App\Http\Requests\Admin\SourceConfigItem\StoreRequest;
use App\Http\Requests\Admin\SourceConfigItem\UpdateRequest;
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
        $items = SourceConfig::query()->with('source')->orderBy('id', 'DESC')->paginate(20);
        if ($request->wantsJson()) {
            return response_success('success', $items);
        }

        return view('admin.source_config.index', compact('items'));
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

    public function destroy($id)
    {
        $config = SourceConfig::query()->findOrFail($id);

        $config->delete();

        return response_success('Xóa config thành công');
    }

    public function edit($id)
    {
        $config = SourceConfig::query()->with(['source', 'items'])->findOrFail($id);
        $sources = Source::all();
        return view('admin.source_config.edit', compact('config', 'sources'));
    }

    public function update($id, UpdateRequest $request)
    {
        $update = $this->service->update($id, $request->all(), $msg);
        if (!$update) {
            return response_error($msg);
        }
        return response_success('success', $update);
    }
}
