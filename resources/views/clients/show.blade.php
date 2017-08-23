@extends('layouts.app')

@inject('helper', 'App\PRS\Helpers\UserRoleCompanyHelpers')
@section('content')
	<header class="section-header">
		<div class="tbl">
			<div class="tbl-row">
				<div class="tbl-cell">
					<h3>View Client</h3>
					<ol class="breadcrumb breadcrumb-simple">
						<li><a href="{{ url('clients') }}">Client</a></li>
						<li class="active">View Client {{ $client->seq_id }}</li>
					</ol>
				</div>
			</div>
		</div>
	</header>
	<div class="row">
		<div class="col-md-12 col-lg-12 col-xl-8 col-xl-offset-2">
			<section class="card">
					<header class="card-header card-header-lg">
						Client info:
					</header>
					<div class="card-block">
						<form>

							@if(!$user->verified)
							<div class="form-group row">
								<email-verification-notice
									name="Client"
									seq-id="{{ $client->seq_id }}">
								</email-verification-noctice>
							</div>
							<br>
							@endif

							@if(!$client->accepted)
							<br>
							<div class="form-group row">
								<div class="col-sm-10 col-sm-offset-2">
									<h3 style="display: inline;"><span class="label label-warning">Unaccepted Client</span></h3>
									<small class="text-muted"><strong>This Client has't accepted been part of your organization.</strong>
										<br>
										<br><strong>Dont worry you can still store it here, but some of the features are going to be disabled.</strong>
										<br>Features that require acceptance:
										<ul>
											<li>* Accepting Payments</li>
											<li>* Sending Notifications</li>
											<li>* Sending Generating Invoices</li>
										</ul>
									</small>
									<br>
								</div>
							</div>
							<br>
							@endif

							@if($image)
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Client photo</label>
								<div class="col-sm-10">
									<div class="col-xl-3 col-lg-4 col-md-4 col-sm-5 m-b-md">
										<photo :image="{{ json_encode($image) }}" :can-delete="false"></photo>
									</div>
								</div>
							</div>
							@endif

							<div class="form-group row">
								<label class="col-sm-2 form-control-label">ID</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" value="{{ $client->seq_id }}">
								</div>
							</div>

							<reference-real-values
								real-value="{{ $user->name }}"
								reference-value="{{ $client->name_extra }}"
								name="name"
								urc-id="{{ $client->seq_id }}">
							</reference-real-values>


							<reference-real-values
								real-value="{{ $user->last_name }}"
								reference-value="{{ $client->last_name_extra }}"
								name="last_name"
								urc-id="{{ $client->seq_id }}">
							</reference-real-values>


							<reference-real-values
								real-value="{{ $user->email }}"
								reference-value="{{ $client->email_extra }}"
								name="email"
								text="Client logs in and recives emails to this email."
								urc-id="{{ $client->seq_id }}">
							</reference-real-values>

							<div class="form-group row" style="margin-bottom:30px">
								<label class="col-sm-2 form-control-label">Name</label>
								<div class="col-sm-10">
									<div class="input-group" style="margin-bottom:10px">
										<div class="input-group-addon">Real</div>
										<input type="text" readonly class="form-control" value="{{ $user->name }}">
										<span class="input-group-btn">
											<button class="btn btn-success bootstrap-touchspin-up" type="button">Request Change</button>
										</span>
									</div>
									<div class="input-group">
										<div class="input-group-addon">Reference</div>
										<input type="text" readonly class="form-control" value="{{ $client->name_extra }}">
									</div>
								</div>
							</div>

							<div class="form-group row" style="margin-bottom:30px">
								<label class="col-sm-2 form-control-label">Last Name</label>
								<div class="col-sm-10">
									<div style="margin-bottom:10px">
										<div class="input-group">
											<div class="input-group-addon">Real</div>
											<input type="text" readonly class="form-control" value="{{ $user->last_name }}">
											<span class="input-group-btn">
												<button class="btn btn-success bootstrap-touchspin-up" type="button">Request Change</button>
											</span>
										</div>
									</div>
									<div class="input-group">
										<div class="input-group-addon">Reference</div>
										<input type="text" readonly class="form-control" value="{{ $client->last_name_extra }}">
									</div>
								</div>
							</div>

							<div class="form-group row" style="margin-bottom:30px">
								<label class="col-sm-2 form-control-label">Email</label>
								<div class="col-sm-10">
									<div style="margin-bottom:10px">
										<div class="input-group">
											<div class="input-group-addon">Real</div>
											<input type="text" readonly class="form-control" value="{{ $user->email }}">
											<span class="input-group-btn">
												<button class="btn btn-success bootstrap-touchspin-up" type="button">Request Change</button>
											</span>
										</div>
										<small class="text-muted">Client logs in and recives emails to this email.</small>
									</div>

									<div class="input-group">
										<div class="input-group-addon">Reference</div>
										<input type="text" readonly class="form-control" value="{{ $client->email_extra }}">
									</div>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Mobile Phone</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" value="{{ $client->cellphone }}">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Address</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" value="{{ $client->address }}">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Type</label>
								<div class="col-sm-10">
									{!! $helper->styledTypeClient($client->type, false) !!}
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Receives email</label>
								<div class="col-sm-10">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Language</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" value="{{ languageCode_to_text($user->language) }}">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 form-control-label">About</label>
								<div class="col-sm-10">
									<textarea rows="4" class="form-control"
												placeholder="Any additional info about this client."
												readonly>{{ $client->about }}</textarea>
								</div>
							</div>

						</form>
						<hr>
						<div id="toolbar">
							<h3><strong>List of Services</strong></h3>
						</div>
						<div class="table-responsive">
							<table class="generic_table"
								   class="table"
								   data-toolbar="#toolbar"
								   data-search="true"
								   data-show-export="true"
								   data-export-types="['excel', 'pdf']"
								   data-minimum-count-columns="2"
								   data-pagination="true"
								   data-show-footer="false"
								   data-response-handler="responseHandler"
								   >
								<thead>
								    <tr>
								        <th data-field="id" data-sortable="true">#</th>
								        <th data-field="name" data-sortable="true">Name</th>
								        <th data-field="address" data-sortable="true">Address</th>
								        <th data-field="price" data-sortable="true">Price</th>
								    </tr>
								</thead>
								<tbody>
									@foreach ($services as $service)
										<tr>
											<td>{{ $service->seq_id }}</td>
											<td>{{ $service->name }}</td>
											<td>{{ $service->address_line }}</td>
											<td>{!! $service->amount.' <strong>'.$service->currency.'</strong>' !!}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>

						<hr>
						<span style="float: right;">
        					@can('delete', $client)
								<delete-button url="clients/" object-id="{{ $client->seq_id }}">
								</delete-button>
        					@endcan
        					@can('update', $client)
								&nbsp;&nbsp;&nbsp;&nbsp;
								<a  class="btn btn-primary"
								href="{{ url('/clients/'.$client->seq_id.'/edit') }}">
								<i class="font-icon font-icon-pencil"></i>&nbsp;&nbsp;Edit Client</a>
        					@endcan
						</span>
						<br>
						<br>
					</div>
			</section>
		</div>
	</div>
	<div class="row">
	</div>

@endsection
