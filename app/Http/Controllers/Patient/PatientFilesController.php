<?php

namespace myocuhub\Http\Controllers\Patient;

use Auth;
use DateTime;
use Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Http\Controllers\Controller;
use myocuhub\Models\Ccda;
use myocuhub\Models\PatientFile;
use myocuhub\Patient;
use MyCCDA;
use myocuhub\Http\Controllers\Traits\CCDATrait;

class PatientFilesController extends Controller
{
    use CCDATrait;
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
        $count = $request->count_patient_file;
        $isCCDA = false;
        $ccdaAsJson = null;
        $data = [];
        $j =0;
        for ($i=1; $i<= $count; $i++) {
            $data[$j]['name'] = $request->input('patient_file_name_'.$i);
            $data[$j]['file'] = $request->file('patient_file_'.$i);

            if ($request->hasFile('patient_file_'.$i)) {
                $ccdaAsJson = MyCCDA::isCCDA($data[$j]['file']);
                if ($ccdaAsJson) {
                    $isCCDA = true;
                    continue;
                }
                $this->saveFile($patientID, $data[$j]);
                $j++;
            }
        }
        $ccdaData = null;
        if ($isCCDA) {
            $ccdaData = $this->saveCCDA($request, $ccdaAsJson, $patientID);
        }

        $data = array();
        $data['count'] = $j;
        $data['id'] = $patientID;
        $data['ccda'] = $isCCDA;
        $data['ccdaData'] = $ccdaData;

        return json_encode($data);
    }

    public function saveFile($patientID, $data)
    {
        try {
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
        } catch (Exception $e) {
            Log::error($e);
            $action = 'Application Exception in uploading files for patient id '. $patientID;
            $description = '';
            $filename = basename(__FILE__);
            $ip = '';
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

            return false;
        }
    }

    public function downloadFile($id)
    {
        $file = PatientFile::find($id);
        $downloadFile = Storage::get($file->treepath . '/' . $file->name . '.' . $file->extension);
        return response($downloadFile, 200)
            ->header('Content-Type', $file->mimetype)
            ->header("Content-Disposition", "attachment; filename=\"" . $file->display_name . '.' . $file->extension . "\"");
    }
}
