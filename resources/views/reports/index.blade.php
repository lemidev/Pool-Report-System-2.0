@extends('layouts.app')

@section('content')
<header class="section-header">
	<div class="tbl">
		<div class="tbl-row">
			<div class="tbl-cell">
				<h3>All Reports</h3>
				<ol class="breadcrumb breadcrumb-simple">
					<li class="active">Reports</li>
				</ol>
			</div>
		</div>
	</div>
</header>
<div class="row">
	<div class="col-xl-2">
		<section class='box-typical'>
			<section class="calendar-page-side-section">
				<div class="calendar-page-side-section-in">
					<div class="datepicker-inline" id="side-datetimepicker"></div>
				</div>
			</section>
		</section>
	</div>
	<div class="col-xl-10">
		<section class="box-typical">
			<div id="toolbar">
				<a href="{{ url('reports/create') }}" class="btn btn-primary">
					<i class="font-icon font-icon-page"></i>&nbsp;&nbsp;&nbsp;New Report
				</a>
			</div>
			<div class="table-responsive">
				<table id="generic_table"
					   data-toolbar="#toolbar"
					   data-url='{{ $default_table_url }}'
					   data-page-list='[5, 10, 20, 50, 100, 200]'
					   data-search='true'
					   data-show-export="true"
					   data-export-types="['excel', 'pdf']"
					   data-minimum-count-columns="2"
					   data-show-footer="false"
					   >
		            <thead>
				        <tr>
				            <th data-field="id">#</th>
					        <th data-field="service">Service</th>
					        <th data-field="on_time">On time</th>
					        <th data-field="technician">Technician</th>
				        </tr>
		            </thead>
		        </table>
			</div>
		</section><!--.box-typical-->
	</div>
</div><!--.row-->
@endsection
