@extends('layouts.app')

@section('content')
	<header class="section-header">
		<div class="tbl">
			<div class="tbl-row">
				<div class="tbl-cell">
					<h3>View Report</h3>
					<ol class="breadcrumb breadcrumb-simple">
						<li><a href="{{ url('reports') }}">Reports</a></li>
						<li class="active">View Report {{ $report->seq_id }}</li>
					</ol>
				</div>
			</div>
		</div>
	</header>
	<div class="row">
		<div class="col-md-12 col-lg-12 col-xl-8 col-xl-offset-2">
			<section class="card">
					<header class="card-header card-header-lg">
						Report info:
					</header>
					<div class="card-block">
						<form>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">ID</label>
								<div class="col-sm-10">
									<p class="form-control-static"><input type="text" readonly class="form-control" id="inputPassword" value="{{ $report->seq_id }}"></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Compleated at:</label>
								<div class="col-sm-10">
									<p class="form-control-static"><input type="text" readonly class="form-control" id="inputPassword" value="{{ format_date($report->completed) }}"></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Was made on time?</label>
								<div class="col-sm-10">
									<p class="form-control-static">{!! onTime_tag($report->on_time) !!}</p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Service Name</label>
								<div class="col-sm-10">
									<p class="form-control-static"><input type="text" readonly class="form-control" id="inputPassword" value="{{ $report->service()->name }}"></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Technician Name</label>
								<div class="col-sm-10">
									<p class="form-control-static"><input type="text" readonly class="form-control" id="inputPassword" value="{{ $report->technician()->name }}"></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">PH</label>
								<div class="col-sm-10">
									<p class="form-control-static">{!! readings_tag($report->ph) !!}</p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Clorine</label>
								<div class="col-sm-10">
									<p class="form-control-static">{!! readings_tag($report->clorine) !!}</p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Temperature</label>
								<div class="col-sm-10">
									<p class="form-control-static">{!! readings_tag($report->temperature) !!}</p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Turbidity</label>
								<div class="col-sm-10">
									<p class="form-control-static">{!! readings_tag($report->turbidity, true) !!}</p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Salt</label>
								<div class="col-sm-10">
									<p class="form-control-static">{!! readings_tag($report->salt) !!}</p>
								</div>
							</div>
						</form>
						<br>
						<h4>Pool Photos</h4>
						<hr>
						<div class="row">
							@foreach($report->images as $image)
	                            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-5 m-b-md">
	                                <div class="gallery-col">
										<article class="gallery-item">
											<img class="gallery-picture" src="{{ url($image->thumbnail_path) }}" alt="" height="158">
											<div class="gallery-hover-layout">
												<div class="gallery-hover-layout-in">
													<p class="gallery-item-title">{{ get_image_tag($image->order) }}</p>
													<div class="btn-group">
														<a class="fancybox btn" href="{{ url($image->normal_path) }}" title="{{ get_image_tag($image->order) }}">
															<i class="font-icon font-icon-eye"></i>
														</a>
													</div>
													<p>Photo number {{ $image->order }}</p>
												</div>
											</div>
										</article>
									</div><!--.gallery-col-->
	                            </div><!--.col-->
                            @endforeach
						</div>
						<hr>
						<p style="float: right;">
							<a class="btn btn-danger"
							data-method="delete" data-token="{{ csrf_token() }}"
			        		data-confirm="Are you sure?" href="{{ url('/reports/'.$report->seq_id) }}">
							<i class="font-icon font-icon-close-2"></i>&nbsp;&nbsp;Delete</a>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<a  class="btn btn-primary"
							href="{{ url('/reports/'.$report->seq_id.'/edit') }}">
							<i class="font-icon font-icon-pencil"></i>&nbsp;&nbsp;Edit Report</a>
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
