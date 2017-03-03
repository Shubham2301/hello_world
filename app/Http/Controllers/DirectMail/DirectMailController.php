<?php

namespace myocuhub\Http\Controllers\DirectMail;

use Auth;
use Event;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Http\Controllers\Traits\EncryptTimestamp;
use myocuhub\Models\ImpersonationAudit;
use myocuhub\Services\SES\SESConnect;
use myocuhub\User;

class DirectMailController extends Controller
{
    use EncryptTimestamp;

    private $sesConnect;

    public function __construct(SESConnect $sesConnect)
    {
        $this->sesConnect = $sesConnect;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (session('impersonation-logged-in') != '' && session('impersonation-logged-in') != Auth::user()->id && session('impersonation-logged-in-time') != '' && session('impersonation-logged-in-time') != Auth::user()->last_login_time) {
            $audit = new ImpersonationAudit;

            $audit->user_impersonated_id = session('impersonation-id');
            $audit->logged_in_user_id = Auth::user()->id;
            $audit->action = 'END IMPERSONATION';

            session(['impersonation-logged-in-time' => '']);
            session(['impersonation-logged-in' => '']);
            session(['impersonation-id' => '']);
            session(['impersonation-name' => '']);

            $audit->save();
        } else {
            $action = 'Accessed Directmail';
            $description = '';
            $filename = basename(__FILE__);
            $ip = $request->getClientIp();

            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));
        }

        $ses = $this->sesConnect->getDirectMail();

        $user = User::find(session('impersonation-id'));
        if (!$user) {
            $user = Auth::user();
        }

        $data = array();
        $data['direct-mail'] = true;
        $data['ocuhub-idp'] = config('ses.ocuhub-idp');
        $data['timestamp'] = $this->encrypt(microtime(true));
        $data['email'] = $this->encrypt($user->email);

        if (!$ses['direct_mail_str']) {
            $request->session()->flash('no_direct_mail', 'You do not have a direct mail access. If you feel this is in error, please contact the OcuHub administrator for assistance.');
        }

        $impersonation = $this->sesConnect->getImpersonationScope();

        /**
         * Disable SES
         */
        if (env('SES_MODE') == 'disable') {
            $request->session()->flash('disable_directmail', 'SES Directmail is temporarily down. We are working out to resolve the issue.');
        }

        return view('directmail.index')->with('ses', $ses)
            ->with('data', $data)
            ->with('impersonation', $impersonation);

    }

    public function beginImpersonate(Request $request)
    {

        if (!$this->sesConnect->checkScope($request->impersonateuser)) {
            $request->session()->flash('no_direct_mail', 'You do not have access rights to impersonate this user.');
        } else {
            $audit = new ImpersonationAudit;

            session(['impersonation-id' => $request->impersonateuser]);
            session(['impersonation-logged-in-time' => Auth::user()->last_login_time]);
            session(['impersonation-logged-in' => Auth::user()->id]);
            session(['impersonation-name' => User::find($request->impersonateuser)->name]);

            $audit->user_impersonated_id = $request->impersonateuser;
            $audit->logged_in_user_id = Auth::user()->id;
            $audit->action = 'BEGIN IMPERSONATION';

            $audit->save();
        }

        return redirect('directmail');

    }

    public function endImpersonate()
    {
        $audit = new ImpersonationAudit;

        $audit->user_impersonated_id = session('impersonation-id');
        $audit->logged_in_user_id = Auth::user()->id;
        $audit->action = 'END IMPERSONATION';

        session(['impersonation-logged-in-time' => '']);
        session(['impersonation-logged-in' => '']);
        session(['impersonation-id' => '']);
        session(['impersonation-name' => '']);

        $audit->save();

        return redirect('directmail');
    }

    public function logClientError(Request $request)
    {

        try {
            $user = Auth::user();
            if ($user == null) {
                return json_encode(['result' => 'Invalid User']);
            }

            if (Auth::user()->id != 62) {
                return json_encode(['result' => 'Invalid User']);
            }

            $report = $request->report;
            $context = $request->context;
            File::append(base_path() . '/storage/logs/ses-client.log', "\n" . $context . $report);

            return json_encode(['result' => 'Error Logged']);

        } catch (Exception $e) {
            Log::error($e);
            return json_encode(['result' => 'Exception in logging error. Ouch!!']);
        }

    }
}
