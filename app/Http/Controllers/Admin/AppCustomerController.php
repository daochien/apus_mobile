<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AppCustomer\StoreRequest;
use App\Models\AppCustomer;
use App\Models\Package;
use App\Services\Admin\AppCustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AppCustomerController extends Controller
{
    protected $service;

    public function __construct(
        AppCustomerService $service
    )
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $items = AppCustomer::query()->with('package')->orderBy('id', 'DESC')->paginate(10);
        if ($request->wantsJson()) {
            return response_success('success', $items);
        }

        return view('admin.app_customer.index', compact('items'));
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

    public function destroy($id)
    {
        $item = AppCustomer::query()->findOrFail($id);
        if (!$item) {
            return response_error('Không tìm thấy thông tin app khách hàng');
        }
        $item->delete();

        return response_success('Xóa app khách hàng thành công');
    }

    public function download($code)
    {
        $app = AppCustomer::query()->firstWhere(['code' => $code]);
        if (!$app) {
            return response_error('Không tìm thấy thông tin app customer');
        }

        $path = storage_path("app/public/app_customers/{$code}.zip");

        $filename = 'source_app.zip';

        $headers = [
            'Content-Disposition' => 'attachment; filename='. $filename. ';'
        ];
        return response()->download($path, $filename, $headers);

    }
}
