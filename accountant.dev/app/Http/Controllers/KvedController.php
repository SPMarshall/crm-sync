<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Journal;
use App\Kved;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ixudra\Curl\Facades\Curl;

class KvedController extends Controller {

    private $_request;
    private $_auth;

    /**
     * Controlles construct. Adds necessary dependencies
     * @param Request $request
     * @param Guard $auth
     */
    public function __construct(Request $request, Guard $auth) {
        $this->_request = $request;
        $this->_auth = $auth;
        /* we allow all ajax methods for now */
        $this->middleware('auth', ['only' => ['postKved', 'getDeleteKved']]);
    }

    /**
     * Handle Add kved form request
     *
     * @return Response
     */
    public function postKved() {
        $this->validate($this->_request, [
            'kved' => 'required',
        ]);

        $with = ['message' => 'A new Kved has been added!', 'alert-class' => 'alert-success'];
        $kved_code = trim($this->_request->input('kved'));

        $kved = Kved::where('kved', $kved_code)->first();
        if (!$kved) {
            $html = Curl::get('http://kved.ukrstat.gov.ua/cgi-bin/kv-query.exe?kv10=' . $kved_code);
            preg_match('#<h2>(.+?)</h2>#is', $html, $matches); //parsing external html page
            if (empty($matches[0])) {// kved does not exists
                $with = ['message' => 'Entered Kved Code is not found in the kved list for 2010.', 'alert-class' => 'alert-danger'];
                goto redirection;
            }

            $matches = strip_tags($matches[0]);
            $arr = explode(PHP_EOL, $matches);
            $arr = array_map('trim', $arr);
            $kved = new Kved();
            $kved->description = mb_convert_encoding($arr[1], "utf-8", "windows-1251");
            $kved->kved = $arr[0];
            if ($kved->save()) {
                Journal::create(['entity_name' => 'Kved', 'entity_identifier' => $kved->kved, 'operation' => 'create', 'data' => json_encode($kved)]);
            }
        }

        $curr_ids = $this->_auth->user()->kveds()->lists('id');
        $curr_ids[] = $kved->id;
        $this->_auth->user()->kveds()->sync($curr_ids);

        redirection:
        return redirect('pages/user-kveds')->with($with);
    }

    /**
     * Handle mark kved as main for the user
     *
     * @return Response
     */
    public function postSetMainAjax() {
        $this->validate($this->_request, [
            'kved_id' => 'required',
        ]);
        $kved_id = $this->_request->input('kved_id');
        $user_obj = $this->_auth->user();
        $user_obj->kveds()->update(['main' => 0]); /* reset all to 0 */
        $user_obj->kveds()->newPivotStatementForId($kved_id)->update(['main' => 1]);
        return response()->json(['status' => 'OK', 'code' => '200']);
    }

    /**
     * Update kved field
     * Handles update description ajax call
     *
     * @return Response
     */
    public function postKvedFieldAjax() {
        
        $this->validate($this->_request, [
            'kved_id' => 'required',
            'field' => 'required',
            'value' => 'required',
        ]);
        
        $params = $this->_request->only('kved_id', 'field', 'value');
        $kved_model = Kved::findOrFail($params['kved_id']);
        $kved_model->$params['field'] = trim($params['value']);
        
        $result = $kved_model->save();
        if ($result){
            Journal::create(['entity_name' => 'Kved', 'entity_identifier' => $kved_model->kved, 'operation' => 'update', 'data' => json_encode($kved_model)]);
            return response()->json(['status' => 'OK', 'code' => '200']);
        }
        return response()->json(['status' => 'Error']);
    }

    /**
     * Deletes kved by id from kveds table
     * 
     * @param int $kved_id
     * @return Response
     */
    public function getDeleteKved($kved_id) {
        if ($kved_id) {
            $kved_model = Kved::find($kved_id);
            if ($kved_model) {
                Journal::create(['entity_name' => 'Kved', 'entity_identifier' => $kved_model->kved, 'operation' => 'delete']);
                $kved_model->delete();
                return response()->json(['status' => 'OK', 'code' => '200']);
            }
        }
        return response()->json(['status' => 'Error']);
    }

    /*
     * Deletes kved id from kved_user pivot table, but keeps the kved itself 
     * 
     * @return Response
     */

    public function postDeleteKvedAjax() {
        $this->validate($this->_request, [
            'kved_id' => 'required',
        ]);
        $kved_id = $this->_request->input('kved_id');
        $this->_auth->user()->kveds()->detach($kved_id);
        return response()->json(['status' => 'OK', 'code' => '200']);
    }

}
