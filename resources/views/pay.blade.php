@extends(backpack_view('blank'))

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
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
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Member Name</th>
                        <th scope="col">Paid Amount</th>
                        <th scope="col">Payment Method</th>
                        <th scope="col">Subscription Plan</th>
                    </tr>
                </thead>
                <tbody id="filteredData">
                    @foreach ($members as $member)
                        <tr>
                            <td>{{ $member->member->full_name }}</td>
                            <td>{{ $member->amount }}</td>
                            <td>{{ $member->type }}</td>
                            <td>{{ $member->payment_for }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div>
        {{ $members->links() }}
    </div>
@endsection
@push('after_scripts')
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
                filterData2(startDate, endDate, filterType);
            });

            function filterData2(startDate, endDate, filterType) {
                $.ajax({
                    url: '{{ route('filterData2') }}',
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
    </script>
@endpush
