@extends(backpack_view('blank'))

@section('header')
    <section class="container-fluid">
        <h2 class="text-center">
            <span class="text-capitalize">Payment for Members</span>
            <small> {!! $entry->name !!}</small>
            @if ($crud->hasAccess('list'))
                <small>
                    <a href="{{ url($crud->route) }}" class="d-print-none font-sm">
                        <i
                            class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i>
                        {{ trans('backpack::crud.back_to_all') }}
                        <span>{{ $crud->entity_name_plural }}</span>
                    </a>
                </small>
            @endif
        </h2>
    </section>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="post">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            @if ($membership === null)
                                <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"
                                        viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                        <path
                                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                    </svg>
                                    <div>
                                        Annual Fee is payment required.
                                    </div>
                                </div>
                            @elseif($membership->annual_status === 'cancelled')
                                <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"
                                        viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                        <path
                                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                    </svg>
                                    <div>
                                        Please Renew Annual Fee!
                                    </div>
                                </div>
                            @elseif ($membership->annual_status === 'active')
                                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                    </symbol>
                                    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                                        <path
                                            d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                                    </symbol>
                                    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                                        <path
                                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                    </symbol>
                                </svg>
                                <div class="alert alert-success d-flex align-items-center" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                                        aria-label="Info:">
                                        <use xlink:href="#info-fill" />
                                    </svg>
                                    <div>
                                        <strong>Welcome!</strong> Select Plan and become John Cena.
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="font-weight-bold">Subscription Plan</label>
                                <select name="payment_for" class="form-control @error('payment_for') is-invalid @enderror">
                                    @if ($membership && $membership->annual_status === 'Active')
                                        <option disabled selected>---Select Payment---</option>
                                        @if (($membership && $membership->status === 'Expired') || $membership->status == null)
                                            <option value="session">Session Subscription</option>
                                        @endif
                                        <option value="monthly">Monthly Subscription</option>
                                        <option value="bi-monthly">Bi-Monthly Subscription</option>
                                        <option value="6-months">6 Months Subscription</option>
                                        <option value="1-year">1 Year Subscription</option>
                                    @endif
                                    @if ($membership == null || $membership->annual_status !== 'Active')
                                        <option value="Annual-Fee">Annual-Fee</option>
                                    @endif
                                </select>
                                @error('payment_for')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Payment Type</label>
                                <select id="payment_type2" name="type"
                                    class="form-control @error('type') is-invalid @enderror">
                                    <option disabled selected>Select Payment Type</option>
                                    <option value="cash">Cash Payment</option>
                                    <option value="gcash">G-cash Payment</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="transaction_code_field" class="form-group">
                                <label>Transaction Code</label>
                                <input type="text" name="transaction_code" value="{{ old('amount') }}"
                                    class="form-control @error('transaction_code') is-invalid @enderror">
                                @error('transaction_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" name="amount" value="{{ old('amount') }}"
                                    class="form-control @error('amount') is-invalid @enderror">
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <input type="hidden" name="_save_action" value="make_payment">
                        <button type="submit" class="btn btn-success"><i class="la la-save"></i> Make Payment</button>
                        <a href="{{ url($crud->route) }}" class="btn btn-danger"><i class="la la-arrow-left"></i>Go
                            Back</a>
                    </div>
                </form>
                <div class="card">
                    <div class="card-body">
                        @php
                            $firstMemberFullName = $payments->isNotEmpty()
                                ? $payments->first()->member->full_name
                                : null;
                        @endphp

                        @if ($firstMemberFullName)
                            <h5 class="card-title">Payment History: <strong>{{ $firstMemberFullName }}</strong></h5>
                            @foreach ($payments as $payment)
                            @endforeach
                        @endif

                        @if ($payments->isNotEmpty())
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Payment Date</th>
                                        <th>Payment Method</th>
                                        <th>Payment Type</th>
                                        <th>Transaction code</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->created_at->format('F d h:i A') }}</td>
                                            <td>{{ $payment->payment_for }}</td>
                                            <td>{{ ucfirst($payment->type) }}</td>
                                            <td>{{ $payment->transaction_code !== null ? $payment->transaction_code : 'N/A' }}
                                            </td>
                                            <td>₱{{ $payment->amount }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No payment records found for this member.</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        function toggleTransactionCodeField() {
            var paymentType = document.getElementById('payment_type2').value;
            var transactionCodeField = document.getElementById('transaction_code_field');

            if (paymentType === 'gcash') {
                transactionCodeField.style.display = 'block';
            } else {
                transactionCodeField.style.display = 'none';
            }
        }
        toggleTransactionCodeField();
        document.getElementById('payment_type2').addEventListener('change', function() {
            toggleTransactionCodeField();
        });
    </script>

@endsection
