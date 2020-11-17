<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Sign Up Page</title>
   <link href="css/styles.css" rel="stylesheet" type="text/css" />
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
   <h1> Sign Up</h1> 

   <form id="signupForm" action="welcome.html">
      First Name: <input type="text" name="fName"><br>
      Last Name:  <input type="text" name="lName"><br>
      Gender:     <input type="radio" name="gender" value="m">Male
                  <input type="radio" name="gender" value="f">Female<br><br>
      Zip Code:   <input type="text" id="zip" name="zip">
                  <span id="zipError"></span><br>
                  
      City:       <span id="city"></span><br>
      Latitude:   <span id="latitude"></span><br> 
      Longitude: <span id="longitude"></span><br>

      State:
      <select class="state" id="state" name="state">
         <option> Select One </option>  
         <option value="ca"> California </option>
         <option value="ny"> New York   </option>
         <option value="tx"> Texas      </option>
      </select><br>

      Select a County: <select class="county" id="county"></select><br>

      Desired Username: <input type="text" id="username" name="username"><br>
                        <span id="usernameError"></span><br>
      Password:         <input type="password" id="password" name="password"><br>
      Password Again:   <input type="password" id="passwordAgain">
                        <span id="passwordAgainError"></span> <br /><br>

      <input type="submit" value="Sign up!">
   </form>

   <script>
      
      var usernameAvailable = false;
      var zipCodeAvailable = true;

      //Displaying City from API after typing a zipcode.
      $("#zip").on("change",async function(){
         //alert($("#zip").val());
         let zipCode = $("#zip").val();
         let url = `https://itcdland.csumb.edu/~milara/ajax/cityInfoByZip?zip=${zipCode}`;
         let response = await fetch(url);
         let data = await response.json();
         //console.log(data);
         $("#city").html(data.city);
         $("#latitude").html(data.latitude);
         $("#longitude").html(data.longitude);
      
         if (data == false){
            $("#zipError").html("Zip code not valid!");
            $("#zipError").css("color","red");
            zipCodeAvailable = false;
         }
       
      }); //zip

      $(document).ready(async function() {
         let url = `https://cst336.herokuapp.com/projects/api/state_abbrAPI.php`;
         let response = await fetch(url);
         let data = await response.json();
         
         $("#state").html("<option>Select One </option");
         data.forEach(function(i) {
            $("#state").append(`<option value=${i.usps}> ${i.state} </option>`);
         });
      });
      
      $("#state").on("change", async function(){
         //alert($("#state").val());
         let state = $("#state").val();
         let url = `https://itcdland.csumb.edu/~milara/ajax/countyList.php?state=${state}`;
         let response = await fetch(url);
         let data = await response.json();
         //console.log(data);
         $("#county").html("<option> Select one </option>");
         //Alternative for loop
         data.forEach(function(i) {
            $("#county").append(`<option> ${i.county} </option>`);
         });
      /* for (let i = 0; i < data.length; i++) {
            $("#county").append(`<option> ${data[i].county} </option>`);
         } */
      }); //state

      $("#username").on("change", async function(){
         //alert($("#username").val());
         let username = $("#username").val();
         let url = `https://cst336.herokuapp.com/projects/api/usernamesAPI.php?username=${username}`;
         let response = await fetch(url);
         let data = await response.json();

         if (data.available) {
            $("#usernameError").html("Username available!");            
            $("#usernameError").css("color","violet");           
            usernameAvailable = true;
         } else {
            $("#usernameError").html("Username not available!");   
            $("#usernameError").css("color","red");  
            usernameAvailable = false;
         }
      }); //username 
      
      $("#signupForm").on("submit", function(e){ 
         //alert("Submitting form...");
         if (!isFormValid()) {
            e.preventDefault();
         }
      });

      function isFormValid(){
         isValid = true;
         if (!usernameAvailable) {
            isValid = false;
         }
         
         if (!zipCodeAvailable) {
            isValid = false;
         }

         if ($("#username").val().length == 0) {
            isValid = false;
            $("#usernameError").html("Username is required");
            $("#usernameError").css("color","red");
         }

         if ($("#password").val() != $("#passwordAgain").val()){
            $("#passwordAgainError").html("Password Mismatch!");
            $("#passwordAgainError").css("color","red");
            isValid = false;
         }

         if ($("#password").val().length < 6) {
            $("#passwordAgainError").html("6 characters minimum");
            $("#passwordAgainError").css("color","red");
            isValid = false;
         }
            
         return isValid;
      }

   </script>

  


</body>
</html>
