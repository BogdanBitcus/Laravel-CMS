<h2>Contact form</h2>

<p>Name: {{ $data['name'] }}</p>

<p>Email: {{ $data['email'] }}</p>

<p>Message:</p>

<p>{!! nl2br(e($data['message'])) !!}</p>