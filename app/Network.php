<?php

namespace myocuhub;

use Illuminate\Database\Eloquent\Model;
use myocuhub\Models\MessageTemplate;

class Network extends Model {
	protected $fillable = ['id', 'name', 'email', 'phone', 'addressline1', 'addressline2', 'city', 'state', 'zip', 'country'];

	/**
	 * @return mixed
	 */
	public function practices() {
		// TODO : optimize
		return $this->hasMany('myocuhub\Models\PracticeNetwork')
		            ->leftJoin('practices', 'practice_network.practice_id', '=', 'practices.id')
		            ->orderBy('practices.name');
	}

    /**
     * @return mixed
     */
    public function goalNetwork()
    {
        return $this->hasMany('myocuhub\Models\GoalNetwork');
    }

	
	public static function practicesByName($search) {
		return self::where('networks.id', session('network-id'))
			->leftJoin('practice_network', 'networks.id', '=', 'practice_network.network_id')
			->leftJoin('practices', 'practice_network.practice_id', '=', 'practices.id')
			->where('practices.name', 'like', '%' . $search . '%')
			->orderBy('practices.name')
			->get();
	}

	/**
	 * @return mixed
	 */
	public function referralTypes() {
		// TODO : optimize
		return $this->hasMany('myocuhub\NetworkReferraltype')
		            ->leftJoin('referraltypes', 'network_referraltype.referraltype_id', '=', 'referraltypes.id');
	}
	/**
	 * @return mixed
	 */
	public function newReferralTypes() {
		// TODO : optimize
		return $this->hasMany('myocuhub\NetworkReferraltype')
		            ->leftJoin('referraltypes', 'network_referraltype.referraltype_id', '=', 'referraltypes.id');
	}
	/**
	 * @return mixed
	 */
	public function careconsoleStages() {
		return $this->hasMany('myocuhub\Models\NetworkStage')
		            ->leftJoin('careconsole_stages', 'network_stage.stage_id', '=', 'careconsole_stages.id')->orderBy('stage_order');
	}

	public static function getColumnNames() {
		$network = \Schema::getColumnListing('networks');
		$dummy_array = array_fill_keys(array_keys($network), null);
		return array_combine($network, $dummy_array);
	}

    public function webForms() {
        return $this->hasMany('myocuhub\Models\NetworkWebForm')
            ->leftJoin('web_form_templates', 'network_web_form.web_form_template_id', '=', 'web_form_templates.id')
            ->select('web_form_templates.id', 'web_form_templates.name', 'web_form_templates.display_name');
    }
}
