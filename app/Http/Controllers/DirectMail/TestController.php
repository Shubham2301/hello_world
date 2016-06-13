<?php

namespace myocuhub\Http\Controllers\DirectMail;
use Illuminate\Http\Request;
use myocuhub\Http\Controllers\Controller;

class TestController extends Controller {

        public function test(Request $request) {
                $JAVA_HOME = getenv('JAVA_HOME');
                $PATH = "$JAVA_HOME/bin:".getenv('PATH');

                putenv("JAVA_HOME=$JAVA_HOME");
                putenv("PATH=$PATH");

                // $output = shell_exec("java -classpath D:\Rudresh\Projects\Eric\SESIntegration\bin;. TestMain");

                //      $output = shell_exec("java -classpath D:\Rudresh\Projects\Eric\SESIntegration\bin;C:\apache-cxf-3.1.6\lib\*.jar;C:\apache-cxf-3.1.6\lib\integration\*.jar;\"C:\Program Files\Java\jre1.8.0_51\lib\*.jar\";\"C:\Program Files\Java\jre1.8.0_51\lib\ext\*.jar\";. SESHelper unreadSesMailCount ehoell@direct.ocuhub.com");

                $output = shell_exec("java -classpath /usr/local/bin/bin:/usr/local/bin/lib/*: com.ocuhub.sesintegration.SESHelper unreadSesMailCount ehoell@direct.ocuhub.com");

                //exec("java -classpath /usr/local/bin/bin:/usr/local/bin/lib/*: com.ocuhub.sesintegration.SESHelper unreadSesMailCount ehoell@direct.ocuhub.com", $output);

                //var_dump($output);

                echo $output;
        }

}