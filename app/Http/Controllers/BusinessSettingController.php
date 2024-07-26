<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Advised;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\Country;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\Product;
use App\Models\SubCategory;
use Dotenv\Dotenv;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class BusinessSettingController extends Controller
{
    public function index()
    {
        return inertia('Setting', [
            'pages' => Page::query()->select('id', 'title')->get(),
            'bSettings' =>[
                's1Title'        => $this->get_setting('s1Title'),
                's1Slogan'     => $this->get_setting('s1Slogan'),

                's2Title'        => $this->get_setting('s2Title'),
                's2Slogan'     => $this->get_setting('s2Slogan'),

                's3Title'        => $this->get_setting('s3Title'),
                's3Slogan'     => $this->get_setting('s3Slogan'),

                's4Title'        => $this->get_setting('s4Title'),
                's4Slogan'     => $this->get_setting('s4Slogan'),

                'mstitle'        => $this->get_setting('mstitle'),
                'msbody'     => $this->get_setting('msbody'),

                'contactUs'     => $this->get_setting('contactUs'),

                'faqpagetitle'        => $this->get_setting('faqpagetitle'),
                'faqpageslogan'     => $this->get_setting('faqpageslogan'),

                'headerpages'     => json_decode($this->get_setting('headerpages')),
                'footerpages'     => json_decode($this->get_setting('footerpages')),

                'footerText'     => $this->get_setting('footerText'),
            ]
        ]);
    }

    public function updateSetting()
    {
        foreach (Request::all() as $type => $value){
            $business_settings = BusinessSetting::where('type', $type)->first();
            if($business_settings != null) {
                if ($value != null){
                    if ($type == 'timezone' && gettype($value) != 'array'){
                        $value = $business_settings->value;
                    }
                    if(gettype($value) == 'array'){
                        $business_settings->value = json_encode($value);
                    }
                    else {
                        $business_settings->value = $value;
                    }
                    $business_settings->save();
                }
            } else{
                if ($value != null){
                    $business_settings = new BusinessSetting;
                    $business_settings->type = $type;
                    if(gettype($value) == 'array'){
                        $business_settings->value = json_encode($value);
                    }
                    else {
                        $business_settings->value = $value;
                    }
                    $business_settings->save();
                }
            }
        }
        return redirect()->back();
    }

    public function get_setting($key, $default = null)
    {
        $setting = BusinessSetting::where('type', $key)->first();
        return $setting == null ? $default : $setting->value;
    }
}
