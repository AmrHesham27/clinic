<x-header />
<x-navbar />

<div class="page">
    <h1 class="text-center">Patients</h1>
    <x-message />
    <div class="d-flex justify-content-center">
        <a href={{url('Patients/create')}} class="btn btn-primary mx-1" style="max-width: 200px">
            Add Patient
        </a>
    </div>
    <table class="table table-striped table-bordered my-5">
        @if ( $all_patients->count() == 0 )
            <p>there are no patients yet.</p>
        @else
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Age</th>
                    <th scope="col">Address</th>
                    <th scope="col">Phone Number</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($all_patients as $patient)
                <tr scope="row">
                    <td>{{ $patient->patientName }}</td>
                    <td>{{ $patient->age }}</td>
                    <td>{{ $patient->address }}</td>
                    <td>{{ $patient->phoneNumber }}</td>
                    <td class="d-flex flex-row">
                        <a href='/Visits/create/{{$patient->id}}' class="btn btn-success mx-1">Add Visit</a>
                        <a href='/Visits/{{$patient->id}}' class="btn btn-primary mx-1"> show last visit </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        @endif
    </table>
    <div class="d-flex justify-content-center">
        {!! $all_patients->links() !!}
    </div>
</div>
<x-footer/>
