<?php

use App\Models\Mission;
use App\Models\PlayHistory;
use App\Models\Setting;
use App\Services\BackEndService;
use App\Services\BanksService;
use Carbon\Carbon;
use hisorange\BrowserDetect\Parser;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;
use Telegram\Bot\Laravel\Facades\Telegram;

if (!function_exists('return_success')) {
    function return_success ($data = [], $message = 'success') {
        return [
            'error' => false,
            'data' => $data,
            'message' => $message
        ];
    }
}

if (!function_exists('return_error')) {
    function return_error ($message = 'Server something error') {
        return [
            'error' => true,
            'message' => $message
        ];
    }
}

if (!function_exists('response_success')) {

    /**
     * @param string $message
     * @param array $data
     * @param array $extra_data
     * @param bool $json
     * @return \Illuminate\Http\JsonResponse
     */
    function response_success($message = 'Success', $data = [], $extra_data = [], $json = false)
    {
        $response = [
            'server_time' => time(),
            'status' => 1,
            'message' => $message,
            'error_code' => 0,
            'data' => []
        ];

        if (!empty($data) || is_null($data)) {
            $response['data'] = $data;
        }

        $response = array_merge($response, $extra_data);

        if (empty($data) && $json) {
            $response['data'] = json_encode($data, JSON_FORCE_OBJECT);
        }

        return response()->json($response);
    }
}

if (!function_exists('response_error')) {


    /**
     * @param $message
     * @param array $errors
     * @param array $extra_data
     * @param int $status_code
     * @return \Illuminate\Http\JsonResponse
     */
    function response_error($message, $errors = [], $extra_data = [], $status_code = 200)
    {
        $response = [
            'server_time' => time(),
            'status' => 0,
            'message' => $message,
            'error_validate' => $status_code,
            'error_type' => 'api',
            'error_code' => 1
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        $response = array_merge($response, $extra_data);

        return response()->json($response, $status_code);
    }
}

if (!function_exists('logDebug')) {
    function logDebug(string $title, Exception $exception, $data) {
        Log::debug($title, [
            'message' => $exception->getMessage(),
            'trace' => $exception->getTrace(),
            'data' => $data
        ]);
    }
}

if (!function_exists('get_client_ip')) {
    function get_client_ip() {
        if (request()->header('ips')) {
            return request()->header('ips');
        }
        return request()->ip();
    }
}

if (!function_exists('price_format')) {
    /**
     * @param $amount
     * @return string
     */
    function price_format($amount)
    {
        return number_format($amount, 0, ',', '.').'đ';
    }
}

if (!function_exists('zero_number_format')) {
    /**
     * @param $number
     * @return string
     */
    function zero_number_format($number)
    {
        if ( 0 < $number && $number < 9){
            return '0'.$number;
        }
        return $number;
    }
}
if (!function_exists('mask_phone')) {
    function mask_phone($phone)
    {
        $first = substr($phone,0,4);
        $last = substr($phone,-3,3);
        return $first.'xxx'.$last;
    }
}

if (!function_exists('log_action')) {
    function log_action($title, $model, $params = [], $content = '')
    {
        activity($title)
            ->performedOn($model)
            ->causedBy(\Illuminate\Support\Facades\Auth::user())
            ->withProperties($params)
            ->log($content);
    }
}

if (!function_exists('flush_tag_cache')) {
    function flush_tag_cache($tags)
    {
        $tags = is_array($tags) ? $tags : [$tags];
        Cache::tags($tags)->flush();
    }
}

?>
