<!-- app/Views/DirectDepositView.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Link Direct Deposit Account</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
</head>
<body>
  <h1>Link Direct Deposit Account</h1>
  <form id="direct-deposit-form">
    <label for="rn">Routing Number:</label>
    <input type="text" id="rn" name="rn" placeholder="Enter 9-digit routing number">
    <button type="button" id="lookup">Lookup</button>
  </form>

  <div id="bank-info" style="margin-top:20px;">
    <!-- Bank information will be populated here -->
  </div>

  <script>
    $(document).ready(function(){
      $("#lookup").click(function(){
        var rn = $("#rn").val();
        if (!/^\d{9}$/.test(rn)) {
          alert("Please enter a valid 9-digit routing number.");
          return;
        }
        $("#bank-info").html("Looking up routing number " + rn + "...");
        $.ajax({
          url: "/api/lookup-bank-info",
          method: "GET",
          data: { rn: rn },
          dataType: "json",
          success: function(data){
            if (data.error) {
              $("#bank-info").html("<strong>Error:</strong> " + data.error);
            } else {
              var html = "<table border='1' cellpadding='4'>";
              $.each(data, function(key, value){
                html += "<tr><td><strong>" + key + "</strong></td><td>" + value + "</td></tr>";
              });
              html += "</table>";
              $("#bank-info").html(html);
            }
          },
          error: function(){
            $("#bank-info").html("An error occurred while fetching bank information.");
          }
        });
      });
    });
  </script>
</body>
</html>
