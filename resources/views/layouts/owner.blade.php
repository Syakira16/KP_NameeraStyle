<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') - Nameera Style</title>


    {{-- Font Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">


    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>



<body class="bg-[#FBF6F3] font-['Poppins',sans-serif] text-sm text-[#3F2A22]">



<div class="flex">



    {{-- ================= SIDEBAR OWNER ================= --}}

    <aside class="min-h-screen w-64 shrink-0 bg-[#D8BCA9] p-5 text-[#3F2A22]">


        {{-- Logo --}}

        <div class="mb-8 flex items-center gap-3 px-2">


            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#6B4A3C] text-xl font-semibold text-white">

                N

            </div>


            <div>

                <p class="text-lg font-semibold leading-tight">
                    Nameera Style
                </p>

                <p class="text-[10px] uppercase tracking-widest text-[#5A3E32]">
                    Busana Muslimah
                </p>

            </div>


        </div>





        {{-- Menu Owner --}}

        <ul class="space-y-1">


            <li>

                <a href="{{ route('owner.dashboard') }}"

                   class="flex items-center gap-3 rounded-xl px-3 py-2.5

                   {{ request()->routeIs('owner.dashboard') 

                   ? 'bg-white font-semibold'

                   : 'font-medium hover:bg-white/50' }}">


                    <svg class="h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.75">

                        <rect x="3" y="3" width="7" height="7" rx="1.5"/>

                        <rect x="14" y="3" width="7" height="7" rx="1.5"/>

                        <rect x="3" y="14" width="7" height="7" rx="1.5"/>

                        <rect x="14" y="14" width="7" height="7" rx="1.5"/>

                    </svg>


                    Dashboard


                </a>


            </li>





            <li>

                <a href="{{ route('owner.orders.index') }}"

class="flex items-center gap-3 rounded-xl px-3 py-2.5

{{ request()->routeIs('owner.orders.*')

? 'bg-white font-semibold'

: 'font-medium hover:bg-white/50' }}">


                    <svg class="h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.75">

                        <path d="M4 6h16M4 12h16M4 18h16"/>

                    </svg>


                    Monitoring Pesanan


                </a>


            </li>





            <li>

               <a href="{{ route('owner.reports.index') }}"

class="flex items-center gap-3 rounded-xl px-3 py-2.5

{{ request()->routeIs('owner.reports.*')

? 'bg-white font-semibold'

: 'font-medium hover:bg-white/50' }}">


                    <svg class="h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.75">


                        <path d="M4 20V10"/>

                        <path d="M10 20V4"/>

                        <path d="M16 20v-7"/>


                    </svg>


                    Laporan


                </a>


            </li>



        </ul>


    </aside>






    {{-- ================= CONTENT ================= --}}


    <main class="min-w-0 flex-1 p-8">



        <div class="mb-8 flex items-center justify-between">



            <div>


                <p class="text-sm text-[#7A6258]">

                    {{ now()->locale('id')->translatedFormat('l, j F Y') }}

                </p>


                <h1 class="text-3xl font-semibold text-[#4A3228]">

                    @yield('title')

                </h1>


            </div>







            {{-- Profil Owner --}}


            <details class="relative">


                <summary class="flex cursor-pointer list-none items-center gap-3 rounded-full border border-[#EBDDD5] bg-white py-1.5 pl-1.5 pr-4 hover:bg-[#FDF8F5]">


                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#6B4A3C] text-xs font-semibold text-white">

                        O

                    </span>


                    <span class="font-medium">

                        Owner

                    </span>



                    <svg class="h-4 w-4 text-[#7A6258]"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">

                        <path d="m6 9 6 6 6-6"/>

                    </svg>


                </summary>





                <div class="absolute right-0 z-10 mt-2 w-48 rounded-2xl border border-[#F0E3DC] bg-white p-2 shadow-lg">


                    <form action="{{ Route::has('logout') ? route('logout') : '#' }}"
                          method="POST">


                        @csrf


                        <button type="submit"

                        class="flex w-full items-center gap-2 rounded-xl px-3 py-2.5 text-left font-medium text-red-700 hover:bg-red-50">


                            Logout


                        </button>


                    </form>


                </div>



            </details>



        </div>





        @yield('content')




    </main>




</div>



</body>

</html>