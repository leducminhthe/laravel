<?php
namespace App\Traits;

use App\Models\Config;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Log;

/**
 * trait ZoomMeetingTrait
 */
trait TeamsMeetingTrait
{
    public $client;
    public $jwt;
    public $headers;
    protected $tenant_id;

    protected function getAccessToken()
    {
        $url = 'https://login.microsoftonline.com/' . config('app.azure.tenant_id') . '/oauth2/v2.0/token';
        $data = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'form_params'=>[
                'client_id' => config('app.azure.client_id'),
                'client_secret' => config('app.azure.client_secret'),
                'scope' => 'openid profile offline_access .default',
                'redirect_url' => config('app.azure.redirect'),
                'grant_type' => 'client_credentials'
            ],
            'verify' => false
        ];
        $client = new Client();
        $response = $client->post($url,$data);
        return json_decode($response->getBody())->access_token;
//        $response = Http::withoutVerifying()->post($url, $data);
//        return $response->json()['access_token'];
    }
    public function getTenantId()
    {
        return config('app.azure.tenant_id');
    }

    public function getUserId()
    {
        $email = \Auth::user()->email;
        if ($email) {
            $url = $this->apiTeamsUrl() . "/users/" . $email;
            $body = [
                'headers' => $this->getHeader(),
                'body' => json_encode([]),
                'verify' => false
            ];
            $client = new Client();
            try {
                $response = $client->get($url, $body);
                if ($response->getStatusCode() == 200) {
                    $data = json_decode($response->getBody());
                    return $data->id;
                }
            } catch (RequestException $e) {
//                dd($e->getResponse()->getStatusCode());
            }
        }
        return config('app.azure.user_id');
    }

    public function getHeader()
    {
        $jwt = $this->refreshToken();
//        $jwt = $this->getAccessToken();
        return [
            'Authorization' => 'Bearer '.$jwt,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ];
    }
    public function getHeader2()
    {
        $jwt = $this->getAccessToken();
//        $jwt = $this->generateTeamsToken();
        return [
            'Authorization' => 'Bearer '.$jwt,
            'Content-Type'  => 'application/json',
            'Prefer'              =>'outlook.timezone="Asia/Ho_Chi_Minh"'
        ];
    }
    public function generateTeamsToken()
    {
        $key = config('app.azure.client_id');
        $secret = config('app.azure.client_secret');
        $payload = [
            'iss' => $key,
            'exp' => strtotime('+1 minute'),
        ];

        return \Firebase\JWT\JWT::encode($payload, $secret, 'HS256');
    }

    private function apiTeamsUrl()
    {
        return config('app.azure.api_url');
    }
    private function apiBeta(){
        return 'https://graph.microsoft.com/beta';
    }

    public function createTeams($data)
    {
        $url = $this->apiTeamsUrl()."/users/".$this->getUserId()."/onlineMeetings";
        $dateConvert = datetime_convert($data['start_time']);
        $startDateTime = date('Y-m-d\TH:i:s.u\Z', strtotime($dateConvert));
        $endDateTime = date('Y-m-d\TH:i:s.u\Z', strtotime(datetime_convert($data['start_time']) .' + '. $data['duration'].' minute'));
        $body = [
            'headers' => $this->getHeader(),
            'body'    => json_encode([
                'subject'      => $data['subject'],
                'startDateTime' => $startDateTime, //Carbon::parse(datetime_convert($data['start_time']))->toIso8601String(),
                'endDateTime'   => $endDateTime,  //Carbon::parse(strtotime(datetime_convert($data['start_time']) .' + '. $data['duration'].' minute'))->toIso8601String(),
                'allowedPresenters'     => 'RoleIsPresenter',
                'participants'     => [
                    'organizer' => ['identity'=>['user'=>['id'=>$this->getUserId()]]],
                    'attendees' => [['upn'=>'thanhphong@itechco9999.onmicrosoft.com','role'=>'presenter'],]
                ],
            ]),
            'verify' => false
        ];
        $client = new Client();
        $response =  $client->post($url, $body);
        return [
            'success' => $response->getStatusCode() === 201,
            'data'    => json_decode($response->getBody()),
        ];

    }
    public function createTeamsMe($data)
    {
        $url = $this->apiTeamsUrl()."/me/onlineMeetings";
        $dateConvert = datetime_convert($data['start_time']);
        $startDateTime = date('Y-m-d\TH:i:s.u\Z', strtotime($dateConvert));
        $endDateTime = date('Y-m-d\TH:i:s.u\Z', strtotime(datetime_convert($data['start_time']) .' + '. $data['duration'].' minute'));
        $body = [
            'headers' => $this->getHeader(),
            'body'    => json_encode([
                'subject'      => $data['subject'],
                'startDateTime' => $startDateTime, //Carbon::parse(datetime_convert($data['start_time']))->toIso8601String(),
                'endDateTime'   => $endDateTime,  //Carbon::parse(strtotime(datetime_convert($data['start_time']) .' + '. $data['duration'].' minute'))->toIso8601String(),
                'allowedPresenters'     => 'RoleIsPresenter'
            ]),
            'verify' => false
        ];
        $client = new Client();
        $response =  $client->post($url, $body);
        return [
            'success' => $response->getStatusCode() === 201,
            'data'    => json_decode($response->getBody()),
        ];

    }
    public function updateTeams($id, $data)
    {
        $this->deleteTeams($id);
        return $this->createTeams($data);
        /*$url = $this->apiTeamsUrl()."/users/".$this->getUserId()."/onlineMeetings/".$id;
        $dateConvert = datetime_convert($data['start_time']);
        $startDateTime = date('Y-m-d\TH:i:s.u\Z', strtotime($dateConvert));
        $endDateTime = date('Y-m-d\TH:i:s.u\Z', strtotime(datetime_convert($data['start_time']) .' + '. $data['duration'].' minute'));
        $body = [
            'headers' => $this->getHeader(),
            'body'    => json_encode([
                'subject'      => $data['subject'],
                'startDateTime' => $startDateTime,
                'endDateTime'   => $endDateTime,
                'allowedPresenters'     => 'RoleIsPresenter',
                'participants'     => [
                    'organizer' => ['identity'=>['user'=>['id'=>$this->getUserId()]]],
                    'attendees' => [['upn'=>'thanhphong3@itechco9999.onmicrosoft.com','role'=>'presenter'],]
                ],
            ]),
            'verify' => false
        ];
        $client = new Client();
        $response =  $client->patch($url, $body);
        return [
            'success' => $response->getStatusCode() === 204,
            'data'    => json_decode($response->getBody(), true),
        ];*/
    }
    public function updateTeamsMe($id, $data)
    {
        $this->deleteTeamsMe($id);
        return $this->createTeamsMe($data);
    }
    public function getTeams($id)
    {
        $url = $this->apiTeamsUrl()."/users/".$this->getUserId()."/onlineMeetings";
        $path = 'meetings/'.$id;
        $this->jwt = $this->generateZoomToken();
        $body = [
            'headers' => $this->headers,
            'body'    => json_encode([]),
            'verify' => false
        ];

        $response =  $this->client->get($url.$path, $body);

        return [
            'success' => $response->getStatusCode() === 204,
            'data'    => json_decode($response->getBody(), true),
        ];
    }

    /*public function getParticipants($meeting_id)
    {
        $path = 'metrics/meetings/'.$meeting_id.'/participants';
        $url = $this->retrieveZoomUrl().$path;
        $body = [
            'headers' => $this->headers,
            'body'    => json_encode([]),
            'verify' => false
        ];
        $response =  $this->client->get($url, $body);

        $data = json_decode($response->getBody()); dd($data);
        if ( !empty($data) ) {
            foreach ( $data->participants as $p ) {
                $name = $p->name;
                $email = $p->user_email;
                echo "Name: $name";
                echo "Email: $email";
            }
        }
    }*/
    /**
     * @param string $id
     *
     * @return bool[]
     */
    public function deleteTeams($id)
    {
        $url = $this->apiTeamsUrl()."/users/".$this->getUserId()."/onlineMeetings/".$id;
        $body = [
            'headers' => $this->getHeader(),
            'body'    => json_encode([]),
            'verify' => false
        ];
        $client = new Client();
        $response =  $client->delete($url, $body);
        return [
            'success' => $response->getStatusCode() === 204,
        ];
    }
    public function deleteTeamsMe($id)
    {
        $url = $this->apiTeamsUrl()."/me/onlineMeetings/".$id;
        $body = [
            'headers' => $this->getHeader(),
            'body'    => json_encode([]),
            'verify' => false
        ];
        $client = new Client();
        $response =  $client->delete($url, $body);
        return [
            'success' => $response->getStatusCode() === 204,
        ];
    }
    public function getReport($teams_id,$user_teams_id)
    {
        try {
            $user_teams_id = $user_teams_id??$this->getUserId();
            $url = $this->apiBeta()."/users/".$user_teams_id."/onlineMeetings/".$teams_id.'/meetingAttendanceReport?$expand=attendanceRecords';
            $body = [
                'headers' => $this->getHeader(),
                'body'    => json_encode([]),
                'verify' => false
            ];
            $client = new Client();
            $response =  $client->get($url, $body);
            return json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            return false;
        }
    }
    public function getReportMe($teams_id,$report_id,$user_teams_id)
    {
        try {
            $user_teams_id = $user_teams_id??$this->getUserId();
            $url = $this->apiBeta()."/me/onlineMeetings/".$teams_id.'/attendanceReports/'.$report_id.'?$expand=attendanceRecords';
            $body = [
                'headers' => $this->getHeader(),
                'body'    => json_encode([]),
                'verify' => false
            ];
            $client = new Client();
            $response =  $client->get($url, $body);
            return json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            return false;
        }
    }
    public function createEvent($data)
    {
        $user_teams_id = $this->getUserId();
        $url = $this->apiTeamsUrl()."/users/".$user_teams_id."/calendar/events";
        $startDate = datetime_convert($data['start_time']);
        $endDate = datetime_convert($data['end_time']);
        $startDateTime = date('Y-m-d\TH:i:s', strtotime($startDate));
        $endDateTime = date('Y-m-d\TH:i:s', strtotime($endDate));

        $content = json_encode([
            'subject'=> $data['subject'],
            'body'=>[
                'contentType'=>'html',
                "content"=> $data['subject']
            ],
            'start'=>[
                'dateTime'=> $startDateTime,
                "timeZone"=>"Asia/Ho_Chi_Minh"
            ],
            'end'=>[
                "dateTime"=> $endDateTime,
                "timeZone"=>"Asia/Ho_Chi_Minh"
            ],
            "attendees"=> [
                (object)[
                    "emailAddress"=>[
                        "address"=>"minhthe@digitaltrainingvietnamllc.onmicrosoft.com",
                        "name"=> "Minh The"
                    ],
                    "type"=> "required"
                ]
            ],
            "Organizer"=> [
                "EmailAddress"=> [
                    "Address"=> "elearning@DIGITALTRAININGVIETNAMLLC.onmicrosoft.com",
                    "Name"=> "Phi Hung"
                ]
            ],
            "allowNewTimeProposals"=> true,
            "isOnlineMeeting"=> true,
            "onlineMeetingProvider"=> "TeamsForBusiness",
        ]);
        $body = [
            'headers' => $this->getHeader2(),
            'body'    => $content,
            'verify' => false
        ];

        $client = new Client();
        $response =  $client->post($url, $body);
        if ($response->getStatusCode() === 201){
            $event  = json_decode($response->getBody());
            $result = $this->getTeamsQueryString($event->onlineMeeting->joinUrl,$user_teams_id);
            return [
                'success' => true,
                'data'    => $result['data']->value[0],
                'event_id'=> $event->id,
                'user_teams_id'=> $user_teams_id
            ];
        }
        return [
            'success' => false,
            'data'    => json_decode($response->getBody()),
        ];

    }

    public function updateEvent($id,$user_teams_id,$data)
    {
        $user_teams_id = $user_teams_id??$this->getUserId();
        $url = $this->apiTeamsUrl()."/users/".$user_teams_id."/events/".$id ;
        $startDate = datetime_convert($data['start_time']);
        $endDate = datetime_convert($data['end_time']);
        $startDateTime = date('Y-m-d\TH:i:s', strtotime($startDate));
        $endDateTime = date('Y-m-d\TH:i:s', strtotime($endDate));
        $content = json_encode([
            'subject'=> $data['subject'],
            'start'=>[
                'dateTime'=> $startDateTime,
                "timeZone"=>"Asia/Ho_Chi_Minh"
            ],
            'end'=>[
                "dateTime"=> $endDateTime,
                "timeZone"=>"Asia/Ho_Chi_Minh"
            ],
            "Organizer"=> [
                "EmailAddress"=> [
                    "Address"=> "elearning@DIGITALTRAININGVIETNAMLLC.onmicrosoft.com",
                    "Name"=> "Phi Hung"
                ]
            ],
            "allowNewTimeProposals"=> true,
            "isOnlineMeeting"=> true,
            "onlineMeetingProvider"=> "TeamsForBusiness",
        ]);
        $body = [
            'headers' => $this->getHeader2(),
            'body'    => $content,
            'verify' => false
        ];
        $client = new Client();
        $response =  $client->patch($url, $body);
        if ($response->getStatusCode() === 200){
            $event  = json_decode($response->getBody());
            $result = $this->getTeamsQueryString($event->onlineMeeting->joinUrl,$user_teams_id);
            return [
                'success' => true,
                'data'    => $result['data']->value[0],
                'event_id'=> $event->id
            ];
        }
        return [
            'success' => false,
            'data'    => json_decode($response->getBody()),
        ];
    }
    public function deleteEvent($id, $user_teams_id)
    {
        $user_teams_id = $user_teams_id??$this->getUserId();
        $url = $this->apiTeamsUrl()."/users/".$user_teams_id."/events/".$id;
        $body = [
            'headers' => $this->getHeader(),
            'body'    => json_encode([]),
            'verify' => false
        ];
        $client = new Client();
        $response =  $client->delete($url, $body);
        return [
            'success' => $response->getStatusCode() === 204,
        ];
    }
    public function getTeamsQueryString($joinUrl,$user_teams_id)
    {
        $user_teams_id = $user_teams_id??$this->getUserId();
        $url = $this->apiTeamsUrl()."/users/".$user_teams_id.'/onlineMeetings?$filter=joinWebUrl eq '."'$joinUrl'";
        $body = [
            'headers' => $this->getHeader(),
            'body'    => json_encode([]),
            'verify' => false
        ];
        $client = new Client();
        $response =  $client->get($url, $body);
        return [
            'success' => $response->getStatusCode() === 204,
            'data'    => json_decode($response->getBody()),
        ];
    }
    protected function refreshToken()
    {
        $refresh_token = \DB::table('el_config')->where('name','refresh_token')->value('value');
        $url = 'https://login.microsoftonline.com/' . config('app.azure.tenant_id') . '/oauth2/v2.0/token';
        $data = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'form_params'=>[
                'client_id' => config('app.azure.client_id'),
                'client_secret' => config('app.azure.client_secret'),
                'scope' => 'user.read',
                'grant_type' => 'refresh_token',
                'refresh_token'=>$refresh_token
            ],
            'verify' => false
        ];
        $client = new Client();
        $response = $client->post($url,$data);
        return json_decode($response->getBody())->access_token;
    }
    protected function getAccessTokenAuthorizationCode()
    {
        $url = 'https://login.microsoftonline.com/' . config('app.azure.tenant_id') . '/oauth2/v2.0/token';
        $data = [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'form_params'=>[
                'client_id' => config('app.azure.client_id'),
                'client_secret' => config('app.azure.client_secret'),
                'scope' => 'https://graph.microsoft.com/User.Read',
                'redirect_url' => config('app.azure.redirect'),
                'grant_type' => 'authorization_code',
                'code'=>'0.AVQAdEU_haF16kqJoaw8yBYq41gEQBvR8tdFky5h6_PIOBZUAAA.AgABAAIAAAD--DLA3VO7QrddgJg7WevrAgDs_wQA9P8P6y4ZJwevRIfVq7LmkZ5C8t4jRWQlmwd_6dZBc3wYijflZs7vvBOU8bUCj8NauDLXz3A_sVgXvC43mvdnOcTiZTNGIEECUp1f5FPvv7Dd22IUcxMgk9pGBHzIaFixjZQjtYDqRo3HpLXg4cpsBoQadYiQR2UUleiCuYs06JtcCKvafRlDmDFt1tV6ISLdobG6lnEDLRWrOUHxfuL302A26JzryOwFxFH-btZ49jBe9_Y7dR2ubIK0hN1lRHOHx6vo8ZPfd2HZIMdY7cx5-RJfh03EUQOSgJfKJ7kx7jETMi7Z8gKWJh50T-Nx0MNmS085nmitQE9Z-KIKWQRuSYGH-HftLyq5YFB6gW4Iet4WSsBPPYVusbg68xWizhb7-obbsyJowjqJons8nl7uZlKPZcJb7DWc9LqD6JaOUaTmzmSKKx2MaD8SVV1s4zhXhzye5n5kE5Q9GxTSpvGBghrTb6Ub97dbVIujT9B0TZey1hfU9COT8sNyKOELP-TyAbSnQT1Em02jLWzXU0OMITYRx9GlqzHJdhGWK8c4qKoTcTnaLrTKwZJ7CXU8o4fI9N9MQ7MesU_0n8jYzH4Y30Q8wv8Bn4V70sA2Xc6xSLP2MH6xpgcXw73-ls7bD9ZgBl9yOJbCVDDY5M8yCxCfmURKpeg'
            ],
            'verify' => false
        ];
        $client = new Client();
        $response = $client->post($url,$data);
        $data = json_decode($response->getBody());
//        Config::updateOrCreate(['name'=>'refresh_token'],['value'=>$data->refresh_token]);
        return $data->access_token;
    }

    public function getAttendanceReportsMe($teams_id)
    {
        try {
//            $user_teams_id = $user_teams_id??$this->getUserId();
            $url = $this->apiBeta()."/me/onlineMeetings/".$teams_id.'/attendanceReports';
            $body = [
                'headers' => $this->getHeader(),
                'body'    => json_encode([]),
                'verify' => false
            ];
            $client = new Client();
            $response =  $client->get($url, $body);
            return json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            return false;
        }
    }
}
