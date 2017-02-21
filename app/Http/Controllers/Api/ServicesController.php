<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Http\Requests;

use App\PRS\Transformers\ServiceTransformer;
use App\PRS\Transformers\PreviewTransformers\ServicePreviewTransformer;
use App\PRS\Helpers\ServiceHelpers;

use App\Service;

use Auth;
use DB;
use Validator;
use Illuminate\View\View;
use App\Http\Requests\CreateServiceRequest;
use App\Administrator;

class ServicesController extends ApiController
{

    private $serviceTransformer;
    private $servicePreviewTransformer;
    private $serviceHelpers;


    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct(ServiceTransformer $serviceTransformer,
                                ServicePreviewTransformer $servicePreviewTransformer,
                                ServiceHelpers $serviceHelpers)
    {
        $this->serviceTransformer = $serviceTransformer;
        $this->servicePreviewTransformer = $servicePreviewTransformer;
        $this->serviceHelpers = $serviceHelpers;
    }

    /**
    * Display a listing of the resource.
    * tested
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        if($this->getUser()->cannot('list', Service::class))
        {
            return $this->setStatusCode(403)->respondWithError('You don\'t have permission to access this. The administrator can grant you permission');
        }

        $this->validate($request, [
            'preview' => 'boolean',
            'status' => 'boolean',
            'limit' => 'integer|between:1,25', // dont validate limit if preview is true
        ]);

        $admin = $this->loggedUserAdministrator();

        // make a preview transformation
        if($request->preview){
            return $this->indexPreview($request, $admin);
        }

        $limit = ($request->limit)?: 5;
        if($request->has('status')){
            // do this with filtering weather has contract active or not
            $services = $admin->servicesInOrder()
                            ->where('status', $request->status)
                            ->paginate($limit);
        }else{
            $services = $admin->servicesInOrder()
                            ->paginate($limit);
        }

        return $this->respondWithPagination(
            $services,
            $this->serviceTransformer->transformCollection($services)
        );

    }

    protected function indexPreview(Request $request, Administrator $admin)
    {
        if($request->has('status')){
            $services = $admin->servicesInOrder()
                                ->where('status', $request->status)
                                ->get();
        }else{
            $services = $admin->servicesInOrder()->get();
        }

        return $this->respond([
                'data' => $this->servicePreviewTransformer->transformCollection($services)
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
        if($this->getUser()->cannot('create', Service::class))
        {
            return $this->setStatusCode(403)->respondWithError('You don\'t have permission to access this. The administrator can grant you permission');
        }

        $this->validate($request, [
            'name' => 'required|string|max:20',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address_line' => 'required|string|max:50',
            'city' => 'required|string|max:30',
            'state' => 'required|string|max:30',
            'postal_code' => 'required|string|max:15',
            'country' => 'required|string|size:2',
            'comments' => 'string|max:10000',
            'photo' => 'mimes:jpg,jpeg,png',
        ]);

        $admin = $this->loggedUserAdministrator();

        // Create service
        $transaction = DB::transaction(function () use($request, $admin) {

            $service = $admin->services()->create(
                array_merge(
                    array_map('htmlentities', $request->all()),
                    [
                        'country' => strtoupper(htmlentities($request->country)),
                    ]
                )
            );

            // Add Photo
            if($request->photo){
                $photo = $service->addImageFromForm($request->file('photo'));
            }

        });

        return $this->respondPersisted(
            'The service was successfuly created.',
            $this->serviceTransformer->transform($admin->services(true)->first())
        );

    }

    /**
    * Display the specified resource.
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($seq_id)
    {
        try {
            $service = $this->loggedUserAdministrator()->serviceBySeqId($seq_id);
        }catch(ModelNotFoundException $e){
            return $this->respondNotFound('Service with that id, does not exist.');
        }

        if($this->getUser()->cannot('view', $service))
        {
            return $this->setStatusCode(403)->respondWithError('You don\'t have permission to access this. The administrator can grant you permission');
        }

        if($service){
            return $this->respond([
                'data' => $this->serviceTransformer->transform($service),
            ]);
        }

        return $this->respondNotFound('Service with that id, does not exist.');

    }

    /**
    * Update the specified resource in storage.
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $seq_id)
    {
        try{
            $service = $this->loggedUserAdministrator()->serviceBySeqId($seq_id);
        }catch(ModelNotFoundException $e){
            return $this->respondNotFound('Service with that id, does not exist.');
        }

        if($this->getUser()->cannot('update', $service))
        {
            return $this->setStatusCode(403)->respondWithError('You don\'t have permission to access this. The administrator can grant you permission');
        }

        $this->validate($request, [
            'name' => 'string|max:20',
            'latitude' => 'numeric|between:-90,90',
            'longitude' => 'numeric|between:-180,180',
            'address_line' => 'string|max:50',
            'city' => 'string|max:30',
            'state' => 'string|max:30',
            'postal_code' => 'string|max:15',
            'country' => 'string|size:2',
            'comments' => 'string|max:750',
            'photo' => 'mimes:jpg,jpeg,png',
        ]);

        // ***** Persist *****
        $transaction = DB::transaction(function () use($request, $service) {

            $service->fill(
                array_merge(
                    array_map('htmlentities', $request->all()),
                    [
                        'country' => strtoupper(htmlentities($request->country)),
                    ]
                ));

            $service->save();

            if($request->photo){
                $service->images()->delete();
                $photo = $service->addImageFromForm($request->file('photo'));
            }

        });

        return $this->respondPersisted(
            'The service was successfully updated.',
            $this->serviceTransformer->transform($this->loggedUserAdministrator()->serviceBySeqId($seq_id))
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
        try{
            $service = $this->loggedUserAdministrator()->serviceBySeqId($seq_id);
        }catch(ModelNotFoundException $e){
            return $this->respondNotFound('Service with that id, does not exist.');
        }

        if($this->getUser()->cannot('delete', $service))
        {
            return $this->setStatusCode(403)->respondWithError('You don\'t have permission to access this. The administrator can grant you permission');
        }

        if($service->delete()){
            return $this->respondWithSuccess('Service was successfully deleted');
        }

        return $this->respondNotFound('Service with that id, does not exist.');

    }

}
