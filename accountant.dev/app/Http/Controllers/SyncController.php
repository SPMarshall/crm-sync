<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\RecursionContext\Exception;
use Log;

class SyncController extends Controller {

    /**
     * Sends journal count for the needed operation;
     * 
     * @param string $operation
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getCount() {
        return response()->json(['status' => 'OK', 'code' => 200, 'msg' => 'Kveds count', 'data' => Journal::count()]);
    }

    /**
     * Sends journal data to sync
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postData(Request $request) {
        $this->validate($request, [
            'count' => 'required',
        ]);
        $count = $request->input('count');
        $journal_rows = Journal::orderBy('created_at','ASC')->take($count)->get()->toArray();
        return response()->json(['status' => 'OK', 'code' => 200, 'msg' => 'Data', 'data' => $journal_rows]);
    }

    /**
     * Confirms the syncronization
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postConfirm(Request $request) {
        $this->validate($request, [
            'ids' => 'required',
        ]);
        $journal_ids = json_decode($request->input('ids'));
        try {
            DB::beginTransaction();
            DB::table('journals')->whereIn('id', $journal_ids)->delete();
            DB::commit();
            $msg = 'SyncController: Synchronization Succesfull';
            $data = $journal_ids;
            $code = 200;
            $status = 'Ok';
            Log::info('Syncronization Successfull :' . __METHOD__, ['journal_ids' => implode(',  ', $journal_ids)]);
        } catch (Exception $e) {
            DB::rollback();
            Log::critical('Syncronization Failed :' . __METHOD__, ['journal_ids' => implode(',  ', $journal_ids)]);
            $msg = 'Syncronization Failed';
            $data = 'Exception:' . $e->getMessage();
            $code = 500;
            $status = 'Error';
        }
        return response()->json(['status' => $status, 'code' => $code, 'msg' => $msg, 'data' => $data]);
    }

}
