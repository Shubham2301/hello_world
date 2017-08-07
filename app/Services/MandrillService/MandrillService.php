<?php 


namespace myocuhub\Services\MandrillService;

use Mandrill;

/**:
* Service for Mandrill API
*/

class MandrillService
{
	private $mandrill;

	function __construct()
	{
		$this->mandrill = new Mandrill(env('MANDRILL_SECRET'));
	}

	public function sendTemplate($attr){
		try {
		    $template_name = $attr['template'];
		    $template_content = [
		    ];
		    $message = [
		        'subject' => $attr['subject'],
		        'from_email' => $attr['from']['email'],
		        'from_name' => $attr['from']['name'],
		        'to' => [
		        		[
			        		'email' => $attr['to']['email'],
			                'name' => $attr['to']['name'],
			                'type' => 'to'
		                ]
	                ],
	            'merge_vars' => [
		            [
		                "rcpt" => $attr['to']['email'],
		                'vars' => $attr['vars']
		    		]
        		],
                'attachments' => $attr['attachments'],
		    ];
		    $async = false;
		    $result = $this->mandrill->messages->sendTemplate($template_name, $template_content, $message, $async);
		    return true;
		} catch(Mandrill_Error $e) {
		    Log::error($e);
		}
		return false;
	}

	public function templates($label = ''){
		try {
			$templates = $this->mandrill->templates->getList($label);
			$result = [];
			foreach ($templates as $template) {
				$result[$template['slug']] = [
						'name' => $template['name'],
						'labels' => $template['labels']
					];
			}
			return $result;
		} catch (Mandrill_Error $e) {
			Log::error($e);
		}
		return [];
	}

	public function templateInfo($name){
		try {
			return $this->mandrill->templates->info($name);
		} catch (Mandrill_Error $e) {
			Log::error($e);
		}
		return [];
	}
}
