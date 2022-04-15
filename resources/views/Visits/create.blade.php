<x-header />
<x-navbar />

<div class="text-center page">
    <h1 class="text-center">Create New Visit</h1>
    <x-errors />
    <x-message />

    <form action="{{url('/Visits')}}" method="post">
        @csrf
        <div class="form-group my-4">
            <label >Patient Id</label>
            <input readonly name="patientId" class="form-control mx-auto" value={{$id}} >
        </div>
        <div class="form-group my-4">
            <label >Date</label>
            <input class="form-control mx-auto" type="date" name="date" value={{ old('date')}}>
        </div>

        <div class="form-group my-4">
            <label >Visit Type</label>
            <select name="visitType" value={{ old('visitType')}} style="width: 100%" class="form-select mx-auto">
                <option disabled value=''>--choose type</option>
                <option value="examination">examination</option>
                <option value="consultation">consultation</option>
            </select>
        </div>

        <div class="form-group my-4 d-flex flex-column">
            <label >Start Time</label>
            <input type="time" name="startTime" class="form-control mx-auto" value={{ old('startTime')}}>
        </div>

        <button type="submit" class="btn btn-primary my-5">Add Visit</button>
    </form>
</div>

<x-footer />
