@extends('dashboard.layouts.app')

@section('container')
    <!-- row 1 -->
    <div class="flex flex-wrap -mx-3">
        <!-- card1 -->
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">
                    <div class="flex flex-row -mx-3">
                        <div class="flex-none w-2/3 max-w-full px-3">
                            <div>
                                <p class="mb-0 font-semibold leading-normal text-sm">about</p>

                            </div>
                        </div>
                        <div class="px-3 text-right basis-1/3">
                            <div class="flex justify-center items-center w-12 h-12 rounded-lg bg-gradient-to-tl from-backgroundSecondary to-greenSecondary">
                                <i class="ri-table-fill text-2xl text-greenPrimary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


@endsection

@section('js')
    <script>
        var ctx = document.getElementById("chart-bars").getContext("2d");



        new Chart(ctx, {
        type: "bar",
        data: {
            labels: kriteriaId,
            datasets: [
            {
                label: "Bobot",
                tension: 0.4,
                borderWidth: 0,
                borderRadius: 4,
                borderSkipped: false,
                backgroundColor: "#fff",
                data: kriteriaBobot,
                maxBarThickness: 6,
            },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
            legend: {
                display: false,
            },
            },
            interaction: {
            intersect: false,
            mode: "index",
            },
            scales: {
            y: {
                grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: false,
                drawTicks: false,
                },
                ticks: {
                suggestedMin: 0,
                suggestedMax: 600,
                beginAtZero: true,
                padding: 15,
                font: {
                    size: 14,
                    family: "Open Sans",
                    style: "normal",
                    lineHeight: 2,
                },
                color: "#fff",
                },
            },
            x: {
                grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: false,
                drawTicks: false,
                },
                ticks: {
                display: false,
                },
            },
            },
        },
        });

        // end chart 1

    </script>

@endsection
