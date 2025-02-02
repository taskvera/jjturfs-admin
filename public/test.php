<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Routing Number Lookup</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

  <!-- Simple form with input and two buttons -->
  <form id="example-form">
    <label for="rn">Routing Number:</label>
    <input type="text" id="rn" placeholder="Enter routing number" />
    <button type="button" id="lookup">Lookup</button>
    <button type="button" id="tryit">Try Sample</button>
  </form>

  <!-- Where results will be displayed -->
  <div id="result" style="margin-top: 20px; font-family: Arial, sans-serif;">
    <!-- Filled by JavaScript -->
  </div>

  <!-- jQuery from CDN -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      // On "Lookup" button click
      $("#lookup").click(function() {
        doLookup($("#rn").val());
      });

      // Prevent form from reloading the page
      $("#example-form").submit(function() {
        return false;
      });

      // Demo button: fill in a sample RN, then auto-click "Lookup"
      $("#tryit").click(function() {
        $("#rn").val("122242597");
        $("#lookup").click();
      });
    });

    function doLookup(rn) {
      $("#result").empty().text("Looking up " + rn + " ...");

      // JSONP call to the public API
      $.ajax({
        url: "https://www.routingnumbers.info/api/data.json?rn=" + rn,
        dataType: 'jsonp', // JSONP is important for cross-domain requests
        success: onLookupSuccess,
        error: function() {
          $("#result").text("Error fetching data. Maybe try another number.");
        }
      });
    }

    function onLookupSuccess(data) {
      // Build a small table from the returned JSON
      var table = $("<table>")
        .css({
          "border-collapse": "collapse",
          "margin": "10px 0"
        });

      // Add a header
      table.append(
        $("<tr>").append(
          $("<th>")
            .attr("colspan", "2")
            .text("Results")
            .css({
              "background": "#eee",
              "padding": "8px",
              "text-align": "left"
            })
        )
      );

      // Populate rows with key-value pairs
      for (var key in data) {
        var row = $("<tr>");

        // Key cell
        var keyCell = $("<td>")
          .text(key)
          .css({
            "border": "1px solid #ccc",
            "padding": "6px",
            "font-weight": "bold",
            "background": "#f9f9f9"
          });

        // Value cell
        var valueCell = $("<td>")
          .text(data[key])
          .css({
            "border": "1px solid #ccc",
            "padding": "6px"
          });

        row.append(keyCell, valueCell);
        table.append(row);
      }

      // Show in the result div
      $("#result").empty().append(table);
    }
  </script>

</body>
</html>
