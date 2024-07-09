$(document).on('click', '.section', function(e) {
    e.preventDefault();

    var id = $('.section').val();

    $.ajax({
      type: "GET",
      url: "/instructorClassAttendance/"+id,
      dataType: "json",
      success: function(response) {
        if (response.status == 200) {
    
          
        
      }}
    });
});