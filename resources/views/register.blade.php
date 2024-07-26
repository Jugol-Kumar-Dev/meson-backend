<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
    <title>Meson - মেসন | Register</title>

    <meta name="title" content="Meson is an uprising E-learning platform focusing on HSC & Admission preparation." />
    <meta name="description" content="Meson is an uprising E-learning platform focusing on HSC & Admission preparation. তোমদের HSC এবং এডমিশন প্রস্তুতিকে সহজতর করতে আমরা আছি ''মেসন'' টিম।" />
    <meta property="og:image" content="https://mesonacademy.com/image/logo2.png" />
    <meta name="og:title" content="Meson is an uprising E-learning platform focusing on HSC & Admission preparation." />
    <meta name="og:description" content="Meson is an uprising E-learning platform focusing on HSC & Admission preparation. তোমদের HSC এবং এডমিশন প্রস্তুতিকে সহজতর করতে আমরা আছি ''মেসন'' টিম।" />




    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @layer  {
            .overlay{
                left: 37%;
                right: 50%;
                filter: blur(100px);
                bottom: 8rem;
                z-index: 1;
                background: #0000ff30;
            }
        }
    </style>

</head>
<body>

<div class="container mx-auto px-4 mt-20">
    <div class="flex justify-center">
        <div class="w-full max-w-xl">
            <div class="bg-white shadow-lg rounded-lg">
                <div class="p-6">
                    <a href="/" class="block mx-auto text-center">
                        <img src="./image/logo2.png" width="250" class="mx-auto"/>
                    </a>
                    <h4 class="text-center text-xl font-semibold mb-4">Register New Account</h4>

                    @if ($errors->any())
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li class="text-red-500">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <form class="space-y-4" action="{{ route('createStudnet') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <input type="text" name="name" value="{{ old('name') }}" class="mt-1 border-2 rounded-md border-slate-400 py-3 px-2 block w-full" placeholder="e.g Enter Full Name">
                        </div>
                        <div>
                            <input name="phone" type="text" id="phone" value="{{ old('phone') }}" class="form-input mt-1 border-2 rounded-md border-slate-400 py-3 px-2 block w-full" placeholder="e.g Enter Phone Number">
                        </div>
                        <div>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-input mt-1 border-2 rounded-md border-slate-400 py-3 px-2 block w-full" placeholder="e.g Enter Email">
                        </div>
                        <div>
                            <input type="password" name="password" class="form-input mt-1 border-2 rounded-md border-slate-400 py-3 px-2 block w-full" placeholder="********">
                        </div>
                        <div>
                            <input type="hidden" name="is_show" value="true">
                            <select name="course_id" class="form-input mt-1 border-2 rounded-md border-slate-400 py-3 px-2 block w-full">
                                <option selected disabled class="text-slate-300">~~ Select Course ~~</option>
                                @foreach($courses as $c)
                                    <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="w-full mt-4 bg-[#4d28ac] text-white py-3 px-4 rounded" type="submit">
                            Sign up
                        </button>
                    </form>
                    <p class="text-center mt-4">
                        <span>Already registered?</span>
                        <a href="/login" class="text-blue-500">
                            <span> Login here</span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>



</body>
</html>
