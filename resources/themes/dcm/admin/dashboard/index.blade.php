@extends( "layouts.master-admin")

@section('content')

@include('admin.dashboard.partials.navigation')

<div class="row animated fadeIn">

        <div class="col-lg-8 widget-content pb-5">
            <div class="block">
                    <div class="block-content block-content-full">
                        <p class="text-uppercase font-w600 text-center mb-4">
                            Total user registered per month
                        </p>
                        <canvas id="chart-line"></canvas>
                    </div>

            </div>


        </div>
        <div class="col-lg-4 widget-content pb-5">
            @include('admin.dashboard.partials.users')
        </div>

        <div class="col-md-6 col-xl-6">
            @include('admin.dashboard.partials.most-viewed-apps')
        </div>
        <div class="col-md-6 col-xl-6">
            @include('admin.dashboard.partials.categories')
        </div>

</div>


@endsection


@push('javascript')
<script src="{{ asset('js/common/chart.min.js') }}"></script>
<script>
    $(document).ready(function(){
        var monthlyData = JSON.parse('{!! fixedJSON($dashboard["total_users_groupby_month"]) !!}');
        new Chart('chart-line', {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: '#EC807A',
                data: Object.values(monthlyData)
                }]
            },
            options: {
                maintainAspectRatio: true,
                elements: {
                line: {
                    tension: 0.4,
                    "borderWidth": 2
                }
                },
                legend: {display: false},
                scales: {
                    yAxes: [{
                        ticks: {
                        fontColor: "#999999"
                        }
                    }],
                    xAxes: [{
                        ticks: {
                        fontColor: "#999999"
                        }
                    }]
                }
            }
        });
    });
</script>
@endpush