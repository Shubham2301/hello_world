<?php

namespace myocuhub\Http\Controllers\Patient;

use Auth;
use DateTime;
use Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Careconsole;
use myocuhub\Models\Ccda;
use myocuhub\Models\ImportHistory;
use myocuhub\Models\PatientFile;
use myocuhub\Models\PatientFileType;
use myocuhub\Models\PatientInsurance;
use myocuhub\Models\Practice;
use myocuhub\Models\PracticePatient;
use myocuhub\Models\PracticeUser;
use myocuhub\Models\ReferralHistory;
use myocuhub\Models\ReferraltypesPatientfiletypes;
use myocuhub\Patient;
use myocuhub\User;

class PatientFilesController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function index(Request $request)
	{
		return;
	}

	public function upload(Request $request)
	{
		$patientID = $request->upload_patient_id;
		$files = $request->all();
		$count = $request->count_patient_file;
		$data = [];
		$j =0;
		for($i=1; $i<= $count; $i++ )
		{
			$data[$j]['name'] = $request->input('patient_file_name_'.$i);
			$data[$j]['file'] = $request->file('patient_file_'.$i);
			$this->save($patientID, $data[$j]);
			$j++;
		}

		$data = array();
		$data['count'] = $j;
		$data['id'] = $patientID;
		return json_encode($data);
	}

	public function save($patientID, $data)
	{
		$networkID = session('network-id');
		$file = $data['file'];
		$patientFile = new PatientFile;
		$patientFile->display_name = $data['name'];
		$patientFile->name = rand();
		$patientFile->patient_id = $patientID;
		$patientFile->treepath = '';
		$patientFile->extension = $file->getClientOriginalExtension();
		$patientFile->mimetype = $file->getClientMimeType();
		$patientFile->filesize = $file->getClientSize();
		$patientFile->treepath = $networkID.'/patient_files'.'/'.$patientID;
		$patientFile->status = 1;
		$patientFile->save();

		Storage::put(
			$patientFile->treepath . '/' . $patientFile->name . '.' .$patientFile->extension,
			file_get_contents($file->getRealPath())
		);
		return true;
	}

	public function downloadFile($id){
		$file = PatientFile::find($id);
		$downloadFile = Storage::get($file->treepath . '/' . $file->name . '.' . $file->extension);
		return response($downloadFile, 200)
			->header('Content-Type', $file->mimetype)
			->header("Content-Disposition", "attachment; filename=\"" . $file->display_name . '.' . $file->extension . "\"");
	}
}
