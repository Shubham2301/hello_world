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
        dd($request->all());
    }

    public function getFileUploadView(Request $request)
    {
        $referraltypeID =  $request->upload_referral_id;
        $referralFiles = ReferraltypesPatientfiletypes::getFilesForReferral($referraltypeID);
        $formView = view('patient.files_upload')->with('files', $referralFiles)->render();
        return $formView;
    }


    public function upload(Request $request)
    {
        $referralTypeID = $request->upload_referral_id;
		$referralFiles = ReferraltypesPatientfiletypes::getFilesForReferral($referralTypeID);
        $patientID = $request->upload_patient_id;
		$i = 0;
        foreach ($referralFiles as $referralFile) {
            if ($request->hasFile($referralFile->name)) {
                 $file = $request->file($referralFile->name);
                 $fileTypeID = PatientFileType::where('name', $referralFile->name)->first()->id;
                 $this->save($patientID, $file, $fileTypeID);
				$i++;
            }
        }
		$data = array();
		$data['count'] = $i;
		$data['id'] = $patientID;
		return json_encode($data);
    }

    public function save($patientID, $file, $fileTypeID)
    {
        $patientFile = new PatientFile;
        $fileName = rand(11111, 99999);
        $patientFile->name = $fileName;
        $patientFile->patientfiletype_id = $fileTypeID;
        $patientFile->patient_id = $patientID;
        $patientFile->treepath = '';
        $patientFile->extension = $file->getClientOriginalExtension();
        $patientFile->mimetype = $file->getClientMimeType();
        $patientFile->filesize = $file->getClientSize();
        $patientFile->status = 1;
        $patientFile->save();
        $parent_treepath = '9/patient_files'.'/'.$patientID;
        //echo $parent_treepath . '/' . $fileName . '.' .$patientFile->extension;
        Storage::put(
         $parent_treepath . '/' . $fileName . '.' .$patientFile->extension,
        file_get_contents($file->getRealPath())
    );
		return true;
    }
}
