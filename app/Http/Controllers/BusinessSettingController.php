<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Advised;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\Country;
use App\Models\Course;
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
            'data' => [
              'pages' => Page::query()->select('id', 'title')->get(),
              'categories' => Category::query()->select(['id', 'name'])->get(),
              'course' => Course::query()->select(['id', 'name'])->get()
            ],

            'bSettings' => [
                'headerCats' => json_decode($this->get_setting('headerCats')),
                'heroCats' => json_decode($this->get_setting('heroCats')),

                'heroCourses' => json_decode($this->get_setting('heroCourses')),
                'homeCourses' => json_decode($this->get_setting('homeCourses')),
                'topForCats' => json_decode($this->get_setting('topForCats')),

                'secondSecond' => json_decode($this->get_setting('secondSecond')),


                's3Slogan' => $this->get_setting('s3Slogan'),

                's4Title' => $this->get_setting('s4Title'),
                's4Slogan' => $this->get_setting('s4Slogan'),

                'mstitle' => $this->get_setting('mstitle'),
                'msbody' => $this->get_setting('msbody'),

                'contactUs' => $this->get_setting('contactUs'),

                'faqpagetitle' => $this->get_setting('faqpagetitle'),
                'faqpageslogan' => $this->get_setting('faqpageslogan'),

                'headerpages' => json_decode($this->get_setting('headerpages')),
                'footerpages' => json_decode($this->get_setting('footerpages')),

                'footerText' => $this->get_setting('footerText'),
            ]
        ]);
    }

    public function updateSetting()
    {
        foreach (Request::all() as $type => $value) {
            $business_settings = BusinessSetting::where('type', $type)->first();
            if ($business_settings != null) {
                if ($value != null) {
                    if ($type == 'timezone' && gettype($value) != 'array') {
                        $value = $business_settings->value;
                    }
                    if (gettype($value) == 'array') {
                        $business_settings->value = json_encode($value);
                    } else {
                        $business_settings->value = $value;
                    }
                    $business_settings->save();
                }
            } else {
                if ($value != null) {
                    $business_settings = new BusinessSetting;
                    $business_settings->type = $type;
                    if (gettype($value) == 'array') {
                        $business_settings->value = json_encode($value);
                    } else {
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
