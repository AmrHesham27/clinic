<x-header />
<x-navbar />

<div class="page">
    <h1 class="text-center">Show Visit</h1>
    <x-message />
    <table class="table table-striped table-bordered my-5">
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
                <tr scope="row">
                    <td>{{ $lastVisit->patientId }}</td>
                    <td>{{ $lastVisit->date }}</td>
                    <td>{{ $lastVisit->startTime }}</td>
                    <td>{{ $lastVisit->endTime }}</td>
                    <td>{{ $lastVisit->visitType }}</td>
                    <td class="d-flex flex-row">
                        <a href={{ url("Bills/store/".$lastVisit->id) }} class="btn btn-success mx-1">
                            Add bill
                        </a>
                        <a href={{ url("Visits/addTest/".$lastVisit->id) }} class="btn btn-primary mx-1">
                            Add test
                        </a>
                        <a href={{ url("Visits/addDiagnose/".$lastVisit->id) }} class="btn btn-primary mx-1">
                            Add diagnose
                        </a>
                        <a href={{ url("Visits/addPrescription/".$lastVisit->id) }} class="btn btn-primary mx-1">
                            Add prescription
                        </a>
                        <a href={{ url("Bills/".$lastVisit->id) }} class="btn btn-success mx-1">
                            Show bill
                        </a>
                    </td>
                </tr>
            </tbody>
    </table>
</div>
<x-footer/>
