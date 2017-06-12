<!DOCTYPE html>
<html>
  <head>
    <style type="text/css">
    table {
      border-spacing: 0;
      width: 100%;
    }
    th,
    td {
      padding: 10px 15px;
      text-align: left;
    }

    .report-head tr td, .report-head tr th {
      padding: 1px 1px;
      text-align: left;
    }
    .report-body tr td {
      border-top: 1px solid #cecfd5;
    }
    td.organism,
    td.antibiotic,
    td.comment {
      width: 154px;
    }

    td.result {
      width: 115px;
    }

    .ast-head tr th {
      border-bottom: 1px solid #cecfd5;
    }
    .ast-body tr td.organism,
    .ast-body tr:last-child td {
      border-bottom: 1px solid #cecfd5;
    }
    tfoot tr th, tfoot tr td{
      border-top: 1px solid #cecfd5;
    }
    tfoot tr:first-child th, tfoot tr:first-child td{
      border-top: 0;
    }

    caption{
      text-align: left;
    }

  body{
    font-size:12px;
   }


   .ast-table { page-break-inside:avoid; page-break-after:auto }
    </style>
  </head>
  <body>
        @include("reportHeader")
    <table class="report-head">
      <tbody>
        <tr>
          <th>Facility Name</th>
          <td>{{$specimen->referral->facility->name}}</td>
          <th>Date Received</th>
          <td>{{$specimen->time_accepted}}</td>
        </tr>
        <tr>
          <th>Patient Name</th>
          <td>{{ $specimen->patient->name }}</td>
          <th>Specimen Type</th>
          <td>{{ $specimen->specimenType->name }}</td>
        </tr>
        <tr>
          <th>{{ trans('messages.patient-id')}}</th>
          <td>{{ $specimen->patient->patient_number}}</td>
          <th>Lab ID</th>
          <td>{{ $specimen->lab_id }}</td>
        </tr>
        <tr>
          <th>{{ trans('messages.gender')}} & {{ trans('messages.age')}}</th>
          <td>{{ $specimen->patient->getGender(false) }} | {{ $specimen->patient->getAge()}}</td>
          <!-- todo: uncomment when functionality is done -->
          <th><!-- Study No. --></th>
          <td></td>
        </tr>
      </tbody>
    </table>
<br>
		<table>
         <caption>Laboratory Findings</caption>
         <tbody class="report-body">
            @forelse($specimen->tests as $test)
                  @if(!$test->testType->isCulture() && $test->isCompleted())
                  <tr>
                     <td>{{ $test->testType->name }}</td>
                     <td colspan="2">
                        @foreach($test->testResults as $result)
                          @if($test->measures->count() > 1)
                          {{ Measure::find($result->measure_id)->name }}:
                          @endif
                          {{ $result->result }}
                          {{ Measure::getRange($test->specimen->patient, $result->measure_id) }}
                          {{ Measure::find($result->measure_id)->unit }}
                        @endforeach
                     </td>
                  </tr>
                  @endif
            @empty
               <tr>
                  <td colspan="3">{{trans("messages.no-records-found")}}</td>
               </tr>
            @endforelse
         </tbody>
      </table>
        </br>
        @foreach($specimen->tests as $test)
        @if($test->testType->isCulture())
        <!-- Culture and Sensitivity analysis -->
        <table>
          <caption>Antimicrobial Susceptibility Testing(AST)</caption>
          <thead class="ast-head">
            <tr>
                <th class="organism" scope="col">Organism(s)</th>
                <th class="antibiotic" scope="col">Antibiotic(s)</th>
                <th class="result" scope="col">Result(s)</th>
                <th class="comment" scope="col">Comment(s)</th>
            </tr>
          </thead>
        </table>
        @foreach($test->isolated_organisms as $isolated_organism)
        <table class="ast-table">
            <tbody class="ast-body">
              <tr>
                <td rowspan="{{$isolated_organism->drug_susceptibilities->count()}}"
                  class="organism">{{$isolated_organism->organism->name}}</td>
                  <?php $i = 1; ?>
                @foreach($isolated_organism->drug_susceptibilities as $drug_susceptibility)
                @if ($i > 1)
              <tr>
                @endif <?php $i++; ?>
                <td class="antibiotic">{{$drug_susceptibility->drug->name}}</td>
                <td class="result">{{$drug_susceptibility->drug_susceptibility_measure->symbol}}</td>
                <td class="comment">-</td>
              </tr>
              @endforeach
            </tbody>
        </table>
        @endforeach

        <table>
          <tbody>
            <tr>
              <td>Comment(s)</td>
              <td colspan="2">
              {{$test->interpretation}}
              </td>
            </tr>
          </tbody>
        </table>

        </hr>

        <table>
          <tbody class="report-body">
            <tr>
               <td>Result Guide</td>
               <td>S-Sensitive | R-Resistant | I-Intermediate</td>
            </tr>
          </tbody>
        </table>
        @endif
        @endforeach

        <hr style="border: 1px solid;">
        <table>
          <tbody>
        @if($test->isCompleted())
            <tr>
              <th>Test/Analysis Performed by:</th>
              <!-- todo: asks the question is it the same person to do all the tests -->
              <td>{{$specimen->tests->first()->testedBy->name}}</td>
              <td>Signature:</td>
            </tr>
        @endif
        @if($test->isVerified())
            <tr>
              <th>Reviewed by:</th>
              <td>{{$specimen->tests->first()->testedBy->name}}</td>
              <td>Signature:</td>
            </tr>
        @endif
          </tbody>
        </table>
        <table>
          <tbody>
            <tr>
              <td>Date Of Results Dispatch: {{$printTime}}</td>
            </tr>
          </tbody>
        </table>
        <p style="text-align: right;">1 of 1</p>
  </body>
</html>