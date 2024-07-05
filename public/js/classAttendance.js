$(document).on('click', '.section', function(e) {
    e.preventDefault();

    var id = $('.section').val();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type: "GET",
      url: "/instructorClassAttendance/" + id,
      dataType: "json",
    });
});