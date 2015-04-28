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
        $count = $this->_sendRequest('sync/count', 'get');
        $this->_validateJson($count);
        $i = 0;
        while ($i < $count['data']) {
            $data = $this->_sendRequest('sync/data', 'post', ['count' => config('app.sync_rows_count')]);
            $journal_ids = $this->_process($data['data']);
            if ($journal_ids) {
                $this->_sendRequest('sync/confirm', 'post', ['ids' => json_encode($journal_ids)]);
                Log::info('Synchronization Successful.');
            }
            $i = $i + config('app.sync_rows_count');
        }
        $s = time() - $start;
        $m = floor($s / 60);
        $h = floor($m / 60);
        $mins = ($m % 60);
        $seconds = ($s % 60);
        $deviation = microtime() - $start_micro;
        dd("Synchronization completed. It took: MicroSeconds - $deviation  Seconds - $seconds  | Minutes - $mins | Step - " . config('app.sync_rows_count'));
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
     * Process crud operations for each rows
     * 
     * @param array $data
     * @return array
     */
    private function _process($data) {
        if (!$data || !is_array($data))
            return false;

        $processed_journal_ids = [];
        try {
            DB::beginTransaction();
            foreach ($data as $journal_row) {
                //we assume that all rows are kveds for now.
                switch ($journal_row['operation']) {
                    case 'create':
                    case 'update':
                        $tmp_obj = json_decode($journal_row['data']);
                        Kved::updateOrCreate(['kved' => $journal_row['entity_identifier']], ['description' => $tmp_obj->description]);
                        break;

                    case 'delete':
                        Kved::where('kved', $journal_row['entity_identifier'])->delete();
                        break;
                }
                $processed_journal_ids[] = $journal_row['id'];
            }
            DB::commit();
        } catch (Exception $s) {
            DB::rollback();
            $processed_journal_ids = false;
        }
        return $processed_journal_ids;
    }

}