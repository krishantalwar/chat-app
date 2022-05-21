var now = new Date();
var time = now.getTime();
time += 10800 * 1000;
now.setTime(time);

// moment.tz.guess();
var current_timezone =  Intl.DateTimeFormat().resolvedOptions().timeZone;
var current_timezone=current_timezone.replace("Calcutta","Kolkata");
document.cookie = 'user_timezone='+current_timezone + '; expires=' + now.toUTCString()+';path=/';


$("#login").validate({          
    rules: {
      email: {
            required: true,
            minlength: true
        },
        password:{
          required: true,
          minlength: 6
        }

      }
    });         
$("#register").validate({
    rules: {
        email: {
            required: true,
            email: true,  
        },
        password:{
            required: true,
            minlength: 6
        },
        password_confirmation:{
            required: true,
            minlength: 6
        }

    }
});