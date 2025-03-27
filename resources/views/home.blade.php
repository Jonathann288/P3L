@extends('layouts.app')
@section('content')
<div id="content" style="display: none;">
    <script src="https://cdn.tailwindcss.com"></script>
    <nav class="bg-blue-600 p-4">
        <div class="container mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/logo6.png') }}" alt="ReUseMart" class="h-12">
            </div>
            <div class="flex-grow mx-4">
                <input type="text" placeholder="Mau cari apa nih kamu?" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
            </div>
            <button
                style="background-color: #0056b3; border: none; color: white; padding: 8px 15px; border-radius: 10px; margin-right: 10px; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 4px 8px rgba(0,0,0,0.2);"
                onmouseover="this.style.backgroundColor='#00336e'; this.style.transform='translateY(2px)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.1)';"
                onmouseout="this.style.backgroundColor='#0056b3'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)';"
                onmousedown="this.style.backgroundColor='#001f4d'; this.style.transform='translateY(4px)'; this.style.boxShadow='0 0px 0px rgba(0,0,0,0)';"
                onmouseup="this.style.backgroundColor='#00336e'; this.style.transform='translateY(2px)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.1)';"
                onclick="window.location.href='{{ route('login') }}'">
                Log In
            </button>
            <button
                style="background-color: white; color: black; padding: 8px 15px; border-radius: 10px; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 4px 8px rgba(0,0,0,0.2);"
                onmouseover="this.style.backgroundColor='#e0e0e0'; this.style.transform='translateY(2px)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.1)';"
                onmouseout="this.style.backgroundColor='white'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)';"
                onmousedown="this.style.backgroundColor='#c0c0c0'; this.style.transform='translateY(4px)'; this.style.boxShadow='0 0px 0px rgba(0,0,0,0)';"
                onmouseup="this.style.backgroundColor='#e0e0e0'; this.style.transform='translateY(2px)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.1)';"
                onclick="window.location.href='{{ route('register') }}'">
                Sign In
            </button>
        </div>
    </nav>
</div>
@endsection
