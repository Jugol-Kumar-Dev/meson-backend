@extends('master')
@section('content')

    <section class="py-10 lg:py-52 no-reapet bg-cover" style="background-image: url('./image/course-bg.webp');">
        <div class="container mx-auto max-w-xl bg-white rounded">
            <div class="p-5 flex items-center justify-center flex-col">
                <p class="text-3xl font-bold mb-3">Account Activation Required</p>
                <p class="text-md font-semibold mb-3 text-center">Thank you for registering! Your account has been created, but active not yeat. it needs to be activated by
                    <a href="mailto:{{ env('SUPPORT_EMAIL') }}" class="text-blue-500">"support team"</a> before you can use it.</p>
                <p class="text-ld font-semibold text-center">Please contact our support team for activation:</p>
                <div class="contact-info my-7">
                    <p class="text-ld font-semibold">Email: <a href="mailto:{{ env('SUPPORT_EMAIL') }}">{{ env('SUPPORT_EMAIL') }}</a></p>
                    <p class="text-ld font-semibold">Phone: <a href="tel:+{{ env('SUPPORT_PHONE') }}">{{ env('SUPPORT_PHONE') }}</a></p>
                </div>
                <p class="text-ld font-semibold">We appreciate your patience and look forward to serving you.</p>
            </div>
        </div>
    </section>

</div>
@endsection
