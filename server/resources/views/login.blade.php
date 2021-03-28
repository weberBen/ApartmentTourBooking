

<html>

<head>
  <link href="{{ asset('assets/global/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <link rel="stylesheet" href="{{ asset('assets/global/vendor/intlTelInput/css/intlTelInput.min.css') }}">

  <title>Visite</title>
  
  <style>
  	
/* BASIC */

html {
  background-color: #56baed;
}

body {
  font-family: "Poppins", sans-serif;
  height: 100vh;
}

a {
  color: #92badd;
  display:inline-block;
  text-decoration: none;
  font-weight: 400;
}

h2 {
  text-align: center;
  font-size: 16px;
  font-weight: 600;
  text-transform: uppercase;
  display:inline-block;
  margin: 40px 8px 10px 8px; 
  color: #cccccc;
}



/* STRUCTURE */

.wrapper {
  display: flex;
  align-items: center;
  flex-direction: column; 
  justify-content: center;
  width: 100%;
  min-height: 100%;
  padding: 20px;
}

#formContent {
  -webkit-border-radius: 10px 10px 10px 10px;
  border-radius: 10px 10px 10px 10px;
  background: #fff;
  padding: 30px;
  width: 90%;
  max-width: 450px;
  position: relative;
  padding: 0px;
  -webkit-box-shadow: 0 30px 60px 0 rgba(0,0,0,0.3);
  box-shadow: 0 30px 60px 0 rgba(0,0,0,0.3);
  text-align: center;
}

#formFooter {
  background-color: #f6f6f6;
  border-top: 1px solid #dce8f1;
  padding: 25px;
  text-align: center;
  -webkit-border-radius: 0 0 10px 10px;
  border-radius: 0 0 10px 10px;
}



/* TABS */

h2.inactive {
  color: #cccccc;
}

h2.active {
  color: #0d0d0d;
  border-bottom: 2px solid #5fbae9;
}



/* FORM TYPOGRAPHY*/

input[type=button], input[type=submit], input[type=reset]  {
  background-color: #56baed;
  border: none;
  color: white;
  padding: 15px 80px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  text-transform: uppercase;
  font-size: 13px;
  -webkit-box-shadow: 0 10px 30px 0 rgba(95,186,233,0.4);
  box-shadow: 0 10px 30px 0 rgba(95,186,233,0.4);
  -webkit-border-radius: 5px 5px 5px 5px;
  border-radius: 5px 5px 5px 5px;
  margin: 5px 20px 40px 20px;
  -webkit-transition: all 0.3s ease-in-out;
  -moz-transition: all 0.3s ease-in-out;
  -ms-transition: all 0.3s ease-in-out;
  -o-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out;
}

input[type=button]:hover, input[type=submit]:hover, input[type=reset]:hover  {
  background-color: #39ace7;
}

input[type=button]:active, input[type=submit]:active, input[type=reset]:active  {
  -moz-transform: scale(0.95);
  -webkit-transform: scale(0.95);
  -o-transform: scale(0.95);
  -ms-transform: scale(0.95);
  transform: scale(0.95);
}

input[type=password] {
  background-color: #f6f6f6;
  border: none;
  color: #0d0d0d;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 5px;
  width: 85%;
  border: 2px solid #f6f6f6;
  -webkit-transition: all 0.5s ease-in-out;
  -moz-transition: all 0.5s ease-in-out;
  -ms-transition: all 0.5s ease-in-out;
  -o-transition: all 0.5s ease-in-out;
  transition: all 0.5s ease-in-out;
  -webkit-border-radius: 5px 5px 5px 5px;
  border-radius: 5px 5px 5px 5px;
}

input[type=password]:focus {
  background-color: #fff;
  border-bottom: 2px solid #5fbae9;
}

input[type=password]:placeholder {
  color: #cccccc;
}




input[type=tel] {
  background-color: #f6f6f6;
  border: none;
  color: #0d0d0d;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 5px;
  width: 85%;
  border: 2px solid #f6f6f6;
  -webkit-transition: all 0.5s ease-in-out;
  -moz-transition: all 0.5s ease-in-out;
  -ms-transition: all 0.5s ease-in-out;
  -o-transition: all 0.5s ease-in-out;
  transition: all 0.5s ease-in-out;
  -webkit-border-radius: 5px 5px 5px 5px;
  border-radius: 5px 5px 5px 5px;
}

input[type=tel]:focus {
  background-color: #fff;
  border-bottom: 2px solid #5fbae9;
}

input[type=tel]:placeholder {
  color: #cccccc;
}


/* ANIMATIONS */

