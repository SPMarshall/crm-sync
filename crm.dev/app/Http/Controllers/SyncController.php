<?php
namespace App\Http\Controllers;
set_time_limit(0);
use App\Kved;
use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;
use Log;
use SebastianBergmann\RecursionContext\Exception;
use Symfony\Component\HttpFoundation\Response;

class SyncController extends Controller {

    /**
     * Show the suncronization page result
     *
     * @return Response
     */
    public function synchronize() {
        
        $start = time();
        $start_micro = microtime();
        //#1. Get Deleted Count.
        $count_delete = $this->_sendRequest('kved-facade/count/delete', 'get');
        $this->_validateJson($count_delete);
        

        //#2. Process Deleted kveds by kved id.
        $i = 0;
        while ($i < $count_delete['data']) {
            $get_kveds = $this->_sendRequest('kved-facade/kveds', 'post', ['count' => config('app.sync_rows_count'), 'operation' => 'delete', 'field_list' => ['kved']]);

            //#3. Confirm Deletion
            $kved_codes = array_flatten($get_kveds['data']);
            $confirm_deletion = $this->_deleteKveds($kved_codes);
            if ($confirm_deletion) {
                $response = $this->_sendRequest('kved-facade/confirm-sync', 'post', ['kved_codes' => $kved_codes]);
                Log::info('Synchronization Successful:: Delete operation is synchronized for: ', ['kved_codes' => implode(',  ', $kved_codes)]);
            } else {
                Log::critical('Synchronization Failed :: Unable to synchronize delete operation for :', ['kved_codes' => implode(',  ', $kved_codes)]);
            }
            $i = $i + config('app.sync_rows_count');
        }
        
        //#4. Get Updated Count
        $count_updated = $this->_sendRequest('kved-facade/count/update', 'get');
        //#5. Process Update or Insert kveds.
        $i = 0;
        while ($i < $count_updated['data']) {
            $get_kveds = $this->_sendRequest('kved-facade/kveds', 'post', ['count' => config('app.sync_rows_count'), 'operation' => 'update', 'field_list' => ['kved', 'description']]);
            //#6. Confirm Deletion
            $kved_codes_arr = $this->_createKveds($get_kveds['data']);
            if ($kved_codes_arr) {
                $response = $this->_sendRequest('kved-facade/confirm-sync', 'post', ['kved_codes' => $kved_codes_arr]);
                Log::info('Synchronization Successful:: Add/update operation is synchronized for: ', ['kved_codes' => implode(',  ', $kved_codes_arr)]);
            } else {
                Log::critical('Synchronization Failed :: Unable to synchronize add/update operation for :', ['kved_codes' => implode(',  ', $kved_codes_arr)]);
            }
            $i = $i + config('app.sync_rows_count');
        }

        $s = time() - $start;
        $m = floor($s / 60);
        $h = floor($m / 60);
        $mins = ($m % 60);
        $seconds = ($s % 60);
        $deviation = microtime() - $start_micro;
        dd("Synchronization completed. Script has been running for: MicroSeconds - $deviation  Seconds - $seconds  | Minutes - $mins | Hours - $h | Step - ".config('app.sync_rows_count'));
    }

    /**
     * 
     * @param string $action_url
     * @param string $method
     * @param array $params
     * @return mixed json decoded response as an array or false on empty response
     */
    private function _sendRequest($action_url, $method = 'get', $params = []) {
        if ($method == 'get')
            $response = Curl::get('http://accountant.dev/' . $action_url, $params);
        else {
            $response = Curl::post('http://accountant.dev/' . $action_url, null, $params);
        }
        if ($response)
            return json_decode($response, true);
        return false;
    }

    /**
     * Function validates json resonse array
     * and prints error on a screen if occured and stops script execution.
     * 
     * @param array $json_array
     * @return boolean
     */
    private function _validateJson($json_array) {
        if (!empty($json_array) && $json_array['code'] != 200) {
            ob_start();
            echo '=====================================</br>';
            echo 'Fatal Error:<br>';
            echo 'Status:' . $json_array['status'] . '<br>';
            echo 'Code:' . $json_array['code'] . '<br>';
            echo 'Message:' . $json_array['msg'] . '<br>';
            echo 'Data:<br>';
            print_r($json_array['data']);
            echo '</br>=====================================</br>';
            ob_flush();
            Log::critical('Synchronization Failed :: Bad Request. Forced exit; ', ['json' => $json_array]);
            exit;
        }
        return true;
    }

    /**
     * Hadles kveds add/update
     * 
     * @param array $data
     * @return mixed array on success/boolean on failure
     */
    private function _createKveds($data) {
        if (!$data)
            return false;
        $array_kved_codes = [];
        try {
            DB::beginTransaction();
            foreach ($data as $item) {
                $tmp = array_flatten($item);
                list($kved_code, $kved_description) = $tmp;
                if (Kved::updateOrCreate(['kved' => $kved_code], ['description' => $kved_description])) {
                    $array_kved_codes[] = $kved_code;
                }
            }
            DB::commit();
        } catch (Exception $s) {
            DB::rollback();
            $array_kved_codes = false;
        }
        return $array_kved_codes;
    }

    /**
     * Handles deletion operation
     * 
     * @param array $array_kved_num
     * @return boolean
     */
    private function _deleteKveds($array_kved_num) {
        if (!$array_kved_num)
            return false;

        try {
            DB::beginTransaction();
            DB::table('kveds')->whereIn('kved', $array_kved_num)->delete();
            DB::commit();
        } catch (Exception $s) {
            DB::rollback();
        }
        return true;
    }

}
