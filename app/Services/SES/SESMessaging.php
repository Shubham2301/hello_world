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
        if (!$email) {
            return false;
        }
        $data = ['email' => $email];
        $validate = Validator::make($data, $this->rule);

        if (!$validate->fails()) {
            return true;
        }
        
        return false;
    }

    public static function getDirectID($userID)
    {
        $user = User::find($userID);
        
        if ($user) {
            return $user->sesemail;
        }

        return false;
    }

    /**
     * Sends SES Direct Messagge via SES API
     */
    public static function send($attr)
    {
        try {
            $content = self::prepareContent($attr['view'], $attr['appt']);
            $attr['body'] = $content;
            $attachments = json_encode($attr['attachments'], JSON_FORCE_OBJECT);

            //prepare arguments
            $args = [
                'action'        => 'sendMessage',
                'email_to'      =>  $attr['to']['email'],
                'subject'       =>  $attr['subject'],
                'body'          =>  addslashes($attr['body']),
                'attachments'   =>  $attachments,
            ];

            $output = self::javaServiceConnect($args);
            foreach ($attr['attachments'] as $path) {
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            return $output;
        } catch (Exception $e) {
            Log::error($e);
            $action = 'Application Exception in sending Appointment Request email not sent to patient '. $aatr['from']['email'];
            $description = '';
            $filename = basename(__FILE__);
            $ip = '';
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
            return false;
        }
    }

    public static function getUnreadCount($userID)
    {
        $directID = self::getDirectID($userID);
        
        if ($directID) {
            return self::javaServiceConnect([
                'unreadSesMailCount',
                $directID,
            ]);
        }

        return false;
    }

    public static function getJavaHome()
    {
        return getenv('JAVA_HOME');
    }

    public static function getPath()
    {
        return self::getJavaHome()."/bin:".getenv('PATH');
    }

    private static function javaServiceConnect($args)
    {
        try {
            $JAVA_HOME = self::getJavaHome();
            $PATH = self::getPath();

            putenv("JAVA_HOME=$JAVA_HOME");
            putenv("PATH=$PATH");
            $exec = "java -classpath ".env('JAVA_ADAPTER_CLASSPATH')." com.ocuhub.sesintegration.SESHelper";
        
            foreach ($args as $arg) {
                $exec = $exec.' '. $arg;
            }

            $output = shell_exec($exec);
            return $output;
        } catch (Exception $e) {
            Log::error($e);
            $action = 'Application Exception in java ses api ';
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
