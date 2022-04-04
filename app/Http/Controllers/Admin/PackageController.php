<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Package\StoreRequest;
use App\Http\Requests\Admin\Package\UpdateRequest;
use App\Models\Source;
use App\Services\Admin\PackageService;
use Illuminate\Http\Request;

class PackageController extends Controller
{

    protected $service;

    public function __construct(PackageService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $packages = $this->service->fetchList($request->all());

        if ($request->wantsJson()) {
            return response_success('success', $packages);
        }

        return view('admin.package.index', compact('packages'));
    }

    public function create(Request $request)
    {
        $sources = Source::query()->with('configs.items')->orderBy('id', 'desc')->get();

        return view('admin.package.create', compact('sources'));
    }

    public function store(StoreRequest $request)
    {
        $result = $this->service->store($request->all(), $msg);
        if (!$result) {
            return response_error($msg);
        }
        return response_success('success', $result);
    }

    public function edit(Request $request, $id)
    {
        $package = $this->service->repo()->findById($id);

        if (!$package) {
            return response_error('Không tìm thấy thông tin package');
        }
        $sources = Source::query()->with('configs.items')->orderBy('id', 'desc')->get();
        return view('admin.package.edit', compact('package', 'sources'));
    }

    public function update(UpdateRequest $request, $id)
    {
        $update = $this->service->update($id, $request->all());
        if (!$update) {
            return response_error('Cập nhật package không thành công');
        }

        return response_success('success');
    }

    public function destroy($id)
    {
        $source =  $this->service->repo()->findById($id);
        if (!$source) {
            return response_error('Không tìm thấy thông tin package');
        }
        $source->delete();

        return response_success('Xóa package thành công');
    }
}
