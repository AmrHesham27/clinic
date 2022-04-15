<x-header />
<x-navbar />

<div class="page">
    <x-message/>
    <div class="d-felx flex-column jsutify-content-center">
        <h3>Receipt</h3>
        <p>Date : {{ $date }}</p>
        <p>Time : {{ $time }}</p>

        @foreach ($procedures as $procedure)
            <p>{{$procedure->name.' : '.$procedure->price }}</p>
        @endforeach

        <p>Total : {{ $fees }}</p>
    </div>
</div>
<x-footer/>
