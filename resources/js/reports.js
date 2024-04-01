
$(document).ready(function(){
    // Function to handle filter type change
    $('#filterType').change(function() {
        var filterType = $(this).val();
        var startDate = getStartDate(filterType);
        var endDate = getEndDate(filterType);
        $('#startDay').val(startDate);
        $('#endDay').val(endDate);

        // Disable the date input when "This Week", "This Month", "This Quarter", or "This Year" is selected
        if (filterType === 'week' || filterType === 'month' || filterType === 'quarter' || filterType === 'year') {
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
            $('#filterType').val('Custom Date').change(); // Reset dropdown to "Custom Date"
            // Remove the filter option from display
            $('#filterOptions').html('');
        };
    $('#refreshButton').click(function() {
        location.reload(); // Reload the page
    });

    // Function to get start date based on filter type
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

    // Function to get end date based on filter type
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

    // Function to format date as yyyy-mm-dd
    function formatDate(date) {
        var yyyy = date.getFullYear();
        var mm = String(date.getMonth() + 1).padStart(2, '0');
        var dd = String(date.getDate()).padStart(2, '0');
        return `${yyyy}-${mm}-${dd}`;
    }

    // Function to handle filter button click
    $('#filterButton').click(function(){
        var startDate = $('#startDay').val();
        var endDate = $('#endDay').val();
        var filterType = $('#filterType').val();
        filterData(startDate, endDate, filterType);
    });

    // Function to filter data
    function filterData(startDate, endDate, filterType) {
        $.ajax({
            url: '{{ route("filterData") }}',
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

