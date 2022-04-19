<x-header />
<x-navbar />

<div class="page">
    <h1 class="text-center">Visits</h1>
    <x-message />
    <table class="table table-striped table-bordered my-5">
        @if ( $visits->count() == 0 )
            <p>there are no visits yet.</p>
        @else
            <thead>
                <tr>
                    <th scope="col">patientId </th>
                    <th scope="col">Date</th>
                    <th scope="col">Start Time</th>
                    <th scope="col">End Time</th>
                    <th scope="col">Visit Type</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($visits as $visit)
                <tr scope="row">
                    <td>{{ $visit->patientId }}</td>
                    <td>{{ $visit->date }}</td>
                    <td>{{ $visit->startTime }}</td>
                    <td>{{ $visit->endTime }}</td>
                    <td>{{ $visit->visitType }}</td>
                    <td class="d-flex flex-row">
                        <a href={{ url("Bills/store/".$visit->id) }} class="btn btn-primary mx-1">
                            Add bill
                        </a>
                        <a href={{ url("Visits/addTest/".$visit->id) }} class="btn btn-primary mx-1">
                            Add test
                        </a>
                        <a href={{ url("Visits/addDiagnose/".$visit->id) }} class="btn btn-primary mx-1">
                            Add diagnose
                        </a>
                        <a href={{ url("Visits/addPrescription/".$visit->id) }} class="btn btn-primary mx-1">
                            Add prescription
                        </a>
                        <a href={{ url("Bills/".$visit->id) }} class="btn btn-primary mx-1">
                            Show bill
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        @endif
    </table>
    <div class="d-flex justify-content-center">
        {!! $visits->links() !!}
    </div>
</div>
<x-footer/>
