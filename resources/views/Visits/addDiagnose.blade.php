<x-header />
<x-navbar />

<div class="text-center page">
    <h1 class="text-center">Add new Diagnose</h1>
    <x-errors />
    <x-message />

    <form action="{{url('/addDiagnose')}}" method="post">
        @csrf
        <div class="form-group my-4">
            <label >Visit Id</label>
            <input readonly name="visit_id" class="form-control mx-auto" value={{$id}} >
        </div>
        <div class="form-group my-4">
            <label >Diagnosis</label>
            <input class="form-control mx-auto" name="diagnosis" value={{ old('diagnosis')}}>
        </div>

        <button type="submit" class="btn btn-primary my-5">Add Diagnose</button>
    </form>
</div>

<x-footer />
