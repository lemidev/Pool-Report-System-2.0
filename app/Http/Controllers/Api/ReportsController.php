<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Report;

use Validator;
use DB;
use Carbon\Carbon;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Notifications\ReportCreatedNotification;
use App\PRS\Transformers\ReportTransformer;
use App\PRS\Helpers\ReportHelpers;

class ReportsController extends ApiController
{

    protected $reportTransformer;
    protected $reportHelpers;

    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct(ReportTransformer $reportTransformer, ReportHelpers $reportHelpers)
    {
        $this->reportTransformer = $reportTransformer;
        $this->reportHelpers = $reportHelpers;
    }

    /**
     * Display a listing of the resource.
     * tested
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($this->getUser()->cannot('index', Report::class))
        {
            return $this->setStatusCode(403)->respondWithError('You don\'t have permission to access this. The administrator can grant you permission');
        }

        if(isset($request->date)){
            return $this->indexByDate($request->date);
        }

        $this->validate($request, [
            'limit' => 'integer|between:1,25'
        ]);

        $limit = ($request->limit)?: 5;
        $reports = $this->loggedUserAdministrator()->reportsInOrder()->paginate($limit);

        return $this->respondWithPagination(
            $reports,
            $this->reportTransformer->transformCollection($reports)
        );
    }

    /**
     * Get the reports by date
     * tested
     * @param  String $date format YYYY-MM-DD the timezone may not be UTC
     * @return $reports
     */
    public function indexByDate(String $date_str)
    {
        if($this->getUser()->cannot('index', Report::class))
        {
            return $this->setStatusCode(403)->respondWithError('You don\'t have permission to access this. The administrator can grant you permission');
        }

        if(!validateDate($date_str))
        {
            return $this->setStatusCode(422)->RespondWithError('The date is invalid');
        }

        $admin = $this->loggedUserAdministrator();

        $date = (new Carbon($date_str, $admin->timezone))->setTimezone('UTC');

        // Needs pagination
        $reports = $admin->reportsByDate($date)->get();

        return $this->respond([
            'data' => $this->reportTransformer->transformCollection($reports),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * tested
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // check that user has permission
        if($this->getUser()->cannot('create', Report::class))
        {
            return $this->setStatusCode(403)->respondWithError('You don\'t have permission to access this. The administrator can grant you permission');
        }

        $admin = $this->loggedUserAdministrator();

        // Validate
            $this->validateReportCreate($request);
            // validate and get the service
            try {
                $service = $admin->serviceBySeqId($request->service_id);
            }catch(ModelNotFoundException $e){
                return $this->respondNotFound('Service with that id, does not exist.');
            }
            // validate and get the technician_id
            try {
                $technician_id = $admin->technicianBySeqId($request->technician_id)->id;
            }catch(ModelNotFoundException $e){
                return $this->respondNotFound('Technician with that id, does not exist.');
            }

        // check if the report was made on time
        $on_time = $this->reportHelpers->checkOnTimeValue(
                (new Carbon($request->completed, $admin->timezone)),
                $service->start_time,
                $service->end_time,
                $admin->timezone
            );

        // ***** Persisting *****
        $report = DB::transaction(function () use($request, $service, $technician_id, $on_time) {

            // create report
            $report = Report::create([
                'service_id' => $service->id,
                'technician_id' => $technician_id,
                // need to check what timezone is completed
                'completed' => $request->completed,
                'on_time' => $on_time,
                'ph' => $request->ph,
                'chlorine' => $request->chlorine,
                'temperature' => $request->temperature,
                'turbidity' => $request->turbidity,
                'salt' => $request->salt,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'altitude' => $request->altitude,
                'accuracy' => $request->accuracy,
            ]);

            // add photos
            $report->addImageFromForm($request->file('photo1'));
            $report->addImageFromForm($request->file('photo2'));
            $report->addImageFromForm($request->file('photo3'));

            return $report;

        });

        // notify report was made
            // notify the clients
            foreach ($service->clients()->get() as $client) {
                $client->user->notify(new ReportCreatedNotification($report));
            }
            // notify the supervisor
            $report->supervisor()->user->notify(new ReportCreatedNotification($report));

        return $this->respondPersisted(
            'The report was successfuly created.',
            $this->reportTransformer->transform($admin->reportsInOrder('desc')->first())
        );

    }

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($seq_id)
    {
        if($this->getUser()->cannot('show', Report::class))
        {
            return $this->setStatusCode(403)->respondWithError('You don\'t have permission to access this. The administrator can grant you permission');
        }

        try {
            $report = $this->loggedUserAdministrator()->reportsBySeqId($seq_id);
        }catch(ModelNotFoundException $e){
            return $this->respondNotFound('Report with that id, does not exist.');
        }

        if($report){
            return $this->respond([
                'data' => $this->reportTransformer->transform($report),
            ]);
        }

        return $this->respondInternalError();
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $seq_id)
    {
        // check if user has permission
        if($this->getUser()->cannot('edit', Report::class))
        {
            return $this->setStatusCode(403)->respondWithError('You don\'t have permission to access this. The administrator can grant you permission');
        }

        $admin = $this->loggedUserAdministrator();

        // Validate
            // validate core attributes
            $this->validateReportUpdate($request);
            // validate and get the Report
            try {
                $report = $admin->reportsBySeqId($seq_id);
            }catch(ModelNotFoundException $e){
                return $this->respondNotFound('Report with that id, does not exist.');
            }
            // validate and get the service
            try {
                if(isset($request->service_id)){
                    $service = $admin->serviceBySeqId($request->service_id);
                }else{
                    $service = $report->service();
                }
            }catch(ModelNotFoundException $e){
                return $this->respondNotFound('Service with that id, does not exist.');
            }
            // validate and get the technician_id
            try {
                if(isset($request->technician_id)){
                    $technician_id = $admin->technicianBySeqId($request->technician_id)->id;
                }else{
                    $technician_id = $report->technician_id;
                }
            }catch(ModelNotFoundException $e){
                return $this->respondNotFound('Technician with that id, does not exist.');
            }

        // end validation

        // ***** Persisting *****
        $transaction = DB::transaction(function () use($request, $report, $service, $technician_id) {

            // $service and $technician_id were checked allready
            $report->fill(array_merge(
                array_map('htmlentities', $request->except('on_time')),
                [
                    'service_id' => $service->id,
                    'technician_id' => $technician_id
                ]
            ));
            $report->save();

            if(isset($request->photo1)){
                $report->deleteImage(1);
                $report->addImageFromForm($request->file('photo1'), 1);
            }
            if(isset($request->photo2)){
                $report->deleteImage(2);
                $report->addImageFromForm($request->file('photo2'), 2);
            }
            if(isset($request->photo3)){
                $report->deleteImage(3);
                $report->addImageFromForm($request->file('photo3'), 3);
            }

        });

        return $this->respondPersisted(
            'The report was successfully updated.',
            $this->reportTransformer->transform($this->loggedUserAdministrator()->reportsBySeqId($seq_id))
        );
    }

    /**
     * Remove the specified resource from storage.
     * tested
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($seq_id)
    {
        if($this->getUser()->cannot('destroy', Report::class))
        {
            return $this->setStatusCode(403)->respondWithError('You don\'t have permission to access this. The administrator can grant you permission');
        }

        try{
            $report = $this->loggedUserAdministrator()->reportsBySeqId($seq_id);
        }catch(ModelNotFoundException $e){
            return $this->respondNotFound('Report with that id, does not exist.');
        }

        if($report->delete()){
            return $this->respondWithSuccess('Report was successfully deleted');
        }

        return $this->respondNotFound('Report with that id, does not exist.');
    }

    protected function validateReportCreate(Request $request)
    {
        return $this->validate($request, [
            'service_id' => 'required|integer|min:1',
            'technician_id' => 'required|integer|min:1',
            'completed' => 'required|date',
            'ph' => 'required|integer|between:1,5',
            'chlorine' => 'required|integer|between:1,5',
            'temperature' => 'required|integer|between:1,5',
            'turbidity' => 'required|integer|between:1,4',
            'salt' => 'required|integer|between:1,5',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'required|numeric|between:0,100000',
            'photo1' => 'required|mimes:jpg,jpeg,png',
            'photo2' => 'required|mimes:jpg,jpeg,png',
            'photo3' => 'required|mimes:jpg,jpeg,png',
        ]);
    }

    protected function validateReportUpdate(Request $request)
    {
        return $this->validate($request, [
            'service_id' => 'integer|min:1',
            'technician_id' => 'integer|min:1',
            'completed' => 'date',
            'ph' => 'integer|between:1,5',
            'chlorine' => 'integer|between:1,5',
            'temperature' => 'integer|between:1,5',
            'turbidity' => 'integer|between:1,4',
            'salt' => 'integer|between:1,5',
            'latitude' => 'numeric|between:-90,90',
            'longitude' => 'numeric|between:-180,180',
            'accuracy' => 'numeric|between:0,100000',
            'photo1' => 'mimes:jpg,jpeg,png',
            'photo2' => 'mimes:jpg,jpeg,png',
            'photo3' => 'mimes:jpg,jpeg,png',
        ]);
    }

}
