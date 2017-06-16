@extends("layout")
@section("content")
	<div>
		<ol class="breadcrumb">
		  <li><a href="{{{URL::route('user.home')}}}">{{trans('messages.home')}}</a></li>
		  <li>
		  	<a href="{{ URL::route('unhls_test.index') }}">{{ Lang::choice('messages.test',2) }}</a>
		  </li>
		  <li class="active">{{trans('messages.new-test')}}</li>
		</ol>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading ">
            <div class="container-fluid">
                <div class="row less-gutter">
                    <div class="col-md-11">
						<span class="glyphicon glyphicon-adjust"></span>Receive Specimen
                    </div>
                    <div class="col-md-1">
                        <a class="btn btn-sm btn-primary pull-right" href="#" onclick="window.history.back();return false;"
                            alt="{{trans('messages.back')}}" title="{{trans('messages.back')}}">
                            <span class="glyphicon glyphicon-backward"></span></a>
                    </div>
                </div>
            </div>
		</div>
		<div class="panel-body">
		<!-- if there are creation errors, they will show here -->
			@if($errors->all())
				<div class="alert alert-danger">
					{{ HTML::ul($errors->all()) }}
				</div>
			@endif
			{{ Form::open(array('route' => 'specimen.store', 'id' => 'form-new-test')) }}
			<input type="hidden" name="_token" value="{{ Session::token() }}"><!--to be removed function for csrf_token -->
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
							<div class="panel panel-info">
								<div class="panel-heading">
									<h3 class="panel-title">{{"Patient and Sample Information"}}</h3>
								</div>
									<div class="panel-body inline-display-details">
								@if($existingPatient)
									{{ Form::hidden('patient_id', $patient->id) }}
									<p>Patient No: {{$patient->patient_number}}</p>
									<p>ULIN: {{$patient->ulin}}</p>
									<p>Patient Name: {{$patient->name}}</p>
									<p>Age: {{$patient->getAge()}}</p>
									<p>Gender: {{$patient->getGender()}}</p>
								@else
									<div class="form-group">
											{{ Form::label('patient_name','Patient Name', array('text-align' => 'right', 'class' => 'required')) }}
											{{ Form::text('patient_name', Input::old('patient_name'), array('class' => 'form-control')) }}
									</div>
									<div class="form-group">
											{{ Form::label('patient_number','Patient ID', array('text-align' => 'right', 'class' => 'required')) }}
											{{ Form::text('patient_number', Input::old('patient_number'), array('class' => 'form-control')) }}
									</div>
									<div class="form-group">
										<label class= 'required' for="dob">Date Of Birth</label>
										<input type="text" name="dob" id="dob" class="form-control input-sm" size="11"> 
									</div>
									<div class="form-group">
										<label class='required' for="age">Age</label>
										<input type="text" name="age" id="age" class="form-control c input-sm" size="11">
										<select name="age_units" id="id_age_units" class="form-control input-sm">
											<option value="Y">Years</option>
											<option value="M">Months</option>
										</select>												
									</div>
									<div class="form-group">
										{{ Form::label('gender', trans('messages.sex'), array('class' => 'required')) }}
										<div>{{ Form::radio('gender', '0', true) }}
										<span class="input-tag">{{trans('messages.male')}}</span></div>
										<div>{{ Form::radio("gender", '1', false) }}
										<span class="input-tag">{{trans('messages.female')}}</span></div>
									</div>
								@endif
									<div class="form-group">
										<label for="lab_id" text-align="right">Lab ID</label>
										<input class="form-control" name="lab_id" value="{{$lab_id}}" type="text">
									</div>
									<div class="form-group">
										{{Form::label('facility', 'Facility')}}
										{{ Form::select('facility', $facilities,
										Input::get('facility'),
										['class' => 'form-control']) }}
									</div>
									<div class="form-pane panel panel-default">
										<div class="col-md-6">
											<div class="form-group">
												{{Form::label('specimen_type', 'Sample Type')}}
												{{ Form::select('specimen_type', $specimenType,
												Input::get('specimenType'),
												['class' => 'form-control specimen-type']) }}
											</div>
											<div class="form-group">
												<label for="time_collected">Time of Sample Collection</label>
												<input class="form-control"
													data-format="YYYY-MM-DD HH:mm"
													data-template="DD / MM / YYYY HH : mm"
													name="time_collected"
													type="text"
													id="collection-date"
													value="{{$collectionDate}}">
											</div>
											<div class="form-group">
												<label for="time_accepted">Time Sample was Received in Lab</label>
												<input class="form-control"
													data-format="YYYY-MM-DD HH:mm"
													data-template="DD / MM / YYYY HH : mm"
													name="time_accepted"
													type="text"
													id="reception-date"
													value="{{$receptionDate}}">
											</div>
										</div>
										<div class="col-md-6 test-type-list">
										</div>
									</div>
									</div>
								</div>
							</div> <!--div that closes the panel div for clinical and sample information -->

								<div class="form-group actions-row">
								{{ Form::button("<span class='glyphicon glyphicon-save'></span> ".trans('messages.save-test'),
									['class' => 'btn btn-primary', 'onclick' => 'submit()', 'alt' => 'save_new_test']) }}
								</div>
						</div>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
@stop
