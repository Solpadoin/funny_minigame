$(function() {
  function displayItem(event, ui) {
    $('#values').text(ui.item.label)
  }

  $( document ).ready(function() {
    $("canvas").appendTo("#game");
  });
});