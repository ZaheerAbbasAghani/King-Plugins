<?php 
function tti_booking_form($form){


$form .= '<form id="myForm" action="#" name="myForm" method="POST">
  <h3>PLEASE ENTER FIELDS:</h3>
  <!-- One "tab" for each step in the form: -->
  <div class="tab">
    <p><input type="text" placeholder="NAME" name="fullname" id="fullname" required></p>
    <p><input type="text" placeholder="SURNAME" name="surname" id="surname" required></p>
    <p><input type="text" placeholder="ADDRESS" name="address" id="address" required></p>
    <p><input type="text" placeholder="ZIP CODE" name="zipcode" id="zipcode" required></p>
    <p><input type="text" placeholder="CITY" name="city" id="city" required></p>
    <p><input type="number" placeholder="NUMBER OF PERSON" name="number_of_person" id="number_of_person" required></p>
  </div>
  <div class="tab">Step 2:
    <p>
      <label> 0 - 9 years old: </label> 
      <select>
        <option value="0" selected>0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        </select>
    </p>
    <p><input placeholder="Phone..." name="phone"></p>
  </div>
  <div class="tab">Birthday:
    <p><input placeholder="dd" name="date"></p>
    <p><input placeholder="mm" name="month"></p>
    <p><input placeholder="yyyy" name="year"></p>
  </div>
  <div style="overflow:auto;">
    <div style="float:right; margin-top: 5px;">
      	<button type="button" class="previous button">Previous</button>
      	<button type="button" class="next button">Next</button>
		<button type="submit" class="submit button">Submit</button>
    </div>
  </div>
  <!-- Circles which indicates the steps of the form: -->
  <div style="text-align:center;margin-top:40px;">
    <span class="step">1</span>
    <span class="step">2</span>
    <span class="step">3</span>
  </div>
</form>';

return $form;
}

add_shortcode("booking_form","tti_booking_form");