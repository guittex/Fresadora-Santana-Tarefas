$(function() {
  var thHeight = $("table#demo-table th:first").height();
  $("table#demo-table th").resizable({
      handles: "e",
      minHeight: thHeight,
      maxHeight: thHeight,
      minWidth: 40,
      resize: function (event, ui) {
        var sizerID = "#" + $(event.target).attr("id") + "-sizer";
        $(sizerID).width(ui.size.width);
      }
  });
});