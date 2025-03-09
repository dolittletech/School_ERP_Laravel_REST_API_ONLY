<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\SchSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurrencyController extends Controller
{
    public function get($id = null)
    {
        $query = Currency::select('currencies.*', DB::raw('IFNULL(sch_settings.currency, 0) as currency_id'))
            ->leftJoin('sch_settings', 'currencies.id', '=', 'sch_settings.currency');

        if ($id) {
            $currency = $query->where('currencies.id', $id)->first();
            return response()->json(['data' => $currency ? $currency->toArray() : null], 200);
        } else {
            $currencies = $query->orderBy('id')->get();
            return response()->json(['data' => $currencies], 200);
        }
    }

    public function add(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate([
                'short_name' => 'required|string',
                'symbol' => 'required|string',
                'base_price' => 'required|numeric',
            ]);
            if ($request->has('id') && $request->input('id') > 0) {
                $currency = Currency::find($request->input('id'));
                if ($currency) {
                    $currency->update($data);
                }
            } else {
                $currency = Currency::create($data);
            }
            DB::commit();
            return response()->json(['message' => 'Currency ' . ($request->has('id') ? 'updated' : 'added'), 'data' => $currency], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function updateCurrency(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate(['id' => 'required|exists:sch_settings,id', 'currency' => 'exists:currencies,id']);
            $schSetting = SchSetting::find($data['id']);
            if ($schSetting) {
                $schSetting->update(['currency' => $data['currency']]);
                DB::commit();
                return response()->json(['message' => 'Currency updated in settings', 'data' => $schSetting], 200);
            }
            return response()->json(['message' => 'Setting not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
