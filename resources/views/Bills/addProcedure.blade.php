<x-header />
<x-navbar />

<div class="text-center page">
    <h1 class="text-center">Create New Procedure</h1>
    <x-errors />
    <x-message />
    <div class="d-flex justify-content-center">
        <a href={{url('printSecondReceipt/'.$id)}} class="btn btn-primary mx-1" style="max-width: 200px">
            Print Second Receipt
        </a>
    </div>
    <form action="{{url('/addProcedure')}}" method="post">
        @csrf
        <div class="form-group my-4">
            <label >Bill Id</label>
            <input readonly name="bill_id" class="form-control mx-auto" value={{$id}} >
        </div>

        <div class="form-group my-4">
            <label >Procedure Name</label>
            <select name="procedureId" value={{ old('procedureId')}} style="width: 100%" class="form-select mx-auto">
                <option disabled value=''>--choose type</option>
                @foreach ($procedures as $procedure)
                    <option value={{$procedure->id}} >{{ $procedure->serviceName }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary my-5">Add Procedure</button>
    </form>
</div>

<x-footer />
