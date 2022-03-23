<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Source\StoreRequest;
use App\Http\Requests\Admin\Source\UpdateRequest;
use App\Services\Admin\SourceService;
use Illuminate\Http\Request;


class SourceController extends Controller
{

    protected $service;

    public function __construct(SourceService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $sources = $this->service->fetchList($request->all());

        if ($request->wantsJson()) {
            return response_success('success', $sources);
        }

        return view('admin.source.index', compact('sources'));
    }

    public function create(Request $request)
    {
        return view('admin.source.create');
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
        $source = $this->service->repo()->findById($id);
        if (!$source) {
            return response_error('Không tìm thấy thông tin source mẫu');
        }

        $source->load('configs');

        return view('admin.source.edit', compact('source'));
    }

    public function update(UpdateRequest $request, $id)
    {
        $update = $this->service->update($id, $request->all());
        if (!$update) {
            return response_error('Cập nhật source mẫu không thành công');
        }

        return response_success('success');
    }

    public function destroy($id)
    {
        $source =  $this->service->repo()->findById($id);
        if (!$source) {
            return response_error('Không tìm thấy thông tin source mẫu');
        }
        $source->delete();

        return response_success('Xóa source mẫu thành công');
    }
}
