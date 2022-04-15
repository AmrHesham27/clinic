<x-header />
<x-navbar />

<div class="page">
    <x-message/>
    <div class="d-felx flex-column jsutify-content-center">
        <h3>Receipt</h3>
        <p>Date : {{ $date }}</p>
        <p>Time : {{ $time }}</p>
        <p>Fees : {{ $fees }}</p>
    </div>
</div>
<x-footer/>
