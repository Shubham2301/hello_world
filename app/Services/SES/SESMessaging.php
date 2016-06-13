<?php

namespace myocuhub\Services\SES;

use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Log;
use Validator;
use myocuhub\Services\SES\SES;

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
        }
        
        return false;
    }

    public static function getDirectID($userID){
        
        $user = User::find($userID);
        
        if($user){
            return $user->sesemail;
        }

        return false;
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

    public static function getUnreadCount($userID){ 
        
        $directID = self::getDirectID($userID);
        
        if ($directID) {
            return self::javaServiceConnect([
                'unreadSesMailCount',
                $directID,
            ]);
        }

        return false;
    }

    public static function getJavaHome(){
        return getenv('JAVA_HOME');
    }

    public static function getPath(){
        return "$JAVA_HOME/bin:".getenv('PATH');
    }

    private static function javaServiceConnect($args){
        
        $JAVA_HOME = self::getJavaHome();
        $PATH = self::getPath();

        putenv("JAVA_HOME=$JAVA_HOME");
        putenv("PATH=$PATH");

        $exec = "java -classpath /usr/local/bin/bin:/usr/local/bin/lib/*: com.ocuhub.sesintegration.SESHelper ";
        
        foreach ($args as $arg) {
            $exec =. ' ' . $arg;
        }

        $output = shell_exec($exec);
    }

    private static function prepareContent($view, $appt)
    {
        return view($view)->with('appt', $appt)->render();
    }
}
