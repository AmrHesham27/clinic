<x-header />
<x-navbar />

<div class="page">
    <h1 class="text-center">Bills</h1>
    <x-message />
    <table class="table table-striped table-bordered my-5">
        @if ( $all_bills->count() == 0 )
            <div class="alert alert-danger">
                <p>there are no bills yet.</p>
            </div>
        @else
            <thead>
                <tr>
                    <th scope="col">Visit Id </th>
                    <th scope="col">Total</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($all_bills as $bill)
                <tr scope="row">
                    <td>{{ $bill->visit_id }}</td>
                    <td>{{ $bill->total }}</td>
                    <td class="d-flex flex-row">
                        <a href={{url('printFirstReceipt/'.$bill->id)}} class="btn btn-primary mx-1">
                            Print First Receipt
                        </a>
                        <a href={{url('printSecondReceipt/'.$bill->id)}} class="btn btn-primary mx-1">
                            Print Second Receipt
                        </a>
                        <a href={{url('/Bills/addProcedure/'.$bill->id)}} class="btn btn-success mx-1">
                            Add Procedure
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        @endif
    </table>
    <div class="d-flex justify-content-center">
        {!! $all_bills->links() !!}
    </div>
</div>
<x-footer/>
