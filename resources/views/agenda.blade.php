<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: #ef233c;
            font-weight: bold;
        }
        
        .bg-video {
            position: fixed; /* Keeps the video in place relative to the viewport */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensures the video covers the entire area */
            z-index: -1;
        }

        table {
            background-color: #ffffff; /* Set table background color to white */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5); /* Tambahkan shadow pada tabel */
            
        }

        th, td {
            text-align: center; /* Center align text for better readability */
        }

        /* Header styling */
        th {
            background-color: #c1121f; 
            color: #ffffff;
        }

    </style>
</head>
<body>

<video class="bg-video" autoplay muted loop>
    <source src="/live.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>

<div class="container mt-5">
    <h1 class="text-center">Agenda</h1>
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ url('/fullcalender') }}" class="btn btn-secondary">Back to Calendar</a>
        <form class="form-inline" action="{{ url('/search-events') }}" method="GET">
            <input class="form-control mr-2" type="search" name="query" placeholder="Search events" aria-label="Search">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Description</th>
                <th>Room</th>
                <th>Dress Code</th> <!-- Tambahkan kolom dresscode -->
            </tr>
        </thead>
        <tbody >
            @foreach ($events as $event)
                <tr>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->start }}</td>
                    <td>{{ $event->end }}</td>
                    <td>{{ $event->description }}</td>
                    <td>{{ $event->room }}</td>
                    <td>{{ $event->dresscode }}</td> <!-- Tampilkan dresscode -->
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
