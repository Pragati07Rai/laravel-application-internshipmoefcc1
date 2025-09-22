<?php
namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreApplicationRequest;
use Illuminate\Validation\ValidationException;

class ApplicationController extends Controller
{
    /**
     * Show the form for creating a new application.
     *
     * @return \Illuminate\View\View
     */
    public function showForm()
    {
        return view('application'); 
    }

    /**
     * Store a new applicant and their submitted documents.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
{
    dd($request->all(), $request->file('documents'));

    DB::beginTransaction();
    try {
        // Validate the form data
        $validatedData = $request->validate([
            // Applicant fields
            'year' => 'required|date_format:Y',
            'season' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:applicants,email',
            'phone' => 'required|string|max:20',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'category' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'pincode' => 'required|string|max:10',
            'institute' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'area_of_interest' => 'required|string|max:255',
            'educational_qualification' => 'required|string|max:255',
            'studying_at_present' => 'required|string|max:255',
            'presently_employed' => 'required|string|nullable',
            'work_experience' => 'required|string|nullable',
            'languages' => 'required|string|nullable',
            'achievements' => 'required|string',
            'sop' => 'required|string',

            // Document fields validation
            'photo_with_signature' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'resume' => 'required|file|mimes:pdf|max:2048',
            'marksheet' => 'required|file|mimes:pdf|max:2048',
            'supervisor_letter' => 'required|file|mimes:pdf|max:2048',
            'no_objection_certificate' => 'required|file|mimes:pdf|max:2048',
            'relevant_information' => 'nullable|file|mimes:pdf|max:2048',
        ]);
        dd($applicants);
       // create the applicant
        $applicants = applicant::create([
            'year' => $request->year,
            'season' =>$request->season,
            'first_name' => $request->first_name,
            'last_name' =>  $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'category' => $request->category,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'institute' => $request->institute,
            'department' => $request->department,
            'area_of_interest' => $request->area_of_interest,
            'educational_qualification' => $request->educational_qualification,
            'studying_at_present' => $request->studying_at_present,
            'presently_employed' => $request->presently_employed,
            'work_experience' => $request->work_experience,
            'languages' => $request->languages,
            'achievements' => $request->achievements,
        ]);

        $applicants =$request->user;
         if($request->hasfile('upload_file')){
            $a = $request->file('upload_file');
             foreach ($a as $file) {
                $path = $file->store('applicants', 'public');

                applicant::create([
                    'file_path' => $path,
                    'user_id' => Auth::id(),
                    "record_id" => $applicants->id,
                    'file_name' => $file->getClientOriginalName()
                ]);
         }
     }

            //  Save applicant (exclude file fields)
         $applicantsData = collect($validatedData)->except([
            'photo_with_signature',
            'resume',
            'marksheet',
            'supervisor_letter',
            'no_objection_certificate',
            'relevant_information',
         ])->toArray();

        //  Create a new Applicant record
        $applicants = Applicant::create($applicantsData);

        //  Process and save documents
        $uploadedFiles = [];
        $fileFields = [
            'photo_with_signature' => 'photo_with_signature',
            'resume' => 'resume',
            'marksheet' => 'marksheet',
            'supervisor_letter' => 'supervisor_letter',
            'no_objection_certificate' => 'no_objection_certificate',
            'relevant_information' => 'relevant_information',
        ];

        foreach ($fileFields as $documents => $fileType) {
            if ($request->hasFile($documents)) {
                $file = $request->file($documents);
                $path = $file->store('applicant_documents', 'public');
                $applicants->documents()->create;
                $applicants = new Applicant();
                $documents = new Document();
                $uploadedFiles[] = [
                    'applicant_id' => $applicant->id,
                    'file_type' => $fileType,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
              
            }
        }

        // 4. Save documents using the Document model
        if (!empty($uploadedFiles)) {
            Document::insert($uploadedFiles);
        }

        DB::commit();

        // Redirect to success page with applicant ID
        return redirect()->route('application.success', ['id' => $applicants->id]);

    } catch (ValidationException $e) {
        DB::rollBack();
        Log::error('Validation failed for application submission: ', $e->errors());
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Application submission failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'An unexpected error occurred during submission. Please try again.')->withInput();
    };
}
}