<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use App\Models\Zoom;
use App\Models\Course;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ZoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Support\Collection
     */
    public function index($is_api=false)
    {
        if (Auth::user()->role == 'admin' || Auth::user()->role == 'instructor') {

            if(Auth::user()->jwt_token != '' && Auth::user()->zoom_email != '' && $is_api){
                return [];
            }

            if (Auth::user()->jwt_token != '' && Auth::user()->zoom_email != '')
            {
                $token = Auth::user()->jwt_token;
                $email = Auth::user()->zoom_email;
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.zoom.us/v2/users/$email",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "authorization: Bearer $token"
                    ),
                ));

                $profile = curl_exec($curl);
                $profile = json_decode($profile, true);

                $err = curl_error($curl);

                curl_close($curl);

                if (isset($profile['code']) && $profile['code'] != 200) {
                    // \Session::flash('delete', $profile['message']);
                    return redirect()->route('setting');
                }


                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.zoom.us/v2/users/" . Auth::user()->zoom_email . "/meetings?page_number=1&page_size=30&type=scheduled",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "authorization: Bearer $token"
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);



                $response = json_decode($response, true);

                if (isset($response['code']) && $response['code'] != 200) {
                    // \Session::flash('delete', $response['message']);
                    return redirect()->route('setting');
                }

                curl_close($curl);


                $itemCollection = [];
                if(!empty($response['meetings'])){
                    $itemCollection = collect($response['meetings']);
                    $itemCollection = $itemCollection->sortByDesc('created_at');
                }





                if ($err) {
                    dd($err);
                } else {
                    if ($is_api){
                        return $response['meetings'] ?? [];
                    }else{
                        return inertia('Zoom/List', [
                            'meetings' => $itemCollection,
                            'profile'  => $profile,
                            'filters'  => Request::only(['search', 'perPage']),
                            'url'      => URL::route('meetings.create'),
                            'main_url' => URL::route('meetings.index'),
                        ]);
                    }
                }
            }
            else {
                return redirect()->route('setting')->with('delete', 'Zoom Token or email not found !');
            }
        } else {
            return abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        if (Auth::User()->role == "admin") {
            $courses = Course::where('status', 'active')->get();
        } else {
            $courses = Course::where('status', 'active')->where('user_id', Auth::user()->id)->get();
        }

        return inertia('Zoom/Create', [
            'courses' => $courses,
            'url' => URL::route('meetings.index')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
//        return Request::all();
        Request::validate([
            'meeting_title' => 'required',
        ]);

        if (Request::hasFile('photo')) {
            $zoom_image = Request::file('photo')->store('image', 'public');
        } else {
            $zoom_image = NULL;
        }

        $meeting_title = Request::input('meeting_title');
        $agenda = Request::input('agenda');


        $email = Auth::user()->zoom_email;
        $token = Auth::user()->jwt_token;

        $timezone = config('app.timezone');

        $host_video = Request::input('host_video') ? true : false;
        $participant_video = Request::input('participant_video') ? true : false;
        $join_before_host = Request::input('join_before_host') ? true : false;
        $mute_upon_entry = Request::input('mute_upon_entry') ? true : false;
        $registrants_email_notification = Request::input('registrants_email_notification') ? true : false;

        $start_time = Carbon::today();
        $duration = 1;
        $type  = "1";
        $password  = "123456";
        /*      $json = [
                    "agenda"=> $agenda,
                    "default_password"=> false,
                    "duration"=> 60,
                    "password"=> "123456",
                    "pre_schedule"=> false,
                    "recurrence"=> [
                      "end_date_time"=> "2023-04-02T15:59:00Z",
                      "end_times"=> 7,
                      "monthly_day"=> 1,
                      "monthly_week"=> 1,
                      "monthly_week_day"=> 1,
                      "repeat_interval"=> 1,
                      "type"=> 1,
                      "weekly_days"=> "1"
                    ],
                    "settings"=> [
                      "additional_data_center_regions"=> [
                        "TY"
                      ],
                      "allow_multiple_devices"=> true,
                      "alternative_hosts_email_notification"=> true,
                      "approval_type"=> 2,
                      "approved_or_denied_countries_or_regions"=> [
                        "approved_list"=> [
                          "CX"
                        ],
                        "denied_list"=> [
                          "CA"
                        ],
                        "enable"=> true,
                        "method"=> "approve"
                      ],
                      "audio"=> "telephony",
                      "auto_recording"=> "cloud",
                      "calendar_type"=> 1,
                      "close_registration"=> false,
                      "cn_meeting"=> false,
                      "email_notification"=> true,
                      "encryption_type"=> "enhanced_encryption",
                      "focus_mode"=> true,
                      "host_video"=> true,
                      "in_meeting"=> false,
                      "jbh_time"=> 0,
                      "join_before_host"=> false,
                      "meeting_authentication"=> true,
                      "mute_upon_entry"=> false,
                      "participant_video"=> false,
                      "private_meeting"=> false,
                      "registrants_confirmation_email"=> true,
                      "registrants_email_notification"=> true,
                      "registration_type"=> 1,
                      "show_share_button"=> true,
                      "use_pmi"=> false,
                      "waiting_room"=> false,
                      "watermark"=> false,
                      "host_save_video_order"=> true,
                      "alternative_host_update_polls"=> true
                    ],
                    "start_time"=> $start_time,
                    "timezone"=> $timezone,
                    "topic"=> $meeting_title,
                    "type"=> 2
                  ];*/


        $json =[
            'topic'			=> $meeting_title ?? 'PHP General Talk',
            'type'			=> 2,
            'start_time'	=> date('Y-m-dTh:i:00').'Z',
            'duration'		=> $duration ?? 30,
            'password'		=> $password ?? mt_rand(),
            'timezone'		=> config('app.timezone'),
            'agenda'		=> $agenda ?? "Agenda",
            'settings'		=> [
                'host_video'			=> $host_video,
                'participant_video'		=> $participant_video,
                'cn_meeting'			=> false,
                'in_meeting'			=> false,
                'join_before_host'		=> $join_before_host,
                'mute_upon_entry'		=> $mute_upon_entry,
                'watermark'				=> false,
                'use_pmi'				=> false,
                'approval_type'			=> 1,
                'registration_type'		=> 1,
                'audio'					=> 'voip',
                'auto_recording'		=> 'none',
                'waiting_room'			=> false
            ]
        ];



        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zoom.us/v2/users/$email/meetings",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($json),
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer $token",
                "content-type: application/json",
                "accept: application/json" // fsms adding to get error in json
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        $response = json_decode($response, true);

        curl_close($curl);

        if (isset($response['code'])) {
            if ($response['code'] != 200) {
                dd($response);
                Log::debug('==ZOOM ERROR==: ' . print_r($response, TRUE));
                return redirect()->route('meetings.index');
            }
        }



        $utc = isset($response['start_time']) ? $response['start_time'] : NULL;
        $dt = new DateTime($utc);

        //fsms adding log
        Log::debug('timezone coming from Zoom  Meeting is: ' . $response['timezone']);



        $tz = new DateTimeZone($response['timezone']); // or whatever zone you're after
        $dt->setTimezone($tz);
        $meeting_time = $dt->format('Y-m-d H:i:s');
        $link_by = NULL;
        $course_id = Request::input('course_id') ?? NULL;

        if (isset($response['settings']['contact_email'])) {
            $owner_id = $response['settings']['contact_email'];
        } else {
            $owner_id = $response['host_email'];
        }


//        return $response;
//        exit();


        $created_meeting = Zoom::create(
            [
                'meeting_id' => $response['id'],
                'user_id' => Auth::user()->id,
                'owner_id' => $owner_id,
                'meeting_title' => $response['topic'],
                'start_time' => $meeting_time,
                'zoom_url' => $response['start_url'],
                'join_url' => $response['join_url'],
                'link_by' => $link_by,
                'course_id' => $course_id,
                'type' => $response['type'],
                'agenda' => $response['agenda'],
                'photo' => $zoom_image,
            ]
        );


        return redirect()->route('meetings.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Zoom  $zoom
     * @return \Illuminate\Http\Response
     */
    public function show(Zoom $zoom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Zoom  $zoom
     * @return \Illuminate\Http\Response
     */
    public function edit(Zoom $zoom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Zoom  $zoom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Zoom $zoom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Zoom  $zoom
     * @return \Illuminate\Http\Response
     */
    public function destroy($meetingId)
    {

        if (Auth::user()->role == 'admin' || Auth::user()->role == 'instructor') {
            if (Auth::user()->jwt_token != '' && Auth::user()->zoom_email != '') {
                $token = Auth::user()->jwt_token;
                $email = Auth::user()->zoom_email;
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.zoom.us/v2/users/$email",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "authorization: Bearer $token"
                    ),
                ));

                $profile = curl_exec($curl);
                $profile = json_decode($profile, true);

                $err = curl_error($curl);

                curl_close($curl);

                if (isset($profile['code']) && $profile['code'] != 200) {
                    // \Session::flash('delete', $profile['message']);
                    return redirect()->route('setting');
                }


                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.zoom.us/v2/meetings/$meetingId",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "DELETE",
                    CURLOPT_HTTPHEADER => array(
                        "authorization: Bearer $token"
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);
                $response = json_decode($response, true);

//                dd($response);

                if (isset($response['code']) && $response['code'] != 200) {
                    // \Session::flash('delete', $response['message']);
                    return redirect()->route('setting');
                }else{

                    return $response;

                    Zoom::where('meeting_id', $meetingId)->delete();
                }

                curl_close($curl);


            } else {
                return redirect()->route('setting')->with('delete', 'Zoom Token or email not found !');
            }
        } else {
            return abort(403, 'Unauthorized action.');
        }
    }
}
