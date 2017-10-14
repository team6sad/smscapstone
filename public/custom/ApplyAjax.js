$(document).ready(function() {
  var selectedBarangay = 0;
  var DistrictName = '';
  var selectedCouncilor = 0;
  var CouncilorName = '';
  var url = "/apply";
  var ctr = 0;
  var checker = 0;
  var dt = new Date();
  var grade = 1;
  var counter = 1;
  var subject = 0;
  dt.setFullYear(new Date().getFullYear() - 18);
  $('#datepicker').datepicker({
    viewMode: "years",
    endDate: dt,
    autoclose: true,
    format: 'yyyy-mm-dd'
  });
  window.Parsley.on('field:error', function(fieldInstance) {
    if (fieldInstance.$element[0].type == 'file') {
      var instanceName;
      if (fieldInstance.$element[0].name == 'strApplPicture') {
        instanceName = $('.images');
      } else {
        instanceName = $('.pdf');
      }
      instanceName.popover({
        trigger: 'manual',
        container: 'body',
        placement: 'auto',
        content: function() {
          return fieldInstance.getErrorsMessages().join(';');
        }
      }).popover('show');
    } else if (fieldInstance.$element[0].type == 'textarea') {
      fieldInstance.$element.popover({
        trigger: 'manual',
        container: 'body',
        placement: 'auto',
        content: function() {
          return fieldInstance.getErrorsMessages().join(';');
        }
      }).popover('show');
    } else {
      fieldInstance.$element.val('');
      fieldInstance.$element.attr('placeholder', fieldInstance.getErrorsMessages().join(';'));
    }
  });
  window.Parsley.on('field:success', function(fieldInstance) {
    if (fieldInstance.$element[0].type == 'file') {
      var instanceName;
      if (fieldInstance.$element[0].name == 'strApplPicture') {
        instanceName = $('.images');
      } else {
        instanceName = $('.pdf');
      }
      instanceName.popover('destroy');
    } else if (fieldInstance.$element[0].type == 'textarea') {
      fieldInstance.$element.popover('destroy');
    }
    fieldInstance.$element.removeAttr('placeholder');
  });
  function scroll_to_class(element_class, removed_height) {
    var scroll_to = $(element_class).offset().top - removed_height;
    if ($(window).scrollTop() != scroll_to) {
      $('html, body').stop().animate({ scrollTop: scroll_to }, 0);
    }
  }
  function bar_progress(progress_line_object, direction) {
    var number_of_steps = progress_line_object.data('number-of-steps');
    var now_value = progress_line_object.data('now-value');
    var new_value = 0;
    if (direction == 'right') {
      new_value = now_value + (100 / number_of_steps);
    } else if (direction == 'left') {
      new_value = now_value - (100 / number_of_steps);
    }
    progress_line_object.attr('style', 'width: ' + new_value + '%;').data('now-value', new_value);
  }
  var $sections = $('.form-section');
  $.backstretch("../../img/backgrounds/1apply.jpg");
  $('.f1 .form-section:first').fadeIn('slow');
  scroll_to_class($('.f1'), 20);
  function navigateTo(index) {
    $sections
    .removeClass('current')
    .eq(index)
    .addClass('current');
    if (index != 1) {
      $('.form-navigation .previous').toggle(index > 0);
      var atTheEnd = index >= $sections.length - 1;
      $('.form-navigation .next').toggle(!atTheEnd);
    } else {
      $('.form-navigation .previous').show();
      $('.form-navigation .next').toggle();
    }
  }
  function curIndex() {
    return $sections.index($sections.filter('.current'));
  }
  $('.form-navigation .previous').click(function() {
    $('#frmApply').parsley().destroy();
    $('input[type="text"], textarea').removeAttr('placeholder');
    ctr--;
    navigateTo(curIndex() - 1);
    var current_active_step = $(this).parents('.f1').find('.f1-step.active');
    var progress_line = $(this).parents('.f1').find('.f1-progress-line');
    current_active_step.removeClass('active').prev().removeClass('activated').addClass('active');
    bar_progress(progress_line, 'left');
    $(this).prev().fadeIn();
    scroll_to_class($('.f1'), 20);
  });
  $('.form-navigation .next').click(function() {
    var pass = true;
    if ($('.f1').parsley().validate({ group: 'block-' + curIndex() })) {
      if (ctr == 2) {
        if (checker) {
          if ($('#strSiblDateFrom').val() >= $('#strSiblDateTo').val()) {
            pass = false;
            alert('Date To Must Not be equal or less than Date From')
          }
        }
      }
      if (ctr == 3) {
        if (grade == 0) {
          if ($('#year').val() == 1 && $('#semester').val() == 1) {
            pass = false;
            alert('Cannot Select First Year First Semester');
          }
        }
      }
      if (ctr == 4) {
        if ($.trim($('[name="essay"]').val()).split(' ').filter(function(v){return v!==''}).length < 300) {
          pass = false;
          alert('Essay must more the 300 words');
        }
      }
      if (pass) {
        navigateTo(curIndex() + 1);
        var current_active_step = $(this).parents('.f1').find('.f1-step.active');
        var progress_line = $(this).parents('.f1').find('.f1-progress-line');
        current_active_step.removeClass('active').addClass('activated').next().addClass('active');
        bar_progress(progress_line, 'right');
        $(this).next().fadeIn();
        scroll_to_class($('.f1'), 20);
        ctr++;
      }
    }
    if (ctr == 5) {
      var ctr_organization = $('input[name="strPersOrganization[]"]').length;
      var div = "<label>Councilor:</label> " + CouncilorName + " <br>" +
      "<hr>" +
      "<label>Personal Info </label> <br>" +
      "<label>Applicant's Name:</label> " + $('#fname').val() + " " + $('#mname').val() + " " + $('#lname').val() + " <br>" +
      "<label>Gender:</label> " + $("select[name='PersGender'] option:selected").text() + "<br>" +
      "<label>Birth Date:</label> " + $('#datepicker').val() + " <br>" +
      "<label>Place of Birth:</label> " + $('#pob').val() + " <br>" +
      "<label>Religion:</label> " + $('#strPersReligion').val() + " <br>" +
      "<label>Address:</label> " + $('#strApplHouseNo').val() + " " +
      $('#stname').val() + " " +
      $("select[name='intBaraID'] option:selected").text() + " " + DistrictName +
      " <br>" +
      "<label>Mobile Number:</label> " + $('#strUserCell').val() + " <br>" +
      "<label>Email Address:</label> " + $('#email').val() + " <br>" +
      "<hr>" +
      "<label>Family Background</label> <br>" +
      "<div class='row'>" +
      "<div class='col-md-6'>" +
      "<label>Mother's Name:</label> " + $('#motherfname').val() + " " + $('#motherlname').val() + " <br>" +
      "<label>Citizenship:</label> " + $('#mothercitizen').val() + " <br>" +
      "<label>Highest Attainment:</label> " + $('#motherhea').val() + " <br>" +
      "<label>Occupation:</label> " + $('#motheroccupation').val() + " <br>" +
      "<label>Monthly Income:</label> " +
      $("select[name='motherincome'] option:selected").text() +
      " <br>" +
      "</div>" +
      "<div class='col-md-6'>" +
      "<label>Father's Name:</label> " + $('#fatherfname').val() + " " + $('#fatherlname').val() + " <br>" +
      "<label>Citizenship:</label> " + $('#fathercitizen').val() + " <br>" +
      "<label>Highest Attainment:</label> " + $('#fatherhea').val() + " <br>" +
      "<label>Occupation:</label> " + $('#fatheroccupation').val() + " <br>" +
      "<label>Monthly Income:</label> " +
      $("select[name='fatherincome'] option:selected").text() +
      " <br>" +
      "</div>" +
      "</div>" +
      "<div class='row'>" +
      "<div class='col-md-6'>" +
      "<label>Number of Brother/s:</label> " + $('#brono').val() + " <br>" +
      "<label>Number of Sister/s:</label> " + $('#sisno').val() + " <br>" +
      "</div>";
      if (checker) {
        div += "<div class='col-md-6'>" +
        "<label>Name:</label> " + $('#strSiblFirstName').val() + " " + $('#strSiblLastName').val() + " <br>" +
        "<label>From:</label> " + $('#strSiblDateFrom').val() + " <label>To:</label> " + $('#strSiblDateTo').val() + " <br>" +
        "</div>";
      }
      div += "</div>" +
      "<hr>" +
      "<label>Educational Background</label> <br>" +
      "<div class='row'>" +
      "<div class='col-md-6'>" +
      "<label>Elementary</label> <br>" +
      "<label>School Name:</label> " + $('#elemschool').val() + " <br>" +
      "<label>Year Enrolled:</label> " + $('#elemenrolled').val() + " <br>"+
      "<label>Year Graduated:</label> " + $('#elemgrad').val() + " <br>" +
      "<label>Achievements/Honors:</label> " + $('#elemhonors').val() + " <br>" +
      "</div>" +
      "<div class='col-md-6'>" +
      "<label>High School</label> <br>" +
      "<label>School Name:</label> " + $('#hschool').val() + " <br>" +
      "<label>Year Enrolled:</label> " + $('#hsenrolled').val() + " <br>" +
      "<label>Year Graduated:</label> " + $('#hsgrad').val() + " <br>" +
      "<label>Achievements/Honors:</label> " + $('#hshonor').val() + " <br>" +
      "</div>" +
      "</div>" +
      "<hr>" +
      "<label>College</label> <br>" +
      "<div class='row'>" +
      "<div class='col-md-6'>" +
      "<label>School/University Currently Enrolled In:</label> " + $("select[name='intPersCurrentSchool'] option:selected").text() + " <br>" +
      "</div>" +
      "<div class='col-md-6'>" +
      "<label>Current Course:</label> " + $("select[name='intPersCurrentCourse'] option:selected").text() + " <br>" +
      "</div>" +
      "</div>";
      console.log(grade);
      if (grade == 0) {
        div += "<br><label>Grade</label> <br>"+
        "<div class='row'>";
        for (var i = 0; i <= subject; i++) {
          div += "<div class='col-md-4'>" +
          "<label>Description:</label> " + $('.subject_description')[i].value + " <br>" +
          "</div>" +
          "<div class='col-md-4'>" +
          "<label>Units:</label> " + $('.units')[i].value + "<br>" +
          "</div>" +
          "<div class='col-md-4'>" +
          "<label>Grade:</label> " + $(".subject_grade")[i].value + "<br>" +
          "</div>";
        }
        div += "</div>";
      }
      div += "<hr>" +
      "<label>Community Involvement/Affiliation</label> <br>" +
      "<div class='row'>";
      for (var i = 0; i < ctr_organization; i++) {
        if ($('.organization')[i].value != '' && $('.position')[i].value != '') {
          div += "<div class='col-md-6'>" +
          "<label>Organization:</label> " + $('.organization')[i].value + " <br>" +
          "</div>" +
          "<div class='col-md-3'>" +
          "<label>Position:</label> " + $('.position')[i].value + " <br>" +
          "</div>" +
          "<div class='col-md-3'>" +
          "<label>Year of Participation:</label> " + $('.year')[i].value + " <br>" +
          "</div>";
        } else {
          div += "<div class='col-md-6'>" +
          "<label>Organization:</label> N/A <br>" +
          "</div>" +
          "<div class='col-md-3'>" +
          "<label>Position:</label> N/A <br>" +
          "</div>" +
          "<div class='col-md-3'>" +
          "<label>Year of Participation:</label> N/A <br>" +
          "</div>";
        }
      }
      div += "</div>" +
      "<hr>" +
      "<label>Essay:</label> " + $('#essay').val().substring(0, 50) + "... <br>";
      $('#summary').empty().append(div);
    }
    if (ctr == 1) {
      selectedBarangay = $('#intBaraID').val();
      $.get(url + '/' + selectedBarangay, function(data) {
        $('#councilor').empty();
        $.each(data, function(index, value) {
          var show = "<div class='col-md-6'>" +
          "<div class='box box-widget councilor widget-user-2 text-center' style='cursor: pointer; background-color: #4A5459; border-style: solid;' value=" + value.id + ">" +
          "<div class='widget-user-header'>" +
          "<div class='widget-user-image'>" +
          "<img class='profile-user-img img-responsive img-square' src='" + asset + "/" + value.picture + "' alt='User Avatar'></div>" +
          "<h3 class='widget-user-username text-widget' id=countxt" + value.id + ">" + value.strCounName + "</h3>" +
          "<h5 class='widget-user-desc slot text-widget' id='slot" + value.id + "'>&emsp;</h5></div></div></div>";
          $('#councilor').append(show);
        });
        $('#intDistID').val(data[0].district_id);
      });
    }
  });
$sections.each(function(index, section) {
  $(section).find(':input').attr('data-parsley-group', 'block-' + index);
});
navigateTo(0);
$('#councilor').on('click', '.councilor', function() {
  selectedCouncilor = $(this).attr('value');
  $('#intCounID').val(selectedCouncilor);
  CouncilorName = $('#countxt' + selectedCouncilor).text();
  $.get(url + '/school/' + selectedCouncilor, function(data) {
    var school = "";
    $.each(data, function(index, value) {
      school += "<option value=" + value.id + ">" + value.description + "</option>";
    });
    $('#intPersCurrentSchool').empty().append(school);
  });
  $.get(url + '/course/' + selectedCouncilor, function(data) {
    var course = "";
    $.each(data, function(index, value) {
      course += "<option value=" + value.id + ">" + value.description + "</option>";
    });
    $('#intPersCurrentCourse').empty().append(course);
  });
  $.get(url + '/question/' + selectedCouncilor, function(data) {
    $('.question').empty().append(data.essay);
  });
  $('.form-navigation .next').click();
});
$('input[name="rad"]').on('ifClicked', function(event) {
  if (this.value == "yes") {
    checker = 1;
    $("#questionappear").show("slide", { direction: "up" }, 1000);
  } else {
    checker = 0;
    $("#questionappear").hide();
  }
});
$.each($('input[name="rad"]'), function(index, value) {
  if ($(this).attr('checked'))
    if ($(this).attr('id') == 'yes') {
      checker = 1;
      $("#questionappear").show("slide", { direction: "up" }, 1000);
    }
  });
$('input[name="col"]').on('ifClicked', function(event) {
  if (this.value == "no") {
    $("#college").show("slide", { direction: "up" }, 1000);
    grade = 0;
  } else {
    $("#college").hide();
    grade = 1;
  }
  inputGrade();
});
$.each($('input[name="col"]'), function(index, value) {
  if ($(this).attr('checked'))
    if ($(this).attr('id') == 'no') {
      grade = 0;
      $("#college").show("slide", { direction: "up" }, 1000);
    } else {
      $('.academic').toggle();
    }
  });
$('input').iCheck({
  radioClass: 'iradio_flat-red'
});
$("#frmApply").bind("keypress", function(e) {
  if (ctr != 3) {
    if (e.keyCode == 13) {
      $('#btn-next').click();
      return false;
    }
  }
});
$('.btn-submit').on('click', function(e) {
  e.preventDefault();
  swal({
    title: "Are you sure?",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-success",
    confirmButtonText: "Apply",
    cancelButtonText: "Cancel",
    closeOnConfirm: false,
    allowOutsideClick: true,
    showLoaderOnConfirm: true,
    closeOnCancel: true
  },
  function(isConfirm) {
    setTimeout(function() {
      if (isConfirm) {
        $('#frmApply').submit();
      }
    }, 500);
  });
});
$('.affiliation').click(function() {
  var show = "<div class='add'><div class='form-group col-md-6'>" +
  "<label>Organization</label>"+
  $('.organization')[0].outerHTML + "</div>" +
  "<div class='form-group col-md-3'>" +
  "<label>Position</label>"+
  $('.position')[0].outerHTML + "</div>" +
  "<div class='form-group col-md-3'>" +
  "<label>Year of Participation</label><button type='button' class='btn btn-xs btn-danger pull-right removeorg'><i class='fa fa-remove'></i></button>"+
  "<div class='input-group'>" +
  "<div class='input-group-addon'>" +
  "<i class='fa fa-calendar'></i></div>" + $('.year')[0].outerHTML + "</div></div></div>";
  $('#affiliation').append(show);
});
$('.grade').click(function() {
  if (grade) {
    $('#grade').empty();
    subject = 0;
  } else {
    subject++;
    var show = "<div class='add'><div class='form-group col-md-6'>" +
    "<label>Description</label>"+
    $('.subject_description')[0].outerHTML + "</div>" +
    "<div class='form-group col-md-2'>" +
    "<label>Units</label>"+
    $('.units')[0].outerHTML + "</div>" +
    "<div class='form-group col-md-4'>"+
    "<label>Grade</label><button type='button' class='btn btn-xs btn-danger pull-right removegrade'><i class='fa fa-remove'></i></button>" +
    $('.subject_grade')[0].outerHTML + "</div></div>";
    $('#grade').append(show);
  }
});
$('#affiliation').on('click','.removeorg',function(){
  $(this).parent().parent().remove();
});
$('#grade').on('click','.removegrade',function(){
  $(this).parent().parent().remove();
});
$('.barangay').select2();
$('.dropdownbox').select2();
inputGrade();
function inputGrade() {
  if (grade) {
    $('#academic').toggle(false);
    $('#grade').empty();
  } else {
    $('#academic').toggle(false).toggle();
    $('#grade').empty();
    $.get(url + '/grade/' + $('#intPersCurrentSchool').val(), function(data) {
      var selectGrade = '';
      $.each(data, function(index, value) {
        selectGrade += "<option value=" + value.grade + ">" + value.grade + "</option>";
      });
      var show = "<div class='form-group col-md-6'>" +
      "<label class='control-label'>Description</label>" +
      "<input id='subject_description' class='form-control subject_description' maxlength='45' required='required' autocomplete='off' data-parsley-pattern='^[a-zA-Z0-9 ]+$' name='subject_description[]' type='text'></div>" +
      "<div class='form-group col-md-2'>" +
      "<label class='control-label'>Units</label>" +
      "<input id='units' class='form-control units' maxlength='1' autocomplete='off' required='required' data-parsley-pattern='^[0-9 ]+$' name='units[]' type='text'></div>" +
      "<div class='form-group col-md-4'>" +
      "<label class='control-label'>Grade</label>" +
      "<select id='subject_grade' class='form-control subject_grade' name='subject_grade[]'>" + selectGrade +"</select></div>";
      $('#grade').append(show);
    });
    getCredit();
    $('.subject_description').parsley();
    $('.units').parsley();
    $('.subject_grade').parsley();
  }
}
$('#intPersCurrentSchool').change(function() {
  inputGrade();
  getCredit();
});
$('#intPersCurrentCourse').change(function() {
  getCredit();
});
$('#councilor').on('mouseenter', '.councilor', function() {
  $.get(url + '/count/' + $(this).attr('value'), function(data) {
    $('#slot' + data.id).text('Slot:' + data.slot + '/' + data.max + ' - Queue:' + data.queued);
  });
});
function getCredit()
{
  $.get(url + '/credit/' + $('#intPersCurrentSchool').val() + '/' + $('#intPersCurrentCourse').val(), function(data) {
    var selectYear = '';
    var selectSemester = '';
    var word = '';
    for (var i = 1; i <= data.year; i++) {
      word = getWord(i);
      selectYear += "<option value=" + i + ">" + word + "</option>";
    }
    for (var i = 1; i <= data.semester; i++) {
      word = getWord(i);
      selectSemester += "<option value=" + i + ">" + word + "</option>";
    }
    var show1 = "<select class='form-control' id='year' name='year'>"+ selectYear +"</select>";
    $('.yearCredit').empty().append(show1);
    var show2 = "<select class='form-control' id='semester' name='semester'>"+ selectSemester +"</select>";
    $('.semCredit').empty().append(show2);
  });
}
function getWord(i)
{
  if (i == 1) 
    return 'First';
  else if (i == 2) 
    return 'Second';
  else if (i == 3) 
    return 'Third';
  else if (i == 4) 
    return 'Fourth';
  else if (i == 5) 
    return 'Fifth';
}
$('.wysihtml5-toolbar').remove();
});