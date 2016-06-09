@extends('layouts.app')

@section('content')
	<header class="section-header">
		<div class="tbl">
			<div class="tbl-row">
				<div class="tbl-cell">
					<h3>View Technician</h3>
					<ol class="breadcrumb breadcrumb-simple">
						<li><a href="{{ url('technicians') }}">Technician</a></li>
						<li class="active">View Technician {{ $technician->seq_id }}</li>
					</ol>
				</div>
			</div>
		</div>
	</header>
	<div class="row">
		<div class="col-md-12 col-lg-12 col-xl-8 col-xl-offset-2">
			<section class="card">
					<header class="card-header card-header-lg">
						Technician info:
					</header>
					<div class="card-block">
						<form>
							@if($technician->numImages() > 0)
								<div class="form-group row">
									<label class="col-sm-2 form-control-label">Photo</label>
									<div class="col-sm-10">
										<div class="col-xl-3 col-lg-4 col-md-4 col-sm-5 m-b-md">
			                                <div class="gallery-col">
												<article class="gallery-item">
													<img class="gallery-picture" src="{{ url($technician->thumbnail()) }}" alt="" height="158">
													<div class="gallery-hover-layout">
														<div class="gallery-hover-layout-in">
															<p class="gallery-item-title">Technician Photo</p>
															<div class="btn-group">
																<a class="fancybox btn" href="{{ url($technician->image()) }}" title="Technician Photo">
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
									<input type="text" readonly class="form-control" value="{{ $technician->seq_id }}">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Name</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" value="{{ $technician->name }}">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Last Name</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" value="{{ $technician->last_name }}">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Supervisor Name</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" value="{{ $technician->supervisor()->name.' '.$technician->supervisor()->last_name }}">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Username</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" value="{{ $technician->user()->email }}">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Password</label>
								<div class="col-sm-10">
									<input type="password" readonly class="form-control hide-show-password" value="{{ $technician->user()->password }}">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Mobile Phone</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" value="{{ $technician->cellphone }}">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Address Line</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" value="{{ $technician->address }}">
								</div>
							</div>


							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Language</label>
								<div class="col-sm-10">
									<input type="text" readonly class="form-control" value="{{ languageCode_to_text($technician->language) }}">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Comments</label>
								<div class="col-sm-10">
									<textarea rows="4" class="form-control"
												placeholder="Any additional info about this technician."
												name="comments" readonly>{{ $technician->comments }}</textarea>
								</div>
							</div>
						</form>
						<hr>
						<p style="float: right;">
							<a class="btn btn-danger"
							data-method="delete" data-token="{{ csrf_token() }}"
			        		data-confirm="Are you sure?" href="{{ url('/technicians/'.$technician->seq_id) }}">
							<i class="font-icon font-icon-close-2"></i>&nbsp;&nbsp;Delete</a>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<a  class="btn btn-primary"
							href="{{ url('/technicians/'.$technician->seq_id.'/edit') }}">
							<i class="font-icon font-icon-pencil"></i>&nbsp;&nbsp;Edit Technician</a>
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
