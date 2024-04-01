crud.field('payment_for').onChange(function(field) {
    crud.field('initial_payment').show(field.value == 'Annual-Fee');
  }).change();

  crud.field('payment_for').onChange(function(field) {
    crud.field('renewal').show(field.value == 'Annual-Fee');
  }).change();

  crud.field('payment_for').onChange(function(field) {
    crud.field('annual_amount').show(field.value == 'Annual-Fee');
  }).change();

  crud.field('payment_for').onChange(function(field) {
    crud.field('annual_amount').show(field.value == 'Annual-Fee');
  }).change();

  crud.field('type').onChange(function(field) {
    crud.field('transaction_code').show(field.value == 'gcash');
  }).change();

  function updateCheckboxesFromInput() {
    var inputValue = $("#capabilities-hidden").val().split(',').map(Number);
    $(".capability-checkbox").prop("checked", false); // Uncheck all checkboxes
    for (var i = 0; i < inputValue.length; i++) {
        $(".capability-checkbox[data-value='" + inputValue[i] + "']").prop("checked", true);
    }
}
// Function to update checkboxes based on input field value
function updateCheckboxesFromInput() {
    var inputValue = $("#capabilities-hidden").val().split(',').map(Number);
    $(".capability-checkbox").prop("checked", false); // Uncheck all checkboxes
    for (var i = 0; i < inputValue.length; i++) {
        $(".capability-checkbox[data-value='" + inputValue[i] + "']").prop("checked", true);
    }
    // Check if the checkbox with value 4 is selected
    if (inputValue.includes(4)) {
        // Enable checkboxes with value 5, 6, 7, and 8
        $(".capability-checkbox[data-value='5'], .capability-checkbox[data-value='6'], .capability-checkbox[data-value='7'], .capability-checkbox[data-value='8']").prop("disabled", false);
    } else {
        // Disable checkboxes with value 5, 6, 7, and 8
        $(".capability-checkbox[data-value='5'], .capability-checkbox[data-value='6'], .capability-checkbox[data-value='7'], .capability-checkbox[data-value='8']").prop("disabled", true);
    }
}

// Function to update input field based on checkbox selections
function updateInputFromCheckboxes() {
    var selectedValues = [];
    $(".capability-checkbox:checked").each(function() {
        selectedValues.push(parseInt($(this).data("value")));
    });
    $("#capabilities-hidden").val(selectedValues.join(','));
}

// Event handler for checkbox changes
$(".capability-checkbox").change(function() {
    updateInputFromCheckboxes();
    // Check if checkbox 4 is clicked
    if ($(this).data("value") == 4) {
        // Enable checkboxes with value 5, 6, 7, and 8
        $(".capability-checkbox[data-value='5'], .capability-checkbox[data-value='6'], .capability-checkbox[data-value='7'], .capability-checkbox[data-value='8']").prop("disabled", !$(this).prop("checked"));
    }
});

// Event handler for input field changes
$("#capabilities-hidden").change(function() {
    updateCheckboxesFromInput();
});

// Initial synchronization
$(document).ready(function() {
    updateCheckboxesFromInput();
});





