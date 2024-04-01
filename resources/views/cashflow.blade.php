@extends(backpack_view('blank'))

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h4 class="mb-4">Cashflow</h4>

                <div class="input-group">
                    <label for="filterType" class="mr-2 mt-1 font-weight-bold">Filter Type &nbsp; </label>
                    <select class="form-select me-4" id="filterType">
                        <option selected disabled>Custom Date</option>

                        <option value="session">Sessions</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                        <option value="cash">Cash Payments</option>
                        <option value="gcash">GCash Payments</option>
                    </select>

                    <label for="startDay" class="mr-2 mt-1 font-weight-bold">Start Date &nbsp; </label>
                    <input type="date" class="form-control me-4" name="startDate" id="startDay"
                        placeholder="Select Date">

                    <label for="endDay" class="mr-2 mt-1 font-weight-bold">End Date &nbsp; </label>
                    <input type="date" class="form-control me-4" name="endDate" id="endDay" placeholder="Select Date">

                    <div class="input-group-append">
                        <button type="button" class="btn btn-info" id="refreshButton">
                            Clear Filter
                        </button>
                        <button type="button" class="btn btn-warning" id="filterButton">
                            Search
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3" id="filterOptions">

        </div>
    </div>

    <div class="row mt-3" id="chartContainer" style="display: none;">
        <div class="col-md-6">
            <canvas id="cashChart" width="400" height="200"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="gcashChart" width="400" height="200"></canvas>
        </div>
    </div>
    <div class="row mt-3" id="totalAmounts" style="display: none;">
        <div class="col-md-12">
            @php
                $combinedTotal = $totalCash + $totalGcash;
            @endphp
            <div class="alert alert-dark" role="alert" id="combinedTotalAlert">
                Total Income (Cash + GCash): ₱ {{ $combinedTotal }}
            </div>
        </div>
    </div>
    <table class="table mt-4">
        <thead>
            <tr>

            </tr>
        </thead>
        <tbody id="filteredData">
            <tr>
                <td>
                    <div class="card text-center">
                        <div class="card-header">
                            Welcome to cashflow!
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Show total by filtering</h5>
                            <p class="card-text">If you need to view payments for a member, click the button below</p>
                            <a href="{{ backpack_url('payments') }}" class="btn btn-primary">Payments</a>
                        </div>
                        <div class="card-footer text-muted">
                            {{ date('F d Y') }}
                        </div>
                    </div>

                </td>
            </tr>
        </tbody>

    </table>
@endsection

@push('after_scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function() {
            var cashChart = null;
            var gcashChart = null;

            $('#toggleCharts').click(function() {
                $('#chartContainer').toggle();
                $('#totalAmounts').toggle();
                if ($('#chartContainer').is(':visible')) {

                    updateCharts();
                } else {

                    if (cashChart) {
                        cashChart.destroy();
                    }
                    if (gcashChart) {
                        gcashChart.destroy();
                    }
                }
            });

            $('#filterType').change(function() {
                var filterType = $(this).val();
                var startDate = getStartDate(filterType);
                var endDate = getEndDate(filterType);
                $('#startDay').val(startDate);
                $('#endDay').val(endDate);
                if (filterType === 'session' || filterType === 'week' || filterType === 'month' ||
                    filterType === 'quarter' || filterType === 'year') {
                    $('#startDay').prop('disabled', true);
                    $('#endDay').prop('disabled', true);
                } else {
                    $('#startDay').prop('disabled', false);
                    $('#endDay').prop('disabled', false);
                }
                $('#filterOptions').html(`<div class="alert alert-dark alert-dismissible fade show" role="alert">
                <strong>Filter Selected:</strong> ${filterType}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`);
                updateTotalIncome();
            });

            $('#refreshButton').click(function() {
                location.reload();
            });

            function getStartDate(filterType) {
                var startDate = new Date();
                if (filterType === 'week') {
                    startDate.setDate(startDate.getDate() - startDate.getDay());
                } else if (filterType === 'month') {
                    startDate.setDate(1);
                } else if (filterType === 'quarter') {

                } else if (filterType === 'year') {
                    startDate.setMonth(0, 1);
                }
                return formatDate(startDate);
            }

            function getEndDate(filterType) {
                var endDate = new Date();
                if (filterType === 'week') {
                    endDate.setDate(endDate.getDate() - endDate.getDay() + 6);
                } else if (filterType === 'month') {
                    endDate.setMonth(endDate.getMonth() + 1, 0);
                } else if (filterType === 'quarter') {
                    endDate.setMonth(endDate.getMonth() + 3);
                } else if (filterType === 'year') {
                    endDate.setMonth(11, 31);
                }
                return formatDate(endDate);
            }

            function getDaysInMonth(month, year) {
                return new Date(year, month, 0).getDate();
            }

            function formatDate(date) {
                var yyyy = date.getFullYear();
                var mm = String(date.getMonth() + 1).padStart(2, '0');
                var dd = String(date.getDate()).padStart(2, '0');
                return `${yyyy}-${mm}-${dd}`;
            }


            $('#filterButton').click(function() {
                var startDate = $('#startDay').val();
                var endDate = $('#endDay').val();
                var filterType = $('#filterType').val();
                filterData3(startDate, endDate, filterType);
            });


            function filterData3(startDate, endDate, filterType) {
                $.ajax({
                    url: '{{ route('filterData3') }}',
                    type: 'GET',
                    data: {
                        startDate: startDate,
                        endDate: endDate,
                        filterType: filterType
                    },
                    success: function(response) {
                        $('#filteredData').html(response);

                        updateCharts();
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }

            function updateCharts() {

                var cashChartData = {
                    labels: ['Total Cash Payment'],
                    datasets: [{
                        label: 'Cash Payments',
                        data: [{{ $totalCash }}],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                };

                var gcashChartData = {
                    labels: ['Total GCash Payments'],
                    datasets: [{
                        label: 'GCash Payments',
                        data: [{{ $totalGcash }}],
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                };


                var chartOptions = {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                };

                if (cashChart) {
                    cashChart.destroy();
                }
                if (gcashChart) {
                    gcashChart.destroy();
                }

                var cashChartCtx = document.getElementById('cashChart').getContext('2d');
                cashChart = new Chart(cashChartCtx, {
                    type: 'bar',
                    data: cashChartData,
                    options: chartOptions
                });

                var gcashChartCtx = document.getElementById('gcashChart').getContext('2d');
                gcashChart = new Chart(gcashChartCtx, {
                    type: 'bar',
                    data: gcashChartData,
                    options: chartOptions
                });
            }

            function updateTotalIncome() {
                var totalIncome = 0;
                $('#filteredData tr').each(function() {
                    var amount = parseFloat($(this).find('td:nth-child(2)').text());
                    if (!isNaN(amount)) {
                        totalIncome += amount;
                    }
                });
                $('#totalIncomeAlert').text('Total Income Based on Filtered Results: ₱ ' + totalIncome.toFixed(2));
            }
        });
    </script>
@endpush
