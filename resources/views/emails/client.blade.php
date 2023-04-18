<x-mail :subject="$subject">

    <div class="bg-white rounded-md shadow">
        
        Name: {{ $name }}
        <br>
        Email: {{ $email }}
        <br>
        Created at: {{ $created_at }}
        <br>
        <a href="{{ $route }}">See more</a>

    </div>

</x-mail>