/* Simple CSS3 Fade-in-down Animation */
.fadeInDown {
  -webkit-animation-name: fadeInDown;
  animation-name: fadeInDown;
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
}

@-webkit-keyframes fadeInDown {
  0% {
    opacity: 0;
    -webkit-transform: translate3d(0, -100%, 0);
    transform: translate3d(0, -100%, 0);
  }
  100% {
    opacity: 1;
    -webkit-transform: none;
    transform: none;
  }
}

@keyframes fadeInDown {
  0% {
    opacity: 0;
    -webkit-transform: translate3d(0, -100%, 0);
    transform: translate3d(0, -100%, 0);
  }
  100% {
    opacity: 1;
    -webkit-transform: none;
    transform: none;
  }
}

/* Simple CSS3 Fade-in Animation */
@-webkit-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
@-moz-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
@keyframes fadeIn { from { opacity:0; } to { opacity:1; } }


/* Simple CSS3 Fade-in Animation */
.underlineHover:after {
  display: block;
  left: 0;
  bottom: -10px;
  width: 0;
  height: 2px;
  background-color: #56baed;
  content: "";
  transition: width 0.2s;
}

.underlineHover:hover {
  color: #0d0d0d;
}

.underlineHover:hover:after{
  width: 100%;
}



/* OTHERS */

*:focus {
    outline: none;
} 

#icon {
  width:60%;
}

.iti__selected-flag {
    background-color: #e8f3f7 !important;
}

  </style>
</head>

<body>

<div class="wrapper fadeInDown">
  <div id="formContent">
    <!-- Tabs Titles -->

    <!-- Icon -->
    <div class="fadeIn first" style="padding:10;">
      <h4 style="color:grey"> Se connecter </h4>
    </div>

    <!-- Login Form -->
    <form id="login_form">
      <input type="tel" id="user_id" class="fadeIn" placeholder="Téléphone" data-index="1" autofocus>
      <input type="password" id="user_password" class="fadeIn" placeholder="Mot de passe" data-index="2">
      <input type="button" class="fadeIn fourth submit" value="Se connecter" data-index="3">
    </form>

    <!-- Remind Passowrd -->
    <div id="formFooter">
      <a class="underlineHover" href="#"></a>
    </div>

  </div>
</div>
</body>

<script src="{{ asset('assets/global/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/global/js/jquery.cookie.js') }}"></script>
<script src="{{ asset('assets/global/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/global/vendor/intlTelInput/js/intlTelInput.min.js') }}"></script> 
<script src="{{ asset('assets/global/vendor/intlTelInput/js/intlTelInput-jquery.min.js') }}"></script> 

<script>

    var input = document.querySelector("#user_id");
    const iti_tel = window.intlTelInput(input, {
        utilsScript: "{{ asset('assets/global/vendor/intlTelInput/js/utils.js') }}",
        separateDialCode: true,
        autoHideDialCode: true,
        preferredCountries: [ "fr"],
        initialCountry: "fr",
        
    });
    
    function setUserCookie(data)
    {
      const expiration_time_min = 14400;

      var expDate = new Date();
      $.cookie('user', JSON.stringify(data), { expires: expDate.setTime(expDate.getTime() + (expiration_time_min * 60 * 1000)), path: '/' });
    }

    function login()
    {
        const phone = iti_tel.getNumber();
        const password = $("#user_password").val();

        $.post("/api/auth/login", {
            phone: phone,
            password: password,
        })
        .done(function(data) {
            setUserCookie(data);

            document.location.href = "{{ URL::route('home') }}"
        })
        .fail(function(err) {
            console.error(err);

            res = err.responseJSON;
            if("error" in res)
            {
                alert("Identifiant ou mot de passe invalide");
            }else
            {
                alert("Une erreur est survenue");
            }
        });
    }

    $('#login_form').on('keydown', 'input', function (event) {
        
        if (event.which == 13) 
        {
            event.preventDefault();
            var $this = $(event.target);
            var index = parseFloat($this.attr('data-index'));

            var elem = $('[data-index="' + (index + 1).toString() + '"]');
            if(elem.length)
            {
                elem.focus();
                if(elem.hasClass('submit'))
                    login();
            }
        }
    });

    $("#login_form .submit").click(function() {
        login();
    });

    try
    {
        const user = JSON.parse($.cookie('user'));
        const token = user.token;
        if(token!=null)
        {
            document.location.href = "{{ URL::route('home') }}"
        }
    }catch(err)
    {

    }

    $(document).ready(function() {
        $("#user_id").focus();
    });
</script>

</html>


