<x-header />
<x-navbar />

<div class="page">
    <!-- table of available hours -->
    <h1 class="text-center">Check Date</h1>
    <x-message />
    <x-errors />

    <form action="{{url('/checkDate')}}" method="post">
        @csrf
        <div class="form-group my-4">
            <input class="form-control mx-auto" type="date" name="date" value={{ old('date')}}>
        </div>
        <div class="form-group my-4 d-flex">
            <button class="btn btn-primary my-5 mx-auto">Check Date</button>
        </div>
    </form>

    @if ($date)
    <h6 class="mt-5">Schedule of {{ $date }}</h6>
    <p>Green hours are availabel and red are reserved.</p>
    <table>
        <thead>
            <tr>
                @if ( count($workingHours) == 0 )
                    <div class="alert alert-danger">
                        There are no working hours in this day
                    </div>
                @endif
                @foreach ($workingHours as $hour)
                    @php
                        if(in_array($hour, $startTimes)){
                            $color = "red";
                        }
                        else {
                            $color = "green";
                        }
                    @endphp
                    <th style="background:{{$color}}; border:1px solid black" scope="col" class="text-center">
                        {{ date( 'g:ia', strtotime($hour) ) }}
                    </th>
                @endforeach
            </tr>
        </thead>
    </table>
    @endif
</div>
<x-footer/>
