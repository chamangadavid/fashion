<?php

namespace App\Http\Controllers\MyFashions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Inertia\Inertia;

class SettingsController extends Controller
{
    
/*
    |--------------------------------------------------------------------------
    | PAYMENT SETTINGS PAGE
    |--------------------------------------------------------------------------
    */

    public function payments()
    {
        $settings = [
            'cash_on_delivery' => $this->getSetting(
                'cash_on_delivery',
                true
            ),

            'mobile_money' => $this->getSetting(
                'mobile_money',
                true
            ),

            'card' => $this->getSetting(
                'card',
                false
            ),

            'mobile_money_provider' => $this->getSetting(
                'mobile_money_provider',
                ''
            ),

            'mobile_money_number' => $this->getSetting(
                'mobile_money_number',
                ''
            ),

            'card_provider' => $this->getSetting(
                'card_provider',
                ''
            ),

            'currency' => $this->getSetting(
                'currency',
                'ZMW'
            ),

            'payment_instructions' => $this->getSetting(
                'payment_instructions',
                ''
            ),
        ];

        return Inertia::render(
            'MyFashions/Settings/Payments',
            [
                'settings' => $settings,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE PAYMENT SETTINGS
    |--------------------------------------------------------------------------
    */

    public function updatePayments(Request $request)
    {
        $validated = $request->validate([

            'cash_on_delivery' => [
                'required',
                'boolean',
            ],

            'mobile_money' => [
                'required',
                'boolean',
            ],

            'card' => [
                'required',
                'boolean',
            ],

            'mobile_money_provider' => [
                'nullable',
                'string',
                'max:100',
            ],

            'mobile_money_number' => [
                'nullable',
                'string',
                'max:50',
            ],

            'card_provider' => [
                'nullable',
                'string',
                'max:100',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'payment_instructions' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | SAVE SETTINGS
        |--------------------------------------------------------------------------
        */

        foreach ($validated as $key => $value) {

            Setting::updateOrCreate(

                [
                    'key' => $key,
                ],

                [
                    'value' => is_bool($value)
                        ? ($value ? '1' : '0')
                        : $value,
                ]

            );
        }


        return redirect()
            ->back()
            ->with(
                'success',
                'Payment settings updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GET SETTING
    |--------------------------------------------------------------------------
    */

    private function getSetting(string $key, $default = null)
    {
        $setting = Setting::where(
            'key',
            $key
        )->first();

        if (!$setting) {

            return $default;

        }


        /*
        |--------------------------------------------------------------------------
        | BOOLEAN SETTINGS
        |--------------------------------------------------------------------------
        */

        $booleanSettings = [

            'cash_on_delivery',

            'mobile_money',

            'card',

        ];


        if (in_array($key, $booleanSettings)) {

            return filter_var(
                $setting->value,
                FILTER_VALIDATE_BOOLEAN
            );

        }


        return $setting->value;
    }

}
