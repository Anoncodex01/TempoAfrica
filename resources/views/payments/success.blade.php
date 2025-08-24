@extends('layouts.app-mobile')
@section('content')
    <!-- [ Main Content ] start -->
    <div class="container-fluid">
        <div class="pc-content">
            <!-- [ Main Content ] start -->
            <div class="row justify-content-center">
                <!-- [ sample-page ] start -->
                <div class="col-md-12 col-lg-12">
                    <div class="card price-card" style="background-color: #f7f7f7">
                        <div class="card-body">
                            <div class="price-icon">
                                <img src="{{ asset('ui/dist/assets/images/success.gif') }}" width="100%" alt="img"
                                    class="img-fluid">
                                <h4 class="mb-0"></h4>
                            </div>
                            <h4 class="text-center text-success mt-3">Payment successful</h4>
                            <p class="mt-4 text-justify">Thank you! Your payment has been completed successfully. We
                                appreciate your
                                trust and are processing your request accordingly.</p>

                            <p class="mt-4">You will receive a confirmation shortly through your registered contact
                                details. Please keep your transaction reference safe for future use or tracking.</p>

                            <p class="mt-4">If you have any questions or concerns, feel free to reach out to us. You can
                                call 0714335524 (toll-free) or WhatsApp us at 0714335524 — we’re happy to assist you.
                            </p>

                            {{-- <div class="d-grid"><a class="btn btn-outline-primary bg-light text-primary mb-4" href="#"
                                    role="button">OK</a></div> --}}

                        </div>
                    </div>
                </div>

            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection
