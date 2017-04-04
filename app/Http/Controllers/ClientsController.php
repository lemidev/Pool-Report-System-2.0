<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use JavaScript;
use DB;

use App\Client;
use App\User;
use App\Http\Requests;

use App\Http\Requests\CreateUserRoleCompanyRequest;
use App\Http\Requests\UpdateUserRoleCompanyRequest;
use App\PRS\Transformers\ImageTransformer;
use App\UserRoleCompany;

class ClientsController extends PageController
{

    protected $imageTransformer;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ImageTransformer $imageTransformer)
    {
        $this->middleware('auth');
        $this->imageTransformer = $imageTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('list', [UserRoleCompany::class, 'client']);

        $default_table_url = url('datatables/clients');

        JavaScript::put([
            'click_url' => url('clients').'/',
        ]);

        return view('clients.index', compact('default_table_url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [UserRoleCompany::class, 'client']);

        $services = $this->loggedCompany()->services()->seqIdOrdered()->get();

        return view('clients.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRoleCompanyRequest $request)
    {
        $this->authorize('create', [UserRoleCompany::class, 'client']);

        $company = $this->loggedCompany();

        $this->validate($request, [
            'type' => 'required|numeric|between:1,2',
            'services' => 'array',
            'services.*' => 'required|integer|existsBasedOnCompany:services,'.$company->id,
        ]);

        $user = User::where('email', $request->email)->first();
        if($user == null){
            // Create the user
            $user = User::create(array_map('htmlentities',[
                'email' => $request->email,
                'name' => $request->name,
                'last_name' => $request->last_name,
                'language' => $request->language,
            ]));
        }

        $client = $user->userRoleCompanies()->create(
                        array_map('htmlentities', [
                            'type' => $request->type,
                            'cellphone' => $request->cellphone,
                            'address' => $request->address,
                            'about' => $request->about,
                            'role_id' => 2,
                            'company_id' => $company->id,
                        ])
                    );

        $photo = true;
        if($request->photo){
            $photo = $client->addImageFromForm($request->file('photo'));
        }

        if($request->services){
            $client->setServices($request->services);
        }
        $client->save();

        if($client && $photo && $user){
            flash()->success('Created', 'New client successfully created.');
            return redirect('clients');
        }
        flash()->success('Not created', 'New client was not created, please try again later.');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($seq_id)
    {
        $client = $this->loggedCompany()
                            ->userRoleCompanies()
                            ->bySeqId($seq_id)
                            ->firstOrFail();

        // check that userRoleCompany has role of client
        if(!$client->isRole('client')){
            abort(404, 'There is no client with that id');
        }

        $this->authorize('view', $client);

        $user = $client->user;
        $services = $client->services;
        $image = null;
        if($client->images->count() > 0){
            $image = $this->imageTransformer->transform($client->images->first());
        }

        return view('clients.show',compact('client','user', 'services', 'image'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($seq_id)
    {
        $admin = $this->loggedUserAdministrator();
        $client = $admin->clientsBySeqId($seq_id);

        $this->authorize('update', $client);

        $services = $admin->services()->seqIdOrdered()->get();

        return view('clients.edit',compact('client', 'services'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRoleCompanyRequest $request, $seq_id)
    {
        $company = $this->loggedCompany();

        $this->validate($request, [
            'type' => 'numeric|between:1,2',
            'services' => 'array',
            'services.*' => 'required|integer|existsBasedOnCompany:services,'.$company->id,
        ]);

        $client = $company->clientsBySeqId($seq_id);

        $this->authorize('update', $client);

        $user  = $client->user;
        $user->email = htmlentities($request->email);

        $client->fill(array_map('htmlentities', $request->except(['admin_id', 'services'])));
        $client->setServices($request->services);

        $photo = false;
        if($request->photo){
            $client->images()->delete();
            $photo = $client->addImageFromForm($request->file('photo'));
        }

        $userSaved = $user->save();
        $clientSaved = $client->save();

        if(!$userSaved && !$clientSaved && !$photo){
            flash()->overlay("You did not change anything", 'You did not make changes in client information.', 'info');
            return redirect()->back();
        }
        flash()->success('Updated', 'Client successfully updated.');
        return redirect('clients/'.$seq_id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($seq_id)
    {
        $client = $this->loggedUserAdministrator()->clientsBySeqId($seq_id);

        $this->authorize('delete', $client);

        if($client->delete()){
            flash()->success('Deleted', 'The client successfully deleted.');
            return response()->json([
                'message' => 'The client was deleted successfully.'
            ]);
        }
        return response()->json([
                'error' => 'The client was not deleted, please try again later.'
            ], 500);
    }

}
