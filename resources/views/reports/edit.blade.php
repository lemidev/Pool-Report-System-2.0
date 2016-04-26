@extends('layouts.app')

@section('content')
	<header class="section-header">
		<div class="tbl">
			<div class="tbl-row">
				<div class="tbl-cell">
					<h3>Edit Report</h3>
					<ol class="breadcrumb breadcrumb-simple">
						<li><a href="{{ url('reports') }}">Reports</a></li>
						<li><a href="{{ url('reports/'.$report->seq_id) }}">View Report {{ $report->seq_id }}</a></li>
						<li class="active">Edit Report {{ $report->seq_id }}</li>
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
								<label class="col-sm-2 form-control-label">Compleated at:</label>
								<div class="col-sm-10">
									<div class='input-group date' id="edit_report_datepicker">
									<input type='text' class="form-control" id="edit_report_datepicker_input"/>
									<span class="input-group-addon">
										<i class="font-icon font-icon-calend"></i>
									</span>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Service</label>
								<div class="col-sm-10">
									<select class="bootstrap-select bootstrap-select-arrow" data-live-search="true">
										@foreach($services as $service)
											<option data-content='<span class="user-item"><img src="{{ url($service->icon()) }}"/>
														{{ $service->seq_id.' '.$service->name.' '.$service->last_name}}
														</span>'>{{ $service->seq_id }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Technician</label>
								<div class="col-sm-10">
									<select class="bootstrap-select bootstrap-select-arrow" data-live-search="true">
										@foreach($technicians as $technician)
											<option data-content='<span class="user-item"><img src="{{ url($technician->icon()) }}"/>
														{{ $technician->seq_id.' '.$technician->name.' '.$technician->last_name}}
														</span>'>{{ $technician->seq_id }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">PH</label>
								<div class="col-md-3 col-lg-3 col-xl-4">
									<select class="bootstrap-select bootstrap-select-arrow">
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #FA424A;">
																</span>&nbsp;&nbsp;Very High'
																{{ ($report->ph == 5) ? 'selected':''}}>5
										</option>
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #FDAD2A;">
																</span>&nbsp;&nbsp;High'
																{{ ($report->ph == 4) ? 'selected':''}}>4
										</option>
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #46C35F;">
																</span>&nbsp;&nbsp;Perfect'
																{{ ($report->ph == 3) ? 'selected':''}}>3
										</option>
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #00A8FF;">
																</span>&nbsp;&nbsp;Low'
																{{ ($report->ph == 2) ? 'selected':''}}>2
										</option>
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #AC6BEC;">
																</span>&nbsp;&nbsp;Very Low'
																{{ ($report->ph == 1) ? 'selected':''}}>1
										</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Clorine</label>
								<div class="col-md-3 col-lg-3 col-xl-4">
									<select class="bootstrap-select bootstrap-select-arrow">
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #FA424A;">
																</span>&nbsp;&nbsp;Very High'
																{{ ($report->clorine == 5) ? 'selected':''}}>5
										</option>
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #FDAD2A;">
																</span>&nbsp;&nbsp;High'
																{{ ($report->clorine == 4) ? 'selected':''}}>4
										</option>
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #46C35F;">
																</span>&nbsp;&nbsp;Perfect'
																{{ ($report->clorine == 3) ? 'selected':''}}>3
										</option>
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #00A8FF;">
																</span>&nbsp;&nbsp;Low'
																{{ ($report->clorine == 2) ? 'selected':''}}>2
										</option>
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #AC6BEC;">
																</span>&nbsp;&nbsp;Very Low'
																{{ ($report->clorine == 1) ? 'selected':''}}>1
										</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Temperature</label>
								<div class="col-md-3 col-lg-3 col-xl-4">
									<select class="bootstrap-select bootstrap-select-arrow">
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #FA424A;">
																</span>&nbsp;&nbsp;Very High'
																{{ ($report->temperature == 5) ? 'selected':''}}>5
										</option>
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #FDAD2A;">
																</span>&nbsp;&nbsp;High'
																{{ ($report->temperature == 4) ? 'selected':''}}>4
										</option>
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #46C35F;">
																</span>&nbsp;&nbsp;Perfect'
																{{ ($report->temperature == 3) ? 'selected':''}}>3
										</option>
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #00A8FF;">
																</span>&nbsp;&nbsp;Low'
																{{ ($report->temperature == 2) ? 'selected':''}}>2
										</option>
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #AC6BEC;">
																</span>&nbsp;&nbsp;Very Low'
																{{ ($report->temperature == 1) ? 'selected':''}}>1
										</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Turbidity</label>
								<div class="col-md-3 col-lg-3 col-xl-4">
									<select class="bootstrap-select bootstrap-select-arrow">
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #FA424A;">
																</span>&nbsp;&nbsp;Very High'
																{{ ($report->turbidity == 4) ? 'selected':''}}>4
										</option>
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #FDAD2A;">
																</span>&nbsp;&nbsp;High'
																{{ ($report->turbidity == 3) ? 'selected':''}}>3
										</option>
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #00A8FF;">
																</span>&nbsp;&nbsp;Low'
																{{ ($report->turbidity == 2) ? 'selected':''}}>2
										</option>
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #46C35F;">
																</span>&nbsp;&nbsp;Perfect'
																{{ ($report->turbidity == 1) ? 'selected':''}}>1
										</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 form-control-label">Salt</label>
								<div class="col-md-3 col-lg-3 col-xl-4">
									<select class="bootstrap-select bootstrap-select-arrow">
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #FA424A;">
																</span>&nbsp;&nbsp;Very High'
																{{ ($report->salt == 5) ? 'selected':''}}>5
										</option>
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #FDAD2A;">
																</span>&nbsp;&nbsp;High'
																{{ ($report->salt == 4) ? 'selected':''}}>4
										</option>
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #46C35F;">
																</span>&nbsp;&nbsp;Perfect'
																{{ ($report->salt == 3) ? 'selected':''}}>3
										</option>
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #00A8FF;">
																</span>&nbsp;&nbsp;Low'
																{{ ($report->salt == 2) ? 'selected':''}}>2
										</option>
										<option data-content='<span class="glyphicon glyphicon-asterisk" 
																style="color: #AC6BEC;">
																</span>&nbsp;&nbsp;Very Low'
																{{ ($report->salt == 1) ? 'selected':''}}>1
										</option>
									</select>
								</div>
							</div>
						</form>
						<hr>
						<p style="float: left;">
							<a  class="btn btn-danger"
							href="{{ url('/reports/'.$report->seq_id) }}">
							<i class="glyphicon glyphicon-arrow-left"></i>&nbsp;&nbsp;&nbsp;Go back</a>
							<a  class="btn btn-success"
							href="{{ url('/reports/'.$report->seq_id) }}">
							<i class="font-icon font-icon-ok"></i>&nbsp;&nbsp;&nbsp;Save Changes</a>
						</p>
						<br>
						<br>
					</div>
			</section>
		</div>
	</div>
@endsection