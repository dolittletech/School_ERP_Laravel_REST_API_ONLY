<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\WhatsappConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WhatsappConfigController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth'); // Assuming admin authentication
    //     $this->middleware('permission:whatsapp_setting.view', ['only' => ['index']]); // RBAC (optional)
    // }

    public function index()
    {
        // if (!auth()->user()->can('whatsapp_setting.view')) {
        //     abort(403, 'Unauthorized');
        // }

        $whatsappConfigs = WhatsappConfig::all();
        $statusList = ['enabled' => 'Enabled', 'disabled' => 'Disabled']; // Example status list

        return [
            'title' => 'WhatsApp Config List',
            'whatsapplist' => $whatsappConfigs,
            // 'statuslist' => $statusList,
        ];
    }

    public function whatsapp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'whatsapp_account_sid' => 'required',
            'whatsapp_account_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'st' => 1,
                'msg' => $validator->errors()->all(),
            ]);
        }

        $data = [
            'account_sid' => $request->input('whatsapp_account_sid'),
            'account_token' => $request->input('whatsapp_account_token'),
            'is_active' => $request->input('whatsapp_status', 'disabled'),
        ];

        $whatsappConfig = new WhatsappConfig();
        $whatsappConfig->add($data);

        return response()->json([
            'st' => 0,
            'msg' => __('Update successful'),
        ]);
    }
}