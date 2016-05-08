@extends('layouts.app')

@section('content')
	<header class="section-header">
		<div class="tbl">
			<div class="tbl-row">
				<div class="tbl-cell">
					<h3>View Service</h3>
					<ol class="breadcrumb breadcrumb-simple">
						<li><a href="{{ url('services') }}">Services</a></li>
						<li class="active">View Service {{ $service->seq_id }}</li>
					</ol>
				</div>
			</div>
		</div>
	</header>
	<div class="row">
		<div class="col-md-12 col-lg-12 col-xl-8 col-xl-offset-2">
			<section class="card">
					<header class="card-header card-header-lg">
						Service info:
					</header>
					<div class="card-block">
						<form>
							@if($service->numImages() > 0)
								<div class="form-group row">
									<label class="col-sm-2 form-control-label">Service photo</label>
									<div class="col-sm-10">
										<div class="col-xl-3 col-lg-4 col-md-4 col-sm-5 m-b-md">
			                                <div class="gallery-col">
												<article class="gallery-item">
													<img class="gallery-picture" src="{{ url($service->thumbnail()) }}" alt="" height="158">
													<div class="gallery-hover-layout">
														<div class="gallery-hover-layout-in">
															<p class="gallery-item-title">Service Photo</p>
															<div class="btn-group">
																<a class="fancybox btn" href="{{ url($service->image()) }}" title="Service Photo">
																	<i class="font-icon font-icon-eye"></i>
																</a>
															</div>
														</div>
													</div>
												</article>
											</div><!--.gallery-col-->
			                            </div><!--.col-->
									</div>
								</div>
							@endif
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">ID</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" id="inputPassword" value="{{ $service->seq_id }}">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Name</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" id="inputPassword" value="{{ $service->name }}">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Street and Number</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" id="inputPassword" value="{{ $service->address_line }}">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">City</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" id="inputPassword" value="{{ $service->city }}">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">State</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" id="inputPassword" value="{{ $service->state }}">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Postal Code</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" id="inputPassword" value="{{ $service->postal_code }}">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Country</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" id="inputPassword" value="{{ get_country_by_code($service->country) }}">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Type</label>
								<div class="col-sm-10">
									{!! get_styled_type($service->type, false) !!}
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Service days</label>
								<div class="col-sm-10">
									{!! get_styled_service_days($service->service_days) !!}
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Time interval</label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-addon">From:</div>
										<input type="text" class="form-control"
												name="start_time" value="{{ $service->start_time }}" readonly>
										<div class="input-group-addon">To:</div>
										<input type="text" class="form-control"
												name="end_time" value="{{ $service->end_time }}" readonly>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Price</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" id="inputPassword" value="{{ $service->amount.' '.$service->currency }}">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Status</label>
								<div class="col-sm-10">
									{!! get_styled_status($service->status, false) !!}
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Comments</label>
								<div class="col-sm-10">
									<textarea rows="4" class="form-control" 
												placeholder="Any additional info about this service."
												name="comments" readonly>{{ $service->comments }}</textarea>
								</div>
							</div>
						</form>
						<hr>
						<p style="float: right;">
							<a class="btn btn-danger" 
							data-method="delete" data-token="{{ csrf_token() }}" 
			        		data-confirm="Are you sure?" href="{{ url('/services/'.$service->seq_id) }}">
							<i class="font-icon font-icon-close-2"></i>&nbsp;&nbsp;Delete</a>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<a  class="btn btn-primary"
							href="{{ url('/services/'.$service->seq_id.'/edit') }}">
							<i class="font-icon font-icon-pencil"></i>&nbsp;&nbsp;Edit Service</a>
						</p>
						<br>
						<br>
					</div>
			</section>
		</div>
	</div>
	<div class="row">
	</div>

@endsection