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




    /*
    |--------------------------------------------------------------------------
    | STORE SETTINGS PAGE
    |--------------------------------------------------------------------------
    */

    public function store()
    {
        $settings = [
            'store_name' => $this->getSetting(
                'store_name',
                'My Fashion Store'
            ),

            'store_tagline' => $this->getSetting(
                'store_tagline',
                ''
            ),

            'store_email' => $this->getSetting(
                'store_email',
                ''
            ),

            'store_phone' => $this->getSetting(
                'store_phone',
                ''
            ),

            'store_address' => $this->getSetting(
                'store_address',
                ''
            ),

            'store_city' => $this->getSetting(
                'store_city',
                ''
            ),

            'store_country' => $this->getSetting(
                'store_country',
                'Zambia'
            ),

            'store_currency' => $this->getSetting(
                'store_currency',
                'ZMW'
            ),

            'store_description' => $this->getSetting(
                'store_description',
                ''
            ),

            'store_status' => $this->getSetting(
                'store_status',
                'open'
            ),

            'store_logo' => $this->getSetting(
                'store_logo',
                ''
            ),
        ];

        return Inertia::render(
            'MyFashions/Settings/Store',
            [
                'settings' => $settings,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE STORE SETTINGS
    |--------------------------------------------------------------------------
    */

    public function updateStore(Request $request)
    {
        $validated = $request->validate([

            'store_name' => [
                'required',
                'string',
                'max:255',
            ],

            'store_tagline' => [
                'nullable',
                'string',
                'max:255',
            ],

            'store_email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'store_phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'store_address' => [
                'nullable',
                'string',
                'max:255',
            ],

            'store_city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'store_country' => [
                'required',
                'string',
                'max:100',
            ],

            'store_currency' => [
                'required',
                'string',
                'max:10',
            ],

            'store_description' => [
                'nullable',
                'string',
            ],

            'store_status' => [
                'required',
                'in:open,closed',
            ],

            'store_logo' => [
                'nullable',
                'string',
                'max:500',
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
                    'value' => $value,
                ]

            );
        }


        return redirect()
            ->back()
            ->with(
                'success',
                'Store settings updated successfully.'
            );
    }





}
