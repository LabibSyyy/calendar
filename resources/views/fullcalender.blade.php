<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel FullCalendar</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        height: 100vh;
        position: relative;
        height: 100%;
    }

    /* Fullscreen background video */
    .bg-video {
        position: fixed;
        /* Keeps the video in place relative to the viewport */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Ensures the video covers the entire area */
        z-index: -1;
    }

    .container {
        position: relative;
        z-index: 1;
        /* Ensure content is above the video */
        margin-top: 50px;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #ef233c;
        font-weight: bold;
    }

    #calendar {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
        max-width: 80%;
        margin: 0 auto;
    }

    .fc-toolbar {
        font-size: 0.9em;
        padding-bottom: 20px;
    }

    .fc-view-container {
        font-size: 0.9em;
    }

    .fc-day-grid-day {
        height: 70px;
    }

    .fc-event {
        font-family: 'Poppins', sans-serif;
        font-size: 1em;
        border: none;
        padding: 4px 5px;
        border-radius: 5px;
        margin: 2px;
        transition: transform 0.2s ease-in-out;
    }

    .fc-event:hover {
        transform: scale(1.05);
    }

    .fc-event-title {
        font-weight: 600;
    }

    #eventDescription {
        min-height: 100px;
    }

    .modal-header {
        background-color: #007bff;
        color: white;
    }

    .modal-footer button {
        font-family: 'Poppins', sans-serif;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .bagenda {
        padding-right: 112px;
    }

    .fc-today {
        background-color: #fb8500;
    }
    </style>
</head>

<body>

    <!-- Background Video -->
    <video class="bg-video" autoplay muted loop>
        <source src="/live.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="container">
        <h1>Laravel FullCalendar</h1>
        <div class="d-flex justify-content-end mb-3 bagenda">
            <a href="{{ url('/agenda') }}" class="btn btn-primary">Go to Agenda</a>
        </div>
        <div id='calendar'></div>
    </div>

    <!-- Modal HTML for Adding Event -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventModalLabel">Add New Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addEventForm">
                        <div class="mb-3">
                            <label for="addEventTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="addEventTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="addEventStart" class="form-label">Start Date</label>
                            <input type="text" class="form-control" id="addEventStart" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="addEventEnd" class="form-label">End Date</label>
                            <input type="text" class="form-control" id="addEventEnd" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="addEventDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="addEventDescription" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="addEventRoom" class="form-label">Room</label>
                            <select class="form-control" id="addEventRoom" required>
                                <option value="" disabled selected>Select Room</option>
                                @for ($i = 1; $i <= 10; $i++) <option value="Room {{ $i }}">Room {{ $i }}</option>
                                    @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="addEventDresscode" class="form-label">Dress Code</label>
                            <input type="text" class="form-control" id="addEventDresscode">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveNewEvent">Save Event</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal HTML for Editing Event -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Edit Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="eventForm">
                        <div class="mb-3">
                            <label for="eventTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="eventTitle">
                        </div>
                        <div class="mb-3">
                            <label for="eventStart" class="form-label">Start Date</label>
                            <input type="text" class="form-control" id="eventStart" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="eventEnd" class="form-label">End Date</label>
                            <input type="text" class="form-control" id="eventEnd" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="eventDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="eventDescription" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="eventRoom" class="form-label">Room</label>
                            <select class="form-control" id="eventRoom" required>
                                <option value="" disabled selected>Select Room</option>
                                @for ($i = 1; $i <= 10; $i++) <option value="Room {{ $i }}">Room {{ $i }}</option>
                                    @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="eventDresscode" class="form-label">Dress Code</label>
                            <input type="text" class="form-control" id="eventDresscode">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveChanges">Save changes</button>
                    <button type="button" class="btn btn-danger" id="deleteEvent">Delete Event</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <!-- Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <!-- FullCalendar JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
    <!-- Toastr JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
    $(document).ready(function() {
        var SITEURL = "{{ url('/') }}";
        var selectedEvent = null;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var calendar = $('#calendar').fullCalendar({
            editable: true,
            events: SITEURL + "/fullcalender",
            displayEventTime: false,
            editable: true,
            eventRender: function(event, element) {
                element.css('background-color', event.color);
                element.find('.fc-title').css('font-family', 'Poppins, sans-serif');
            },
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {
                if (start >= moment().startOf(
                        'day')) { // Membatasi seleksi hanya dari hari ini dan seterusnya
                    $('#addEventStart').val($.fullCalendar.formatDate(start, "Y-MM-DD"));
                    $('#addEventEnd').val($.fullCalendar.formatDate(end, "Y-MM-DD"));
                    $('#addEventModal').modal('show');
                } else {
                    alert("You cannot select past dates.");
                }
            },
            eventClick: function(event) {
                selectedEvent = event;

                // Fill modal with event details
                $('#eventTitle').val(event.title);
                $('#eventStart').val($.fullCalendar.formatDate(event.start, "Y-MM-DD"));
                $('#eventEnd').val($.fullCalendar.formatDate(event.end, "Y-MM-DD"));
                $('#eventDescription').val(event.description);
                $('#eventRoom').val(event.room); // Tambahkan room
                $('#eventDresscode').val(event.dresscode); // Tambahkan dresscode

                // Show the modal
                $('#eventModal').modal('show');
            },
            eventDrop: function(event, delta, revertFunc) {
                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");

                if (moment(start).isBefore(moment().startOf('day'))) {
                    alert("You cannot move events to past dates.");
                    revertFunc();
                    return;
                }

                $.ajax({
                    url: SITEURL + '/fullcalenderAjax',
                    data: {
                        id: event.id,
                        title: event.title,
                        start: start,
                        end: end,
                        description: event.description,
                        room: event.room, // Tambahkan room
                        dresscode: event.dresscode, // Tambahkan dresscode
                        color: event.color,
                        type: 'update'
                    },
                    type: "POST",
                    success: function(response) {
                        displayMessage("Event Updated Successfully");
                    },
                    error: function(xhr) {
                        if (xhr.status === 400) {
                            displayMessage(xhr.responseJSON.error);
                        } else {
                            displayMessage("An error occurred.");
                        }
                        revertFunc();
                    }
                });
            }
        });

        // Save new event button click
        $('#saveNewEvent').click(function() {
            var title = $('#addEventTitle').val();
            var start = $('#addEventStart').val();
            var end = $('#addEventEnd').val();
            var description = $('#addEventDescription').val();
            var room = $('#addEventRoom').val(); // Tambahkan room
            var dresscode = $('#addEventDresscode').val(); // Tambahkan dresscode

            if (title) {
                $.ajax({
                    url: SITEURL + "/fullcalenderAjax",
                    data: {
                        title: title,
                        start: start,
                        end: end,
                        description: description,
                        room: room, // Tambahkan room
                        dresscode: dresscode, // Tambahkan dresscode
                        color: 'lightgreen',
                        type: 'add'
                    },
                    type: "POST",
                    success: function(data) {
                        displayMessage("Event Created Successfully");
                        location.reload();
                    },
                    error: function(xhr) {
                        if (xhr.status === 400) {
                            displayMessage(xhr.responseJSON.error);
                        } else {
                            displayMessage("An error occurred.");
                        }
                    }
                });
            } else {
                displayMessage("Event title cannot be empty.");
            }
        });

        // Save changes button click
        $('#saveChanges').click(function() {
            if (selectedEvent) {
                var newTitle = $('#eventTitle').val();
                var newDescription = $('#eventDescription').val();
                var newRoom = $('#eventRoom').val(); // Tambahkan room
                var newDresscode = $('#eventDresscode').val(); // Tambahkan dresscode

                if (newTitle) {
                    var updatedEvent = {
                        id: selectedEvent.id,
                        title: newTitle,
                        start: $.fullCalendar.formatDate(selectedEvent.start, "Y-MM-DD"),
                        end: $.fullCalendar.formatDate(selectedEvent.end, "Y-MM-DD"),
                        description: newDescription,
                        room: newRoom, // Tambahkan room
                        dresscode: newDresscode, // Tambahkan dresscode
                        color: selectedEvent.color
                    };

                    // Remove the old event
                    calendar.fullCalendar('removeEvents', selectedEvent.id);

                    // Add the updated event
                    $.ajax({
                        type: "POST",
                        url: SITEURL + '/fullcalenderAjax',
                        data: {
                            id: updatedEvent.id,
                            title: updatedEvent.title,
                            start: updatedEvent.start,
                            end: updatedEvent.end,
                            description: updatedEvent.description,
                            room: updatedEvent.room, // Tambahkan room
                            dresscode: updatedEvent.dresscode, // Tambahkan dresscode
                            color: updatedEvent.color,
                            type: 'update'
                        },
                        success: function() {
                            displayMessage("Event Updated Successfully");
                            calendar.fullCalendar('renderEvent', updatedEvent, true);
                            $('#eventModal').modal('hide');
                        }
                    });
                } else {
                    displayMessage("Event title cannot be empty.");
                }
            }
        });

        // Delete event button click
        $('#deleteEvent').click(function() {
            if (selectedEvent) {
                var deleteMsg = confirm("Do you really want to delete this event?");
                if (deleteMsg) {
                    $.ajax({
                        type: "POST",
                        url: SITEURL + '/fullcalenderAjax',
                        data: {
                            id: selectedEvent.id,
                            type: 'delete'
                        },
                        success: function() {
                            displayMessage("Event Deleted Successfully");
                            calendar.fullCalendar('removeEvents', selectedEvent.id);
                            $('#eventModal').modal('hide');
                        }
                    });
                }
            }
        });
    });

    function displayMessage(message) {
        toastr.success(message, 'Event');
    }
    </script>

</body>

</html>