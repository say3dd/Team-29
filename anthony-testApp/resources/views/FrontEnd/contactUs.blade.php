@extends('FrontEnd.master')

@section('title', 'ContactUs')

@section('content')
<<<<<<< HEAD

<!-- resources/views/contact.blade.php -->


@section('content')
=======
>>>>>>> be49d0404d09d29711104e6fcc507ec0637b5749
<h1 class="text-white">ContactUs</h1>
@if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('contact.submit') }}" method="post">
        @csrf
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="message">Message:</label>
        <textarea name="message" id="message" rows="4" required></textarea>

        <button type="submit">Submit</button>
    </form>

@endsection