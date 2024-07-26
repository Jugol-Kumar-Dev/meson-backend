@extends('master')
@section('content')
<section class=" w-full h-80 lg:min-h-screen bg-no-reapet bg-cover bg-center" style="background-image: url('./image/mesonacademy-banner.webp');">
</section>
<section class="py-10 lg:py-32 no-reapet bg-cover" style="background-image: url('./image/course-bg.webp');">
    <div class="container mx-auto  px-3 lg:px-0">
        <h3 class="text-3xl lg:text-6xl text-center font-bold mb-10 uppercase text-slate-400">cluster agri courses</h3>
        <div class="flex flex-wrap justify-center">
            <div class="w-full  lg:w-1/3 lg:px-5 mb-5 lg:mb-0">
                <div class="backdrop-blur-sm bg-gray-400/20 p-3 rounded-2xl border border-gray-300 shadow hover:border-amber-500 transition-all ease-in-out duration-100 ">
                    <div class="w-full h-84">
                        <img class="w-full h-full rounded-xl object-cover" src="/image/course-1.jpeg" alt="">
                    </div>
                    <div class="flex items-center gap-4 my-4 p-1">
                        <span class= "text-sm border border-amber-500 py-1 px-3 rounded-full text-slate-300 bg-amber-500/30 font-light">60+ Live Class</span>
                        <span class= "text-sm border border-amber-500 py-1 px-3 rounded-full text-slate-300 bg-amber-500/30 font-light">80+ Live Exam</span>
                    </div>
                    <div class="p-1">
                        <h3 class="text-slate-400 text-2xl font-semibold mb-3">কৃষি গুচ্ছ পূর্ণাঙ্গ কোর্স</h3>
                        <p class="text-amber-400 mb-3 text-3xl font-bold">৳1899</p>
                        <button class="text-slate-400 py-3 px-10 border border-gray-500 rounded-full hover:border-amber-800 hover:bg-amber-500 hover:text-white text-sm">Enroll Now</button>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-1/3 lg:px-5 mb-5 lg:mb-0">
                <div class="backdrop-blur-sm bg-gray-400/20 p-3 rounded-2xl border border-gray-300 shadow hover:border-amber-500 transition-all ease-in-out duration-100 ">
                    <div class="w-full h-84">
                        <img class="w-full h-full rounded-xl object-cover" src="./image/course-2.jpeg" alt="">
                    </div>
                    <div class="flex items-center gap-4 my-4 p-1">
                        <span class= "text-sm border border-amber-500 py-1 px-3 rounded-full text-slate-300 bg-amber-500/30 font-light">59 Live Exam</span>
                    </div>
                    <div class="p-1">
                        <h3 class="text-slate-400 text-2xl font-semibold mb-3">প্র্রিমিয়াম্‌ এক্সাম ব্যাচ 2024</h3>
                        <p class="text-amber-400 mb-3 text-3xl font-bold">৳550</p>
                        <button class="text-slate-400 py-3 px-10 border border-gray-500 rounded-full hover:border-amber-500 hover:bg-amber-500 hover:text-white text-sm">Enroll Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
