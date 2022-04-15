<x-header />
<x-navbar />

<div class="text-center page">
    <h1 class="text-center">Add new Prescription</h1>
    <x-errors />
    <x-message />

    <form action="{{url('/addPrescription')}}" method="post">
        @csrf
        <div class="form-group my-4">
            <label >Visit Id</label>
            <input readonly name="visit_id" class="form-control mx-auto" value={{$id}} >
        </div>
        <div class="form-group my-4">
            <label >Prescription</label>
            <input class="form-control mx-auto" name="name" value={{ old('name')}}>
        </div>

        <button type="submit" class="btn btn-primary my-5">Add Prescription</button>
    </form>
</div>

<x-footer />
