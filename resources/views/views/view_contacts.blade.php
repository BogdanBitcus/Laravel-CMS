
<h1>{{ $page->name }}</h1>

<content>{!! $page->content !!}</content>

<h2>Contact us here</h2>
<form action="{{ url()->current() }}" method="post" >
    @csrf

    <br>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div><input name="name" value="{{ old('name') }}" placeholder="Your name"></div>
    <div><input name="email" value="{{ old('email') }}" placeholder="Your Email"></div>
    <div><textarea name="message" placeholder="Your message">{{ old('message') }}</textarea></div>

    @if (session('message'))
        <div class="" style="color:green;font-weight: bold;">{{ session('message') }}</div>
    @endif

    <div><input type="submit" value="Send"></div>
</form>