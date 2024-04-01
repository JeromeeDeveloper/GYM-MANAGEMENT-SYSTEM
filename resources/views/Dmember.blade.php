@extends(backpack_view('blank'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Gym Members Report</h2>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <canvas id="activeUsersChart" width="400" height="300"></canvas>
                    </div>
                    <div class="col-md-4">
                        <canvas id="expiredUsersChart" width="400" height="300"></canvas>
                    </div>
                    <div class="col-md-4">
                        <canvas id="cancelledUsersChart" width="400" height="300"></canvas>
                    </div>
                </div>

                <h4 class="mb-4">Payments</h4>

                <div class="input-group">
                    <label for="filterType" class="mr-2 mt-1 font-weight-bold">Filter Type &nbsp; </label>
                    <select class="form-select me-4" id="filterType">
                        <option selected disabled>Custom Date</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
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


    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="members-table" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Gym Code</th>
                            <th>Full Name</th>
                            <th>Annual Status</th>
                            <th>Subscription Status</th>
                            <th>Subscription End Date</th>
                            <th>Annual End Date</th>
                        </tr>
                    </thead>
                    <tbody id="filteredData">
                        @foreach ($members as $member)
                            <tr>
                                <td>{{ $member->code }}</td>
                                <td>{{ $member->full_name }}</td>
                                <td>{{ optional($member->membership)->annual_status }}</td>
                                <td>{{ optional($member->membership)->status }}</td>
                                <td>{{ optional($member->membership)->end_date ? \Carbon\Carbon::parse($member->membership->end_date)->format('F j, Y g:i A') : '' }}
                                </td>
                                <td>{{ optional($member->membership)->annual_end_date ? \Carbon\Carbon::parse($member->membership->annual_end_date)->format('F j, Y g:i A') : '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            {{ $members->links() }}
        </div>
    </div>
    </div>
    </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js">
    </script>
    <script type="text/javascript" charset="utf8"
        src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
     
            $('#filterType').change(function() {
                var filterType = $(this).val();
                var startDate = getStartDate(filterType);
                var endDate = getEndDate(filterType);
                $('#startDay').val(startDate);
                $('#endDay').val(endDate);
                if (filterType === 'week' || filterType === 'month' || filterType === 'quarter' ||
                    filterType === 'year') {
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
            });
            window.removeFilterOption = function(filterType) {
                $('#filterType').val('Custom Date').change();

                $('#filterOptions').html('');
            };
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
                filterData4(startDate, endDate, filterType);
            });

            function filterData4(startDate, endDate, filterType) {
                $.ajax({
                    url: '{{ route('filterData4') }}',
                    type: 'GET',
                    data: {
                        startDate: startDate,
                        endDate: endDate,
                        filterType: filterType

                    },
                    success: function(response) {
                        if (response.trim() === '') {
                            $('#filteredData').html('<tr><td colspan="4">No records found</td></tr>');
                        } else {
                            $('#filteredData').html(response);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }
        });

        function exportTable(format) {
            var table = $('#members-table').DataTable();
            table.buttons.exportData({
                format: format
            });
        }

        var activeUsersChartCtx = document.getElementById('activeUsersChart').getContext('2d');
        var expiredUsersChartCtx = document.getElementById('expiredUsersChart').getContext('2d');
        var cancelledUsersChartCtx = document.getElementById('cancelledUsersChart').getContext('2d');

        var activeUsersChart = new Chart(activeUsersChartCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($activeUsersData->keys()) !!},
                datasets: [{
                    label: 'Active Gym Members all time',
                    data: {!! json_encode($activeUsersData->values()) !!},
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var expiredUsersChart = new Chart(expiredUsersChartCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($expiredUsersData->keys()) !!},
                datasets: [{
                    label: 'Gym Members Expired Subscription Plan',
                    data: {!! json_encode($expiredUsersData->values()) !!},
                    fill: false,
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var cancelledUsersChart = new Chart(cancelledUsersChartCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($cancelledUsersData->keys()) !!},
                datasets: [{
                    label: 'Gym Members Cancelled Annual Fee',
                    data: {!! json_encode($cancelledUsersData->values()) !!},
                    fill: false,
                    borderColor: 'rgb(54, 162, 235)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
