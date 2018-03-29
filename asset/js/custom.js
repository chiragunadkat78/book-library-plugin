$(document).ready(function(){
  $('.sendQuery').on('click',function(e){
    e.preventDefault();
    var ajaxURL = $(this).data('url');
    ajaxURL += "?doFilter==true"
    $.ajax({
        type     : "POST",
        url      : ajaxURL,
        data     : $('#BookFilter').serialize(),
        success  : function(data) {
          console.log(data);
          $('#bookTable tbody').html();
          $('#bookTable tbody').html(data);
        }
    });
  });
});
