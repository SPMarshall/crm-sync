<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Kved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\RecursionContext\Exception;
use Log;

class KvedFacadeController extends Controller {

    /**
     * Sends kved count for the needed operation;
     * 
     * @param string $operation
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getCount($operation) {
        $count = Kved::where('edited', 1)->where('operation', $operation)->count();
        return response()->json(['status' => 'OK', 'code' => 200, 'msg' => 'Kveds count', 'data' => $count]);
    }

    /**
     * Sends kved data to sync
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postKveds(Request $request) {
        $this->validate($request, [
            'count' => 'required',
            'operation' => 'required',
            'field_list' => 'required',
        ]);
        $params = $request->all();
        $count = $params['count'];
        $operation = $params['operation'];
        $field_list = $params['field_list'];

        $data = [];
        $kveds = Kved::where('edited', 1)->where('operation', $operation)->take($count)->get();
        if ($kveds) {
            foreach ($kveds as $kved) {
                $tmp = [];
                foreach ($field_list as $field) {
                    $field = trim($field);
                    $tmp[] = [$field => $kved->$field];
                }
                $data[] = $tmp;
            }
        }
        return response()->json(['status' => 'OK', 'code' => 200, 'msg' => 'Kveds data', 'data' => $data]);
    }

    /**
     * Confirms the syncronization for kved_codes
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postConfirmSync(Request $request) {
        $this->validate($request, [
            'kved_codes' => 'required',
        ]);
        $kved_codes = array_flatten($request->input('kved_codes'));
        try {
            DB::beginTransaction();
            DB::table('kveds')->whereIn('kved', $kved_codes)->update(['edited' => 0]);
            DB::commit();
            $msg = 'KvedFacade: Synchronization Succesfull';
            $data = $kved_codes;
            $code = 200;
            $status = 'Ok';
            Log::info('Syncronization Successfull :' . __METHOD__, ['kved_codes' => implode(',  ', $kved_codes)]);
        } catch (Exception $e) {
            DB::rollback();
            Log::critical('Syncronization Failed :' . __METHOD__, ['kved_codes' => implode(',  ', $kved_codes)]);
            $msg = 'Syncronization Failed';
            $data = 'Exception:' . $e->getMessage();
            $code = 500;
            $status = 'Error';
        }
        return response()->json(['status' => $status, 'code' => $code, 'msg' => $msg, 'data' => $data]);
    }

}
