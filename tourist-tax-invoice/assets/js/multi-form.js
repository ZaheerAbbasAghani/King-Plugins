(function ( $ ) {
  $.fn.multiStepForm = function(args) {
      if(args === null || typeof args !== 'object' || $.isArray(args))
        throw  " : Called with Invalid argument";
      var form = this;
      var tabs = form.find('.tab');
      var steps = form.find('.step');
      steps.each(function(i, e){
        $(e).on('click', function(ev){
          //alert("HELLO WORLD");
        });
      });
      form.navigateTo = function (i) {/*index*/
        /*Mark the current section with the class 'current'*/
        tabs.removeClass('current').eq(i).addClass('current');
        // Show only the navigation buttons that make sense for the current section:
        form.find('.previous').toggle(i > 0);
        atTheEnd = i >= tabs.length - 1;
        form.find('.next').toggle(!atTheEnd);
        //console.log(i);

        if(i == 1){
          var number_of_person = jQuery("#number_of_person").val();

          var zeroto9yearsMainSeason      = tti_ajax_object.zeroto9yearsMainSeason;
          var tento15yearsMainSeason      = tti_ajax_object.tento15yearsMainSeason;
          var sixteento99yearsMainSeason  = tti_ajax_object.sixteento99yearsMainSeason;

          var zeroto9yearsLowSeason      = tti_ajax_object.zeroto9yearsLowSeason;
          var tento15yearsLowSeason      = tti_ajax_object.tento15yearsLowSeason;
          var sixteento99yearsLowSeason  = tti_ajax_object.sixteento99yearsLowSeason;


          
          jQuery(".pdetails").remove();
          for (var i = 1; i <= number_of_person; i++) {
            jQuery(".wrap_persons").append('<div class="pdetails"><span class="titles"> Person '+i+': </span><div class="box"><div class="bwrap"><h3 style="text-align:right;"> Anzahl der N&auml;chte in der HAUPTSAISON: </h3><label> 0 - 9 Jahre: </label> <select><option value="0" data-entity="person_'+i+'" data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">0</option><option value="1" data-entity="person_'+i+'" data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">1</option><option value="2" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">2</option><option value="3" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">3</option><option value="4" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">4</option><option value="5" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">5</option><option value="6" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">6</option><option value="7" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">7</option><option value="8" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">8</option><option value="9" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">9</option><option value="10" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">10</option><option value="11" data-entity="person_'+i+'" data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">11</option><option value="12" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">12</option><option value="13" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">13</option><option value="14" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">14</option><option value="15" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">15</option><option value="16" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">16</option><option value="17" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">17</option><option value="18" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">18</option><option value="19" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">19</option><option value="20" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">20</option><option value="21" data-entity="person_'+i+'" data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">21</option><option value="22" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">22</option><option value="23" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">23</option><option value="24" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">24</option><option value="25" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">25</option><option value="26" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">26</option><option value="27" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">27</option><option value="28" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">28</option><option value="29" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">29</option><option value="30" data-entity="person_'+i+'"  data-attr="baby" data-season="main" data-price="'+zeroto9yearsMainSeason+'">30</option></select></div><div class="bwrap"><label> 10 - 15 Jahre: </label> <select><option value="0" data-entity="person_'+i+'"  data-attr="child" data-price="'+tento15yearsMainSeason+'" data-season="main">0</option><option value="1" data-entity="person_'+i+'"  data-attr="child" data-price="'+tento15yearsMainSeason+'" data-season="main">1</option><option value="2" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsMainSeason+'" data-season="main" >2</option><option value="3" data-entity="person_'+i+'" data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">3</option><option value="4" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">4</option><option value="5" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">5</option><option value="6" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">6</option><option value="7" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">7</option><option value="8" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">8</option><option value="9" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">9</option><option value="10" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">10</option><option value="11" data-entity="person_'+i+'" data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">11</option><option value="12" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">12</option><option value="13" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">13</option><option value="14" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">14</option><option value="15" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">15</option><option value="16" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">16</option><option value="17" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">17</option><option value="18" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">18</option><option value="19" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">19</option><option value="20" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">20</option><option value="21" data-entity="person_'+i+'" data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">21</option><option value="22" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">22</option><option value="23" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">23</option><option value="24" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">24</option><option value="25" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">25</option><option value="26" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">26</option><option value="27" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">27</option><option value="28" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">28</option><option value="29" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">29</option><option value="30" data-entity="person_'+i+'"  data-attr="child" data-season="main" data-price="'+tento15yearsMainSeason+'">30</option></select> </div><div class="bwrap"><label> 16 - 99 Jahre: </label> <select><option value="0" data-entity="person_'+i+'"  data-attr="adult" data-price="'+sixteento99yearsMainSeason+'" data-season="main">0</option><option value="1" data-entity="person_'+i+'" data-attr="adult" data-price="'+sixteento99yearsMainSeason+'" data-season="main">1</option><option value="2" data-entity="person_'+i+'" data-attr="adult" data-price="'+sixteento99yearsMainSeason+'" data-season="main">2</option><option value="3" data-entity="person_'+i+'" data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">3</option><option value="4" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">4</option><option value="5" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">5</option><option value="6" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">6</option><option value="7" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">7</option><option value="8" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">8</option><option value="9" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">9</option><option value="10" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">10</option><option value="11" data-entity="person_'+i+'" data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">11</option><option value="12" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">12</option><option value="13" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">13</option><option value="14" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">14</option><option value="15" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">15</option><option value="16" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">16</option><option value="17" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">17</option><option value="18" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">18</option><option value="19" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">19</option><option value="20" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">20</option><option value="21" data-entity="person_'+i+'" data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">21</option><option value="22" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">22</option><option value="23" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">23</option><option value="24" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">24</option><option value="25" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">25</option><option value="26" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">26</option><option value="27" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">27</option><option value="28" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">28</option><option value="29" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">29</option><option value="30" data-entity="person_'+i+'"  data-attr="adult" data-season="main" data-price="'+sixteento99yearsMainSeason+'">30</option></select></div></div><div class="box"><div class="bwrap"><h3 style="text-align:right;"> Anzahl der N&auml;chte in der NEBENSAISON: </h3><label> 0 - 9 Jahre: </label> <select><option value="0" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">0</option><option value="1" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">1</option><option value="2" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">2</option><option value="3" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">3</option><option value="4" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">4</option><option value="5" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">5</option><option value="6" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">6</option><option value="7" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">7</option><option value="8" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">8</option><option value="9" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">9</option><option value="10" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">10</option><option value="11" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">11</option><option value="12" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">12</option><option value="13" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">13</option><option value="14" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">14</option><option value="15" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">15</option><option value="16" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">16</option><option value="17" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">17</option><option value="18" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">18</option><option value="19" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">19</option><option value="20" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">20</option><option value="21" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">21</option><option value="22" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">22</option> <option value="23" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">23</option><option value="24" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">24</option><option value="25" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">25</option><option value="26" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">26</option><option value="27" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">27</option><option value="28" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">28</option><option value="29" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">29</option><option value="30" data-entity="person_'+i+'" data-attr="baby" data-price="'+zeroto9yearsLowSeason+'" data-season="low">30</option></select></div><div class="bwrap"><label> 10 - 15 Jahre: </label> <select><option value="0" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">0</option><option value="1" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">1</option><option value="2" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">2</option><option value="3" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">3</option><option value="4" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">4</option><option value="5" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">5</option><option value="6" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">6</option><option value="7" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">7</option><option value="8" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">8</option><option value="9" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">9</option><option value="9" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">9</option><option value="10" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">10</option><option value="11" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">11</option><option value="12" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">12</option><option value="13" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">13</option><option value="14" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">14</option><option value="15" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">15</option><option value="16" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">16</option><option value="17" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">17</option><option value="18" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">18</option><option value="19" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">19</option><option value="20" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">20</option><option value="21" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">21</option><option value="22" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">22</option><option value="23" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">23</option><option value="24" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">24</option><option value="25" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">25</option><option value="26" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">26</option><option value="27" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">27</option><option value="28" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">28</option><option value="29" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">29</option><option value="30" data-entity="person_'+i+'" data-attr="child" data-price="'+tento15yearsLowSeason+'" data-season="low">30</option></select>  </div><div class="bwrap"><label> 16 - 99 Jahre: </label><select><<option value="0" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">0</option><option value="1" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">1</option><option value="2" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">2</option><option value="3" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">3</option><option value="4" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">4</option><option value="5" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">5</option><option value="6" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">6</option><option value="7" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">7</option><option value="8" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">8</option><option value="9" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">9</option><option value="9" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">9</option><option value="10" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">10</option><option value="11" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">11</option><option value="12" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">12</option><option value="13" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">13</option><option value="14" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">14</option><option value="15" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">15</option><option value="16" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">16</option><option value="17" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">17</option><option value="18" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">18</option><option value="19" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">19</option><option value="20" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">20</option><option value="21" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">21</option><option value="22" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">22</option><option value="23" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">23</option><option value="24" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">24</option><option value="25" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">25</option><option value="26" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">26</option><option value="27" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">27</option><option value="28" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">28</option><option value="29" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">29</option><option value="30" data-entity="person_'+i+'" data-attr="child" data-price="'+sixteento99yearsLowSeason+'" data-season="low">30</option></select></div> </div></div>');
          }
        } // check if step 2

        if(i == 2){


          var collection = [];
         // var entity_collection = [];

          jQuery(".pdetails").each(function(){
            jQuery(this).find(".box").each(function(){
              jQuery(this).find(".bwrap").each(function(key,val){

                  var nights = jQuery(this).find("select option:selected").val();
                  var entity = jQuery(this).find("select option:selected").attr("data-entity");
                  var ptype = jQuery(this).find("select option:selected").attr("data-attr");
                  var season = jQuery(this).find("select option:selected").attr("data-season");
                  var price = jQuery(this).find("select option:selected").attr("data-price");

                  if(nights != 0){
                    
                    collection.push({"entity":entity, "nights":nights, "ptype":ptype, "season":season, "price":price});
                  }

              });

            });
          });


          //console.log(collection);

          localStorage.setItem("collection", JSON.stringify(collection));

          var username = jQuery("#fullname").val();
          var address = jQuery("#address").val();
          var dt = new Date();
          
          var day = dt.getDate();
          var month = dt.getMonth() + 1;
          var year = dt.getFullYear();

          var html1 = "<div class='tti_invoice'><ul><li style='float:left;width:45%;height:60px;'> <img src='"+tti_ajax_object.uploaded_logo+"' style='width: 200px;'> </li> <li style='float:right;width:45%;font-size:20px;text-align:right;height:60px;'></li><li style='float:left;width:100%;font-size:20px;text-align:left;'>Rechnung an: "+username+" </li><li style='float:right;width:45%;font-size:20px;text-align:right;'> Datum: "+day+"."+month+"."+year+" </li></ul></div>";

           var html2 = "<table class='form-table'>";
           var html3= "";
           var prices = [];
           jQuery(collection).each(function(key,value){
            prices.push(value.price * value.nights);
              //console.log(value.price * value.nights);
            html3 += "<tr><td> 1 Person x "+value.price+" Euro x "+value.nights+" N&auml;chte = <span class='tti_price'>"+value.price * value.nights+"</span> Euro</td></tr>";
           });
          var html4 = "</table>";

          var total = 0;
          jQuery.each(prices,function(k,v) {
              total += parseFloat(v);
          });

          var html5 = "<h1 style='text-align:right;margin:10px 0px;'> Gesamt: "+total.toFixed(2)+" Euro</h1>";

          var result = html1.concat(" ", html2, " ",html3, " ",html4, " ",html5);
          jQuery(".wrap_live_invoice").html(result);
        }

  
        form.find('.submit').toggle(atTheEnd);
        fixStepIndicator(curIndex());
        return form;
      }
      function curIndex() {
        /*Return the current index by looking at which section has the class 'current'*/
        return tabs.index(tabs.filter('.current'));
      }
      function fixStepIndicator(n) {
        steps.each(function(i, e){
          i == n ? $(e).addClass('active') : $(e).removeClass('active');
        });
      }
      /* Previous button is easy, just go back */
      form.find('.previous').click(function() {
        form.navigateTo(curIndex() - 1);
      });

      /* Next button goes forward iff current block validates */
      form.find('.next').click(function() {
        if('validations' in args && typeof args.validations === 'object' && !$.isArray(args.validations)){
          if(!('noValidate' in args) || (typeof args.noValidate === 'boolean' && !args.noValidate)){
            form.validate(args.validations);
            if(form.valid() == true){
              form.navigateTo(curIndex() + 1);
              return true;
            }
            return false;
          }
        }
        form.navigateTo(curIndex() + 1);
      });
      jQuery('#myForm').on('submit', function(e){
        e.preventDefault();

        jQuery(this).find("input[type='submit']").attr("disabled", true);

        var fullname = jQuery("#fullname").val();
        var surname  = jQuery("#surname").val();
        var address  = jQuery("#address").val();
        var zipcode  = jQuery("#zipcode").val();
        var city   = jQuery("#city").val();
        var number_of_person   = jQuery("#number_of_person").val();

        var data = {
          'action': 'tti_store_tourist_info',
          'fullname': fullname,
          'surname':surname,
          'address':address,
          'zipcode':zipcode,
          'city':city,
          'number_of_person':number_of_person,
          'collection': localStorage.getItem("collection"),

        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(tti_ajax_object.ajax_url, data, function(response) {
            console.log(response);
            Swal.fire({
              title: "Invoice Details <hr>", 
              html: response,  
              showCancelButton: false, allowOutsideClick: true,
              showConfirmButton: false,
              showCloseButton: true,
              width: '950px'  
            }).then(function(){ 
                location.reload();
            });
        });


        return form;
      });
      /*By default navigate to the tab 0, if it is being set using defaultStep property*/
      typeof args.defaultStep === 'number' ? form.navigateTo(args.defaultStep) : null;

      form.noValidate = function() {
        
      }
      return form;
  };
}( jQuery ));