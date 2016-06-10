<?php

namespace myocuhub\Services\SES;

use myocuhub\Services\SES\SES;
use Illuminate\Support\Facades\Log;
use Validator;
use GuzzleHttp\Client;

/**
* Laravel Service Layer for Intetracting with the Java API Layer
*/
class SESMessaging extends SES
{

    public function __construct()
    {
    }

    /**
     * Check if input parameter is SES email ID. 
     *
     * @param  String  $email
     * @return Bool $type
     */

    private $rule = array(
            'email' => 'regex:/(.+)(@direct)(\.)(.+)/',
        );

    public function isDirectID($email)
    {
        $data = ['email' => $email];

        $validate = Validator::make($data, $this->rule);

        if (!$validate->fails()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Sends SES Direct Messagge via SES API
     */
    public static function send($attr)
    {
        $content = self::prepareContent($attr['view'], $attr['appt']);
        $attr['body'] = $content;

        try {

            $url = 'http://localhost/ocuhub-projectX/public/foo';
            $payload = [
                'form_params' => [
                    'query' => $attr
                ]
            ];
            $client = new Client();

			$response = $client->request('POST', $url, $payload);
            $body = $response->getBody();

            return $body->getContents();

        } catch (Exception $e) {
            Log::error($e);
            $action = 'Application Exception in sending Appointment Request email not sent to patient '. $location->email;
            $description = '';
            $filename = basename(__FILE__);
            $ip = '';
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

            return false;
        }
    }

    private static function prepareContent($view, $appt)
    {
        return view($view)->with('appt', $appt)->render();
    }
}
