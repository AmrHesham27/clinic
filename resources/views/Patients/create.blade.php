<x-header />
<x-navbar />

<div class="text-center page">
    <h1 class="text-center">Add Patient</h1>
    <x-errors />
    <x-message />
    <form action="{{url('/Patients')}}" method="post">
        @csrf
        <div class="form-group my-4">
            <label >Name</label>
            <input class="form-control  mx-auto" placeholder="enter patient Name" name="patientName" value={{ old('patientName')}}>
        </div>

        <div class="form-group my-4">
            <label>Age</label>
            <input class="form-control  mx-auto" placeholder="enter age" name="age" value={{ old('age')}}>
        </div>

        <div class="form-group my-4">
            <label>Address</label>
            <input class="form-control  mx-auto" placeholder="enter address" name="address" value={{ old('address')}}>
        </div>

        <div class="form-group my-4">
            <label>Phone Number</label>
            <input class="form-control  mx-auto"  placeholder="010555666777" name="phoneNumber" value={{ old('phoneNumber')}}>
        </div>

        <button class="btn btn-primary my-5">Add Patient</button>
    </form>
</div>

<x-footer />
