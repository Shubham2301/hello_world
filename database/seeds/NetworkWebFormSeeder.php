<?php

use Illuminate\Database\Seeder;
use myocuhub\Network;
use myocuhub\Models\WebFormTemplate;
use myocuhub\Models\NetworkWebForm;


class NetworkWebFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $templates = WebFormTemplate::all();
        $networkID = Network::where('name', 'LIKE', '%Test Network%')->first()->id;

        $arr = [
            'network_id' => $networkID
        ];

        foreach($templates as $template)
        {
            $arr['web_form_template_id'] = $template->id;

            NetworkWebForm::firstOrCreate($arr);
        }
    }
}
