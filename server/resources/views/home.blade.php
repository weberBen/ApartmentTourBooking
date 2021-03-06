<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Visite appartement</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset('assets/global/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('assets/global/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/global/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/global/vendor/animate.css/animate.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/global/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/global/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/global/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/global/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/global/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/global/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('assets/global/css/style.css') }}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Selecao - v4.0.1
  * Template URL: https://bootstrapmade.com/selecao-bootstrap-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

  <link href="{{ asset('assets/global/vendor/fullcalendar/main.css') }}" rel='stylesheet' />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.7/css/rowReorder.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css ">

  <style>
    @keyframes spinner-border {
      to { transform: rotate(360deg); }
    } 
    .spinner-border{
        display: inline-block;
        width: 2rem;
        height: 2rem;
        vertical-align: text-bottom;
        border: .25em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        -webkit-animation: spinner-border .75s linear infinite;
        animation: spinner-border .75s linear infinite;
    }
    .spinner-border-sm{
        height: 1rem;
        border-width: .2em;
    }

    .text-wrap{
      white-space:normal;
    }
    .width-200{
        width:200px;
    }

    .events-table tr.odd-row { background-color: white;}
    .events-table tr.even-row { background-color: #e5eafc;}

    @media (max-width: 767.98px) {
      .fc .fc-toolbar.fc-header-toolbar {
          display: block;
          text-align: center;
      }

      .fc-header-toolbar .fc-toolbar-chunk {
          display: block;
      }
  }

  </style>
</head>

<body>

  <div id="desktopTest" class="hidden-xs"></div>
  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top d-flex align-items-center  header-transparent" style="background-color:#2A2C39;">
    <div class="container d-flex align-items-center justify-content-between">

      <div class="logo ">
        <h1><a href="index.html">Visite Appartement</a></h1>
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="index.html"><img src="{{ asset('assets/global/img/logo.png') }}" alt="" class="img-fluid"></a>-->
      </div>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="#calendar">Agenda des visites</a></li>
          <li><a class="nav-link scrollto" href="#actions">Mes visites</a></li>
          <li><a class="nav-link scrollto" href="#summary">R??capitulatif</a></li>
          <li><a class="nav-link scrollto" href="#search">Recherche visite/utilisateur</a></li>
          <li><a class="nav-link scrollto" href="#account">Compte</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <main id="main">

   <!-- ======= Home Section ======= -->
   <section id="calendar" class="calendar">
      <div class="container">
          <div class="section-title" data-aos="zoom-out">
            <h2></h2>
            <p>AGENDA DES VISITES</p>
            <h5>Fuseau horaire : <b id="timezone" style="color:#f0b377"> </b> </h5>
          </div>

          <div id="calendar_container_header"></div>
          <div id='calendar_container'></div>

      </div>
    </section><!-- End Home Section -->

    <section id="actions" class="actions">
      <div class="container">
          <div class="section-title" data-aos="zoom-out">
            <h2></h2>
            <p>MES VISITES</p>
          </div>

          <div id='actions_container'></div>

      </div>
    </section><!-- End Home Section -->

    

    <section id="summary" class="summary">
      <div class="container">
          <div class="section-title" data-aos="zoom-out">
            <h2></h2>
            <p>R??CAPITULATIF</p>
          </div>

          <div id="summary_container_header">
            <h6 style="color:grey;">D??but du la plage</h6>
            <input type="text" size="16" class="form-control" id="summary_start_datetimepicker">
            <br/>
            <h6 style="color:grey;">Fin de la plage</h6>
            <input type="text" size="16" class="form-control" id="summary_end_datetimepicker">
            <br/>
            <button class="btn btn-secondary" id="summary_validate_datetimes">Rafra??chir</button>
            <br/>
            <br/>
          </div>

          <div id='summary_container'></div>

      </div>
    </section><!-- End Home Section -->



    <section id="search" class="search">
      <div class="container">
          <div class="section-title" data-aos="zoom-out">
            <h2></h2>
            <p>RECHERCHE VISITE/UTILISATEUR</p>
          </div>
          
          <div id="search_container_header">
            <form class="form-inline my-2 my-lg-0">
              <input class="form-control mr-sm-2" type="search" placeholder="Recherche" aria-label="Recherche" id="input_search">
              <button class="btn btn-outline-success my-2 my-sm-0" type="button" id="btn_search">Chercher</button>
            </form>
            <small class="form-text text-muted">Saisissez un num??ro de t??l??phone (au format international +33...) ou une r??f??rence visite</small>

            <br/>
            <br/>

            <div class="spinner-border text-primary spinner d-none" role="status">
              <span class="sr-only">Chargement...</span>
            </div>
          </div>

          <div id='search_container'></div>

      </div>
    </section><!-- End Home Section -->
    

    <section id="account" class="account">
      <div class="container">
          <div class="section-title" data-aos="zoom-out">
            <h2></h2>
            <p>COMPTE</p>
          </div>

          <button class="btn btn-warning" id="btn_disconnect">Se d??connecter</button>
          
          <div style="padding:10px;"></div>

          <p style="color:grey;">S??lection de la langue</p>
          <select class="form-select" aria-label="" data-live-search="true" id="select_language">
          </select>

          <div id='account_container'></div>

      </div>
    </section><!-- End Home Section -->

  </main><!-- End #main -->

  <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
      <center>
        <div class="spinner-border text-primary modal-spinner d-none" role="status">
          <span class="sr-only">Chargement...</span>
        </div>
      </center>
      <div class="alert alert-danger modal-alert-msg d-none" role="alert"></div>
      <div class="alert alert-success modal-validation-msg d-none" role="alert"></div>

      <div class="modal-footer">

        <button type="button" class="btn btn-secondary modal-btn-cancel" data-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary modal-btn-validation">Valider</button>
      </div>
    </div>
  </div>
</div>


<div class="modal" id="modal_loading" tabindex="-1" role="dialog" aria-labelledby="modal_loading" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Chargement en cours</h5>
        </button>
      </div>
      <div class="modal-body">
        <center>
        <p>Veuillez patentier...</p>
        <br/>
        <div class="spinner-border text-primary modal-spinner" role="status">
          <span class="sr-only">Chargement...</span>
        </div>
      </center>
      </div>
      <div class="alert alert-danger modal-alert-msg d-none" role="alert"></div>
      <div class="alert alert-success modal-validation-msg d-none" role="alert"></div>

      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container">
      </div>
      <div class="copyright">
      </div>
      <div class="credits">
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
</body>


<!-- Vendor JS Files -->
<script src="{{ asset('assets/global/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/global/vendor/aos/aos.js') }}"></script>
<script src="{{ asset('assets/global/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/global/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ asset('assets/global/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/global/vendor/php-email-form/validate.js') }}"></script>
<script src="{{ asset('assets/global/vendor/swiper/swiper-bundle.min.js') }}"></script>
<scrip type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>

<!-- Template Main JS File -->
<script src="{{ asset('assets/global/js/main.js') }}"></script>
<script src="{{ asset('assets/global/vendor/fullcalendar/main.min.js') }}"></script>
<script src="{{ asset('assets/global/vendor/fullcalendar/locales/fr.js') }}"></script>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/l10n/fr.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/store.js/2.0.0/store.everything.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script>

    function removeUserData()
    {
      if(typeof store !== 'undefined')
      {
        store.remove('user');
      }
    }

  function goToPage(page_name)
  {
    if(page_name=="login")
    {
      removeUserData();
      document.location.href = "{{ URL::route('login') }}";
    }
  }



  const RANK_COLORS = ["#f54545", "#f54545", "#f54545", "#e9ead4", "#8df545", "#8df545", "#8df545"];

  function uuidv4() 
  {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
  }

  $(".modal-btn-validation").click(function() {
    const modal = $('#modal');
    const modal_data = modal.data('user');
    let close = false;

    const reason = $("#reason_state").val();

    modal.find("modal-btn-validation").prop('disabled', true);
    modal.find("modal-btn-cancel").prop('disabled', true);
    modal.find(".modal-spinner").removeClass("d-none");
    modal.find(".modal-alert-msg").addClass("d-none");

    modal.find(".modal-body").html("Traitement en cours");
    
    if(modal_data.action=="book")
    {
      window.ajax({
        url: "/api/action/book",
        type: "POST",
        data: {
          id_calendar_event: modal_data.id_event,
        },
        success: function(data) {
          if("error" in data)
          {
          console.error(data);
          
          if("error_type" in data)
          {
            if(data.error_type=="already_active_book")
            {
              modal.find(".modal-alert-msg").html("Vous avez d??j?? fait une r??servation ou une r??servation est en cours de validation");

            }else if(data.error_type=="already_allocated")
            {
              modal.find(".modal-alert-msg").html("Il semblerait que le cr??neau ait ??t?? r??serv?? par une autre personne pendant votre choix.<br/>Veuillez rafra??chir la page et recommencer");
            }else if(data.error_type=="already_spent_datetime")
            {
              modal.find(".modal-alert-msg").html("Vous ne pouvez r??server un cr??neau d??butant avant la date et l'heure actuelle");
            }
          }else
          {
            modal.find(".modal-alert-msg").html("Une erreur sur le serveur est survenue");
          }

          modal.find(".modal-alert-msg").removeClass("d-none");
          }else
          {
            modal.find(".modal-validation-msg ").html("Votre r??servation est prise en compte mais n'est pas encore valid??.<br/>Vous devez attendre la confirmation qui vous sera envoy??e apr??s une validation manuelle.<br/><br/>Vous pourrez annuler cette visite en cliquant sur cette derni??re dans la section 'Mes visites' pour faire appara??tre la colonne 'Actions'.");
            modal.find(".modal-validation-msg ").removeClass("d-none");
          }

        },
        error: function(err) {
          console.error(err);

          modal.find(".modal-alert-msg").html("Une erreur sur le serveur est survenue");
          modal.find(".modal-alert-msg").removeClass("d-none");
        }
      });
    
    }else if(modal_data.action=="unbook")
    {

      window.ajax({
        url: "/api/action/unbook",
        type: "POST",
        data: {
          id_calendar_event: modal_data.id_event,
        reason: reason,
        },
        success: function(data) {
          if("error" in data)
          {
          console.error(data);

          modal.find(".modal-alert-msg").html("Vous ne pouvez pas annuler cette visite");
          modal.find(".modal-alert-msg").removeClass("d-none");

          }else
          {

            if(!data.async_process)
            {
              modal.find(".modal-validation-msg ").html("Votre annulation est effective. Aucune confirmation n'est necessaire.");
              modal.find(".modal-validation-msg ").removeClass("d-none");
            }else
            {
              modal.find(".modal-validation-msg ").html("Votre annulation a bien ??t?? prise en compte mais elle n'est pas encore valid??e.<br/>Vous devez attendre la confirmation qui vous sera envoy??e apr??s une validation manuelle");
              modal.find(".modal-validation-msg ").removeClass("d-none");
            }
          }
        },
        error: function(err) {
          console.error(err);

          modal.find(".modal-alert-msg").html("Une erreur sur le serveur est survenue");
          modal.find(".modal-alert-msg").removeClass("d-none");
        }
      });

    }else
    {
      close = true;
    }


    modal.find("modal-btn-validation").prop('disabled', false);
    modal.find("modal-btn-cancel").prop('disabled', false);
    modal.find(".modal-spinner").addClass("d-none");

    modal.data("user", {});
    $("#reason_state").val("");

    if(close)
      modal.modal('hide');

  });

  function dateToUtcFormat(date)
  {
    const year = date.getFullYear();
    const str_year = "" + year;

    const month = date.getMonth() + 1;
    const str_month = (month<10)?("0"+month):(""+month);

    const day = date.getDate();
    const str_day = (day<10)?("0"+day):(""+day);

    const hours = date.getHours();
    const str_hours = (hours<10)?("0"+hours):(""+hours);

    const minutes = date.getMinutes();
    const str_minutes = (minutes<10)?("0"+minutes):(""+minutes);

    return str_year + "/" + str_month + "/" + str_day  + " " + str_hours + ":" + str_minutes;
  }

  function initCalendar(can_see)
  {
    const container = $("#calendar_container");

    if(!can_see)
    {
      container.append('<div class="alert alert-info" role="alert">Vous ne pouvez plus r??server une visite car une est en cours de confirmation ou en attente d\'??tre faite.<br/>Pour suivre vos visites aller ?? la section "Mes actions"</div>');
      return ;
    }else
    {
      if(!window.user.is_manager_group)
      {
        $("#calendar_container_header").append(`
          <div class="alert alert-info" role="alert">
            Ici vous pouvez r??server une visite en cliquant sur le cr??neau d??sir??. 
            <b><br/>
            ATTENTION : la r??servation ne sera effective qu'au moment de la confirmation par sms.
            <br/>Avant cela, le cr??neau ne vous est pas encore r??serv??. Ne pr??voyez donc rien avant d'avoir recu confirmation.
          </div>
        `);
      }
    }

    var calendarEl = document.getElementById('calendar_container');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'listWeek',
      slotDuration: '00:05',
      headerToolbar: { center: 'timeGridWeek,listWeek' },
      views: {
      dayGridMonth: { // name of view
        titleFormat: { year: 'numeric', month: '2-digit', day: '2-digit' }
        // other view-specific options here
        }
      },
      timeZone: 'UTC',
      locale: window.selectedLanguage,
      events: function(info, successCallback, failureCallback) {

        window.ajax({
          url: '/api/calendar/events',
          method: "GET",
          data: {
            start: dateToUtcFormat(info.start),
            end: dateToUtcFormat(info.end),

            language: window.selectedLanguage,
          },
          success: function(data) {
            successCallback(data);    
          },
          error: function(err) {
            alert('Une erreur est survenue');
          }
        })
         
      },
      eventClick: function(info) {

        const props = info.event._def.extendedProps;
        const start_date = moment(props.start_date).toDate();
        const end_date = moment(props.end_date).toDate();

        const modal = $('#modal');

        if(window.user.is_manager_group)
        {
          modal.data("user", {
            action: null,
          });
          modal.find('.modal-title').html('Information');
          modal.find('.modal-body').html(`
          <ul class="list-group list-group-flush">
                <li class="list-group-item">  <i style="color:grey;"> D??but cr??neau : </i> ` + dateToString(start_date) + ` </li>
                <li class="list-group-item">  <i style="color:grey;"> Fin cr??neau : </i> ` + dateToString(end_date) + ` </li>
                <li class="list-group-item">  <i style="color:grey;"> R??f??rence cr??neau : </i> ` + props.reference + ` </li>
                <li class="list-group-item">  <i style="color:grey;"> Id cr??neau : </i> ` + props.resourceId + ` </li>
            </ul>
          `);

        }else
        {
          modal.data("user", {
            action: "book",
            id_event: props.resourceId,
          });
          modal.find('.modal-title').html('Confirmation');
          modal.find('.modal-body').html('Voulez vous r??server le cr??neau ' + dateToString(start_date) + " - " + dateToString(end_date) + " ?");

          modal.data('meta_data', {
            reload_page_on_close: true,
          });
        }

        modal.modal('show');

        // change the border color just for fun
        //info.el.style.backgroundColor = 'red';
      }
    });
    calendar.render();

    return calendar;
  }

  function dateToString(date, parse=false)
  {
    if(parse)
    {
      date = moment(date).toDate();
    }

    var d = date.getDate();
    var m =  date.getMonth();
    m += 1;  // JavaScript months are 0-11
    var y = date.getFullYear();

    var h = date.getHours();
    var min = date.getMinutes();
    
    return ((d<10)?("0"+d):d) + "/" + ((m<10)?("0"+m):m) + "/" + (y) + " " + ((h<10)?("0"+h):h) + ":" + ((min<10)?("0"+min):min);
  }

  function rankToColor(rank)
  {
    if(rank<0 || rank>=RANK_COLORS.length)//default value
    {
        return "";
    }

    return RANK_COLORS[rank];
  }

  function itemStateToHtml(right_level, item, reverse_item_states, item_front_values)
  {
    var color = "";
    var title = "";

    if(item.state in reverse_item_states)
    {
      const state_key = reverse_item_states[item.state];
      var res = item_front_values["states"]["values"][state_key];
      color = res.color;
      title = res.title;

    }else
    {
      var res = item_front_values["states"]["default"];
      color = res.color;
      title = res.title;
    }

    if(right_level=="admin")
    {
      return '<div class="event-state-cell" style="display:block; text-align:center; background-color:' + color + ';">' +  title + '</div>'
        + (((!item.id_user_state)?"":`
          <br/>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"> <i>Id responsable </i> : ` + item.id_user_state + ` </li>
                ` + ((item.user_state && item.user_state.name)?('<li class="list-group-item"> <i>Responsable de l\'action : </i> ' + item.user_state.name + '</li>'):"" ) + `
                ` + ((item.id_user)?('<li class="list-group-item"> <i>Action r??alis??e par l\'utilisateur : </i> ' + ((item.id_user_state==item.id_user)?"oui":"non") + '</li>'):"" ) + `
                <li class="list-group-item"> <i>Date de l'action : </i> ` + item.date_state + ` </li>
                <li class="list-group-item text-wrap width-200"> <i>Motif : </i> ` + (item.reason_state?item.reason_state:"") + ` </li>
            </ul>

      `));
    }else
    {
      return '<div class="event-state-cell" style="display:block; text-align:center; background-color:' + color + ';">' +  title + '</div>'
        + (((!item.id_user_state)?"":`
          <br/>
            <ul class="list-group list-group-flush">
                ` + (item.reason_state?'<div class="list-group-item text-wrap width-200"> <i>Info statut : </i> ' + item.reason_state + ' <div> ':'') + `
            </ul>

      `));
    }
    
  }

  function initLog(container, user, events, actions, sections_to_display, all_users)
  {
    container = $(container);

    var has_prev = false;

    if(sections_to_display.has('user') && !all_users && ("data" in user))
    {
      const user_data = user.data;

      if(has_prev)
      {
        container.append('<br/><br/>');
      }
      has_prev = true;

      if(!window.user.is_manager_group)
      {
        container.append(`
          <div class="alert alert-info" role="alert">
            Ici vous pouvez visualiser l'ensemble de vos visites ?? venir, annul??e ou en cours de traitement.
            <br/>
            <b>VOUS POUVEZ ANNULER UNE visite</b> qui n'a pas encore ??t?? faite en <b>CLIQUANT SUR LA LIGNE</b> correspondante pour faire appara??tre la <b>COLONNE "Actions"</b>.
          </div>
        `);

      }

      if(window.user.is_manager_group)
      {

        container.append(`
        <div class="card" style="">
          <div class="card-body">
            <h5 class="card-title">` + (window.user.is_manager_group?"Information utilisateur":"Mes informations") + `</h5>
            <h6 class="card-subtitle mb-2 text-muted">Id : ` + user_data.id + `</h6>
          </div>
          <ul class="list-group list-group-flush">
            ` + ((user_data.name)?('<li class="list-group-item"> <i style="color:grey;"> Nom : </i> ' + user_data.name + '</li>'):"" ) + `
              <li class="list-group-item"> <i style="color:grey;">T??l??phone : </i> ` + user_data.phone + ` </li>
              ` + (("rank" in user_data)?('<li class="list-group-item"> <i style="color:grey;">Appr??ciation personelle : </i>  <h7 style="background-color:' + rankToColor(user_data.rank) + ';"> &nbsp;&nbsp;' + (user_data.rank<0?null:user_data.rank) + '/5' + '&nbsp;&nbsp;&nbsp;</h7> </li>'):'') + `
              ` + (("interest" in user_data)?'<li class="list-group-item"> <i style="color:grey;">Int??r??t imm??diat pour le bien : </i> ' + ((user_data.interest==0)?"non":"oui")  + '</li>':'') + `
              ` + (("info" in user_data)?'<li class="list-group-item"> <i style="color:grey;">Information compl??mentaire : </i> <p class="text-wrap width-200">' + (user_data.info?user_data.info:"") + '</p> </li>':'') + `
          </ul>
        </div>
        `);
      }else
      {
        container.append(`
          <div class="card" style="">
            <div class="card-body">
              <h5 class="card-title">Mes informations </h5>
              <h6 class="card-subtitle mb-2 text-muted">T??l??phone : ` + user_data.phone + `</h6>
            </div>
          </div>
          `);
      }
    }

    if(sections_to_display.has('events') && ("data" in events))
    {
      const events_data = events.data;
      const reverse_cancellabed_event_states = {};
      for(var index in events.cancellabled_states)
      {
        var state_name = events.cancellabled_states[index];
        var key = events.states[state_name];
        reverse_cancellabed_event_states[key] = state_name;
      }

      if(has_prev)
      {
        container.append('<br/><br/>');
      }
      has_prev = true;

      const id_table_events = uuidv4();
      container.append('<h5>Listes des visites</h5><br/><table id="' + id_table_events + '" class="display nowrap events-table" width="100%" cellspacing="0"></table>');


      const table_events_meta_data = {
          id:  {
              column :  { data: "id", title:"Id"},
          },
          start_date:  {
              column : { data: "start_date", title: "D??but du cr??neau" },
          },
          end_date:  {
              column : { data: "end_date", title: "Fin du cr??neau" },
          },
          reference:  {
              column :  { data: "reference", title: "R??f??rence" },
          },
          state:  {
              column :   { data: "state", title: "Statut"},
              columnDef: {
                "render": function ( data, type, row )  {
                    return itemStateToHtml(window.user.data.right_level_name, row, events.reverse_states, events.front_values);
                }
              }
          },
          late:  {
              column :   { data: "late", title: "Retard (min)"},
          },
          actions: {
            column:  { defaultContent: '', title: "Actions", visible:!window.user.is_manager_group},
            columnDef: {
                "render": function ( data, type, row )  {
                    if(window.user.is_manager_group)
                    {
                      return "";
                    }

                    if(row.state in reverse_cancellabed_event_states)
                    {
                      return '<button class="btn btn-danger btn-unbook" onclick="">Annuler</button>';
                    }

                    return "";
                  }
              },
          },
          phone:  {
              column :   {data: "user", title: "T??l??phone"},
              columnDef: {
                "render": function ( data, type, row )  {
                    return data.phone;
                }
            }
          },
          interest:  {
              column :   {data: "user", title: "Int??r??t imm??diat"},
              columnDef: {
                "render": function ( data, type, row )  {
                    return ((data.interest==0)?"non":"oui");
                }
              }
          },
          rank:  {
              column :   {data: "user", title: "Appr??ciation personnelle"},
              columnDef: {
                "render": function ( data, type, row )  {
                    return '<h7 style="background-color:' + rankToColor(data.rank) + ';"> &nbsp;&nbsp;' + data.rank + '/5' + '&nbsp;&nbsp;&nbsp;</h7>';
                }
            }
          },
          info:  {
              column: {data: "user", title: "Informations"},
              columnDef: {
                "render": function ( data, type, row )  {
                    return  '<div class="text-wrap width-200">' + (data.info?data.info:"") + '</div>';
                }
            }
          },
      };

      let columns_order = [];


      //toggle, id, start_date, end_date, reference, state, late, actions, phone, interest, rank, info
      if(window.user.data.right_level_name=="admin")
      {
        if(all_users)
        {
          columns_order = ["id", "reference", "start_date", "end_date", "state", "phone", "rank", "interest", "late", "info"];
        }else
        {
          columns_order = ["id", "reference", "start_date", "end_date", "state"];
        }
    
      }else if(window.user.data.right_level_name=="manager")
      {
        if(all_users)
        {
          columns_order = ["reference", "start_date", "end_date", "state", "phone", "rank", "interest", "late", "info"];
        }else
        {
          columns_order = ["reference", "start_date", "end_date", "state"];
        }

      }else
      {
        columns_order = ["reference", "state", "actions", "start_date", "end_date"];
      }

      let columns = [
        { defaultContent: '<i class="bi bi-plus-circle" style="pointer-events: none;"></i>' },
      ];
      let columnDefs = [];

      var index = columns.length;
      for(var i in columns_order)
      {
        const column_name = columns_order[i];

        const item = table_events_meta_data[column_name];

        columns.push(item.column);
        if(item.columnDef)
        {
          columnDefs.push({
            ...item.columnDef,
            "targets": index,
          });
        }

        index++;
      }

      const table_events = $('#'+id_table_events).DataTable( {
          dom: 'Bfrtip',
          buttons: [
              'csv', 'excel', 'pdf'
          ],
          responsive: {
            details: {
                  type: 'column',
                  target: 0
              }
          },
          data: events_data,
          stripeClasses: ['odd-row', 'even-row'],
          columns: columns,
          columnDefs: columnDefs,
      } );

      
      $('#'+id_table_events + " tbody").click(function(e) {
        //e.preventDefault();

        const target = $(e.target);
        let tr = target.closest('tr');
        if(tr.hasClass("child"))
        {
          tr = tr.prev();
        }

        if(target.hasClass('btn-unbook'))
        {
          var data = table_events.row(tr).data();

          const modal = $("#modal");
          modal.data("user", {
            action: "unbook",
            id_event: data.id,
            id_action: data.id_action,
          });

          modal.data('meta_data', {
            reload_page_on_close: true,
          });

          modal.find('.modal-title').html('Confirmation');
          modal.find('.modal-body').html('Voulez vous annuler le cr??neau ' + dateToString(data.start_date, parse=true) + " - " + dateToString(data.end_date, parse=true) + " ?" + `
            <div style="padding-top:10;"></div>
            <div class="form-group">
              <label for="exampleFormControlTextarea1">Raison : </label>
              <textarea class="form-control" id="reason_state" rows="3"></textarea>
            </div>
          `);
          modal.modal('show');
        }else if(target.hasClass('table-cell-btn'))
        {
          if(!target.data('table-cell-btn'))
          {
            //let click passthrough
            target.data('table-cell-btn', true);
            target.click();
          }

          target.data('table-cell-btn', false);

        }else
        {
          const toggle_td = tr.find("td[tabindex='0']");
          if(toggle_td.length>0)
          {
            if(target.attr('tabindex')!=0)
            {
              toggle_td.click();
            }
          }
        }
      });
    }

    if(sections_to_display.has('actions') && ("data" in actions) && window.user.right_level_name=="admin")
    {
      const actions_data = actions.data;

      if(has_prev)
      {
        container.append('<br/><br/>');
      }
      has_prev = true;


      const id_table_actions = uuidv4();
      container.append('<h5>Listes des actions</h5><br/><table id="' + id_table_actions + '" class="display nowrap events-table" width="100%" cellspacing="0"></table>');

      var columns = [
              { defaultContent: '<i class="bi bi-plus-circle" style="pointer-events: none;"></i>' },
              { data: "id", title: "Id", visible: true},
              { data: "type", title: "Type" },
              { data: "state", title: "Statut"},
              { data: "public_data", title: "Information"},
              { data: "public_data", title: "Historique"},
              { data: "created_at", title: "Date de cr??ation"}
      ];

      var columnDefs = [
            {
              "targets": 2,
              "render": function ( data, type, row )  {

                for(var type_name in actions.types)
                {
                  var val = actions.types[type_name];
                  if(val==data)
                  {
                    var res = actions.front_values["types"]["values"][type_name];
                    return res.title;
                  }
                }
                
                return data;
              }
            },
            {
              "targets": 3,
              "render": function ( data, type, row )  {
                  return itemStateToHtml(window.user.right_level_name, row, actions.reverse_states, actions.front_values);
              }
            },
            {
              "targets": 4,
              "render": function ( data, type, row )  {

                  json_data = JSON.parse(data);
                  
                  if("calendar_event" in json_data)
                  {
                    const event = json_data.calendar_event;
                    return '<p class="text-wrap width-200">' + 'Action relative ?? la visite ' + event.reference + ' sur le cr??neau : ' + event.start_date + ' - ' + event.end_date + '</p>';
                  }else
                  {
                    return "";
                  }
              },
            },
            {
              "targets": 5,
              "render": function ( data, type, row )  {

                  json_data = JSON.parse(data);

                  if("history" in json_data)
                  {
                    var text = "";
                    const history = json_data.history;
                    for(var index in history)
                    {
                      const item = history[index];

                      text += '<i style="color:grey;"> ??tat : </i>' + actions.front_values.states.values[actions.reverse_states[item.state]].title + '<br/>';
                      text += '<i style="color:grey;"> Id utilisateur responsable : </i>' + item.id_user_state + '<br/>';
                      text += '<i style="color:grey;"> Reason : </i>' + item.reason_state + '<br/>';
                      text += '<i style="color:grey;"> Date : </i>' + item.date_state + '<br/>';

                      text += '<hr/> <br/>';
                    }
                    return text;
                  }else
                  {
                    return "";
                  }
              }
            },
      ];

      if(all_users)
      {
        let index = null;

        index = columns.length;
        columns.push({data: "user", title: "T??l??phone"});
        columnDefs.push({
              "targets": index,
              "render": function ( data, type, row )  {
                  return data.phone;
              }
        });
      }



      const table_actions = $('#'+id_table_actions).DataTable( {
          responsive: {
            details: {
                  type: 'column',
                  target: 0
              }
          },
          dom: 'Bfrtip',
          buttons: [
            'csv', 'excel', 'pdf'
          ],
          data: actions_data,
          stripeClasses: ['odd-row', 'even-row'],
          columns: columns,
          columnDefs: columnDefs,
      } );

      $('#'+id_table_actions + " tbody").click(function(e) {
        //e.preventDefault();
        var data = table_actions.row($(this).closest('tr')).data();

        const target = $(e.target);
        let tr = target.closest('tr');
        if(tr.hasClass("child"))
        {
          tr = tr.prev();
        }

       if(target.hasClass('table-cell-btn'))
        {
          if(!target.data('table-cell-btn'))
          {
            //let click passthrough
            target.data('table-cell-btn', true);
            target.click();
          }

          target.data('table-cell-btn', false);

        }else
        {
          const toggle_td = tr.find("td[tabindex='0']");
          if(toggle_td.length>0)
          {
            if(target.attr('tabindex')!=0)
            {
              toggle_td.click();
            }
          }
        }
      });
    }

    $(".dt-button").addClass("btn btn-secondary");

  }

  function loadLog(args)
  {
    const container = $(args.container);

    const id_user = args.id_user;
    const start_date = args.start_date;
    const end_date = args.end_date;
    const sections = new Set(((args.sections)?args.sections:['user', 'events', 'actions']));
    const done_request_function = args.doneRequestCallback;
    const ready_function = args.readyCallback;
    const end_function = args.endCallback;
    const all_users = args.all_users?args.all_users:false;
    const request_response_only = args.requestResponseOnly?args.requestResponseOnly:false;
    
    window.ajax({
      url: "/api/calendar/getUserEvents",
      type: "GET",
      data: {
          id_user: id_user,
          start_date: start_date,
          end_date: end_date,
          all_users: all_users,
          language: window.selectedLanguage
      },
      success: function(data) {
        try
        {
          if(done_request_function)
          {
            done_request_function(data);
          }

          if(request_response_only)
            return ;

          data = data["data"];

          container.html("");
          initLog(container, data.user, data.events, data.actions, sections, all_users);

          if(ready_function)
          {
            ready_function();
          }

          if(end_function)
            end_function(true);

        }catch(err)
        {
          if(end_function)
            end_function(false);

          throw err; 
        }
      },
      error: function(err) {
        console.error(err);

        if(end_function)
          end_function(false);

        alert("Une erreur est survenue");
      }
    });
  }

  function getWeekDateInterval(start=null)
  {
    if(start==null)
      start = new Date;
    
    var curr = start; // get current date
    var first = curr.getDate() - curr.getDay(); // First day is the day of the month - the day of the week
    var last = first + 6; // last day is the first day + 6

    var firstday = new Date(curr.setDate(first)).toUTCString();
    var lastday = new Date(curr.setDate(last)).toUTCString();
    
    return [firstday, lastday];
  }

  function reload()
  {
    let url = "{{ Route::getCurrentRoute()->uri }}";
    if(typeof store == 'undefined')
    {
      url += "?" + "access_token=" + window.user.token;
    }

    document.location.href = url;
  }

  function createAjaxRequest(parms, token)
  {
      const url = parms.url;
      const type = parms.type;
      const data = parms.data?parms.data:{};
      const succes_function = parms.success;
      const error_function = parms.error;

      $.ajax({
        type: type,
        headers: {
          'Authorization': 'Bearer ' + token,
        },
        url: url,
        cache: false,
        data: data,
        success: function(data) 
        {
          if(succes_function)
            succes_function(data);
        },
        error: function(jqXHR, textStatus, err) 
        {
          if(jqXHR.status==498)
          {
            goToPage("login");
            return ;
          }

          if(error_function)
            error_function(jqXHR);
        }
      });
  }

  function isDict(v) {
    return typeof v==='object' && v!==null && !(v instanceof Array) && !(v instanceof Date);
  } 

  function initSummary(can_see)
  {
    if(!can_see)
    {
      $("#summary_container_header").addClass("d-none");
      $("#summary_container").html('<div class="alert alert-warning" role="alert">Vous ne pouvez pas acc??der ?? cette section</div>');
      return ;
    }
    const week_dates = getWeekDateInterval();

    const start_datetimepicker = flatpickr("#summary_start_datetimepicker", {
        enableTime: true,
        time_24hr: true,
        allowInput:false,
        locale: "fr",
        formatDate: (arg) => {
          return dateToString(arg);
        },
        static:false,
    });

    start_datetimepicker.setDate(week_dates[0]);

    const end_datetimepicker = flatpickr("#summary_end_datetimepicker", {
        enableTime: true,
        time_24hr: true,
        allowInput:false,
        locale: "fr",
        formatDate: (arg) => {
            return dateToString(arg);
        },
        static:false,
    });

    end_datetimepicker.setDate(week_dates[1]);


    $("#summary_validate_datetimes").click(function () {
        const start_date = start_datetimepicker.selectedDates[0].toUTCString();
        const end_date = end_datetimepicker.selectedDates[0].toUTCString();
        startLoading();
        loadLog({
          'container' : $("#summary_container"),
          'start_date': start_date,
          'end_date': end_date,
          'sections': ['events', 'actions'],
          'all_users': true,
          'endCallback': function(no_error) {
            endLoading();
          }
        });

    }); 
  }

  function search()
  {
    const container = $("#search_container");
    startLoading();
    const query = $("#input_search").val();

    $("#search").find(".spinner").removeClass("d-none");

    window.ajax({
      url: "/api/search",
      type: "POST",
      data: {
        search_query: query,
      },
      success: function(data) {
        endLoading();

        if("error" in data)
        {
          console.error(data);
          return ;
        }
        data = data["data"];
        

        if(data.length==0 || !data[0])
        {
          container.html('<div class="alert alert-warning" role="alert">Aucun r??sultat de recherche.<br/>V??rifier que les formats de recherche sont corrects</div>');
        }else
        {
          loadLog({
            'container' : container,
            'id_user': data,
          });
        }

      },
      error: function(err) {
        console.error(err);
        alert("Une erreur est survenue");

        endLoading();
      }
    });

    $("#search").find(".spinner").addClass("d-none");
  }

  function initSearch(can_see)
  {
    if(!can_see)
    {
      $("#search_container_header").addClass("d-none");
      $("#search_container").html('<div class="alert alert-warning" role="alert">Vous ne pouvez pas acc??der ?? cette section</div>');
      return ;
    }
    $('#input_search').on('keypress', function(e) {
        if (event.which == 13) 
        {
          e.preventDefault();
          search();
          return false;
        }
        
        return true;
    });


    $("#btn_search").click(function() {
      search();
    })
  }

  function startLoading()
  {
    const modal = $("#modal_loading");
    modal.modal('show');
  }

  function endLoading()
  {
    const modal = $("#modal_loading");
    modal.modal('hide');
  }


  function initPersonnalLog(can_see)
  {
    loadLog({
          'container' : $("#actions_container"),
          'requestResponseOnly': !can_see,
          "doneRequestCallback": function(data) {
            data = data["data"];
            initCalendar(data.can_see_calendar);
          },
          "readyCallback": function(no_error) {
            endLoading();
          },
    });

    if(!can_see)
    {
      endLoading();
      $("#actions_container").html('<div class="alert alert-info" role="alert">Aucune donn??es ?? afficher</div>');
    }
  }

  function init()
  {
    startLoading();

    var sections_to_hide = [];
    if(window.user.data.right_level_name=="admin")
    {
      //do nothing
    }else if(window.user.data.right_level_name=="manager")
    {
      sections_to_hide = ["actions"];
    }else
    {
      sections_to_hide = ["summary", "search"];
    }

    for(var i in sections_to_hide)
    {
      const section_name = sections_to_hide[i];

      $(".navbar").find('a[href="#' + section_name + '"]').closest('li').addClass('d-none');
      $("#"+section_name).addClass('d-none');
    }

    window.ajax({
      url: "/api/info",
      type: "GET",
      data: {
      },
      success: function(data) {
        const info = data.data;

        window.info = info;
        window.selectedLanguage = info.available_languages[0].code;

        $("#timezone").html(info.timezone.value);
        
        var options = "";
        for(var i in info.available_languages)
        {
          const language = info.available_languages[i];
          options += '<option value="' + language.code + '">' + language.name + '</option>';
        }
        $("#select_language").append(options).select();

        $("#select_language").change(function() {
          window.selectedLanguage = $(this).val();
        });

        const user = window.user.data;
        const can_see = (user.right_level_name=="admin" || user.right_level_name=="manager");
        window.user.is_manager_group = can_see;

        initPersonnalLog(!can_see);
        initSummary(can_see);
        initSearch(can_see);
      },
      error: function(err) {
        alert("Une erreur est survenue");
      }
    });

  }

  function documentReady()
  {
    let token = null;
    try 
    {
        if(typeof store !== 'undefined')
        {
          const user = store.get('user');
          window.user = user;
          token = user.token;
        }else
        {
          token = "{{$access_token}}";
          window.user = null;
          window.history.pushState('Acceuil', 'Visite', '/');
        }

    }catch (err) 
    {
      goToPage("login");
    }

    if(token==null || (typeof token !=="string") || token.length==0)
    {
      goToPage("login");
    }

    window.ajax = (parms) => createAjaxRequest(parms, token);

    $("#btn_disconnect").click(function() {
      goToPage("login");
    });

    $("#modal").on("hide.bs.modal", function () {
        const data = $(this).data('meta_data');
        $(this).data('meta_data', {});

        if(data && isDict(data) && ("reload_page_on_close" in data) && data.reload_page_on_close==true)
        {
          //document.location.reload();
          reload();
        }
    });

    if(window.user==null)
    {
      window.ajax({
        url: "/api/profile",
        type: "GET",
        success: function(data) {
          window.user = {
            token: token, 
            data: data
          };

          init();
        },
        error: function(err) {
          goToPage('login');
        }
      });
    }else
    {
      init();
    }
  }

  $(document).ready(function(){
 // start load my js functions
 documentReady();
});

  


</script>

</html>