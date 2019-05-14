jQuery('document').ready(function() {

  let locationURL = window.location.pathname;

  if (locationURL == '/item/create' || locationURL == '/item/createcollective') {
    getUsersData();
  }

  if (locationURL == '/iusers') {
    let value = getParameterByName('id');
    if (value) {
      $('#search-input').val(value);
      getIuserSearch(value);
    }
  }

  if (locationURL == '/plate') {
    let value = getParameterByName('placa');
    if (value) {
      $('#search-input').val(value);
      getPlateSearch(value);
    }
  }
  
  function getUsersData() {
    let selectUserTypes = $('[type_user]');
    selectUserTypes.map((userType) => {
      $.ajax({
        type: 'GET',
        url: '/api/apiuserstype/' + selectUserTypes[userType].attributes.type_user.value
      }).done(function (response) {
        let userSelect = $(selectUserTypes[userType]);
        if (response) {
          response.data.map((item) => {
            userSelect.append('<option value="' + item.document + '">' + item.name + '</option>');
          });
        }
      });
    });
  }

  function getIuserSearch(elementValue) {

    $('.user-detail').hide();
    $('.user-none').hide();
    $('.required-field').hide();
    $('#item-list').empty();

    if (elementValue) {

      $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
        
      let u = '/api/live-search/search-iuser/' + elementValue;
      let types = '';
      
      $.ajax({
        type: 'GET',
        url: u,
        success: function(response) {
          if (response) {
            if (response.data.user.length > 0) {
              $('.user-detail').show();
              $('#name').html(response.data.user[0].name);
              $('#identification').html(response.data.user[0].document);
              $('#btn-edit').attr('href','/iuser/' + response.data.user[0].id + '/edit');
              if (response.data.sarlaf && response.data.sarlaf != '') {
                $('.content-sarlaft-data').show();
                $('.data-item').removeClass('col-sm-6');
                $('.content-user-data').addClass('col-sm-6');
                $('#annexed').html(response.data.sarlaf);
                $('#expires').html(response.data.sarlaf_duedate);  
              } else {
                $('.data-item').addClass('col-sm-6');
                $('.content-user-data').removeClass('col-sm-6');
                $('.content-sarlaft-data').hide();
              }
              
              if (response.data.items.length > 0) {
                response.data.items.map((item) => {
                  types = '';
                  if (response.data.types[item.id].length > 0) {
                    response.data.types[item.id].map((user_type) => {
                      types = (types != '') ? types + ' &#124; ' + user_type.name : user_type.name
                    });
                  }
                  $('#item-list').append('<tr>' +
                  '<td><a href="/item/'+ item.id +'">' + item.item_number + '</a></td>' +
                  '<td>' + item.bs_name + '</td>' +
                  '<td>' + types + '</td>' +
                  '<td>' + item.created_at + '</td>' +
                  '</tr>');
                });
              } else {
                $('#item-list').append('<tr>' +
                '<td colspan="100">No se encontraron polizas vinculadas al usuario</td>' +
                '</tr>');
              }
            } else {
              $('.user-none').show();
            }
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log(jqXHR.status);
          if (jqXHR.status = 404) {
            $('#tb').empty();
          }
        },
      });
    } else {
      $('.required-field').show();
    }
  }

  function getPlateSearch(elementValue) {

    $('.plate-detail').hide();
    $('.plate-none').hide();
    $('.required-field').hide();
    $('#item-list').empty();

    if (elementValue) {
    
      $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
      
      let u = '/api/live-search/search-plate/' + elementValue;
      
      $.ajax({
        type: 'GET',
        url: u,
        success: function(response) {
          if (response) {
            $('.plate-detail').show();
            if (response.data.length > 0) {
              response.data.map((item) => {
                $('#item-list').append('<tr>' +
                '<td><a href="/item/'+ item.id +'">' + item.item_number + '</a></td>' +
                '<td>' + item.bs_name + '</td>' +
                '<td>' + item.created_at + '</td>' +
                '</tr>');
              });
            } else {
              $('#item-list').append('<tr>' +
              '<td colspan="100">No se encontraron polizas vinculadas a la placa</td>' +
              '</tr>');
            }
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.status);
            if (jqXHR.status = 404) {
              $('#item-list').empty();
            }
        },
      });
    } else {
      $('.required-field').show();
    }
  }
  
  function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
  }

  //Upload multiple images
  $(".btn-success").click(function() { 
      var html = $(".clone").html();
      $(".increment").after(html);
  });

  $("body").on("click", ".btn-danger", function () {
    $(this).parents(".control-group").remove();
  });

  $(".btn-add-row").click(function () {
    let type = $(this).attr("user_type_button");
    let parent = $(this).parents();
    let html = $(".iuser-section[user_type_section='" + type + "'] .clone-row").html();
    let response = html.replace(/clone/gi, type + "[]");
    $(parent[2]).after(response);
  });

  $("body").on("click", ".btn-remove-row", function () {
    let parent = $(this).parents();
    $(parent[2]).remove();
  });

  $("body").on("blur", "input[user_type_doc]", function () {
    let parent = $(this).parents();
    let un = $(parent[2]).find(".username");
    let val = $(this).val();
    if (val) {
      let type = $(this).attr("user_type_doc");
      let text = $('#userlisttype' + type + ' option[value="' + val +'"]').text();

      if (text) {
        un.val(text);
        un.attr('disabled', 'disabled');
      } else {
        if ($(un).attr('disabled')) {
          un.val('');
          un.removeAttr('disabled');  
        }
      }
    } else {
      if ($(un).attr('disabled')) {
        un.val('');
        un.removeAttr('disabled');  
      }
    }
  });

  //Search
  var searchpaged = 0;
  var element = $('#search');
  element.focus();
  $('#tb').empty();

  element.keyup(function() {
    $('#default-item-list').hide();
    $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    let elementValue = $('#search').val();
    
    if (elementValue) {

      $('.more-search').hide();
      searchpaged = 0;
      let u = '/api/live-search/search/' + elementValue + '/0';

      $.ajax({
        type: 'GET',
        url: u,
        success: function(response) {
          if (response) {
            $('#search-title').removeClass('hide');
            $('#table-header').removeClass('hide');
            $('#tb').empty();
            if (response.data.results.length > 0) {
              response.data.results.map((item) => {
                $('#tb').append('<tr>' +
                '<td><a href="/item/'+ item.id +'">' + item.item_number + '</a></td>' +
                '<td>' + item.created_at + '</td>' +
                '</tr>');
              });
              if (response.data.more.length > 0) {
                $('.more-search').show();
              }
            } else {
              $('#tb').append('<tr>' +
              '<td colspan="100">No se encontraron resultados para: ' + elementValue + '</td>' +
              '</tr>');
            }
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.status);
            if (jqXHR.status = 404) {
              $('#tb').empty();
            }
        },
      });
    } else {
      $('#tb').empty();
      $('#default-item-list').show();
      $('.more-search').hide();
    }
  });

  $(".more-search").click(function() { 
    $('#default-item-list').hide();
    $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    let elementValue = $('#search').val();
    
    if (elementValue) {
      
      searchpaged++;
      let u = '/api/live-search/search/' + elementValue + '/' + searchpaged;
      $.ajax({
        type: 'GET',
        url: u,
        success: function(response) {
          if (response) {
            $('#search-title').removeClass('hide');
            $('#table-header').removeClass('hide');
            response.data.results.map((item) => {
              $('#tb').append('<tr>' +
              '<td><a href="/item/'+ item.id +'">' + item.item_number + '</a></td>' +
              '<td>' + item.created_at + '</td>' +
              '</tr>');
            });

            if (response.data.more.length > 0) {
              $('.more-search').show();
            } else {
              $('.more-search').hide();
            }
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.status);
            if (jqXHR.status = 404) {
            }
        },
      });
    }
  });
  
  // General actions
  $('#userdoctype1').focus();

  //Sarlaf behaviour when loading data

  if ($('#sarlaf-container input[type=checkbox]').prop('checked')) {
    $('#sarlaf-due-date').removeClass('hide');
  }

  //Sarlaf checkbox value and date input showing
  $('#sarlaf-container input[type=checkbox]').click(function() {
    
    let checkboxStatus = $(this).prop('checked');
    
    if (checkboxStatus) {
      $('#sarlaf-due-date').removeClass('hide');
    }
    else{
      $('#sarlaf-due-date').addClass('hide');
      $('#sarlafduedate').val('');
    }
  });

  $("#iuser-search").click(function() {
    var elementValue = $('#search-input').val().trim();
    getIuserSearch(elementValue);
  });


  $("#plate-search").click(function() {
    var elementValue = $('#search-input').val().trim();
    getPlateSearch(elementValue);
  });

  $("body").on("change", "input[type=file]", function () {
    let parent = $(this).parents();
    let un = $(parent[0]);
    un.find(".error-size").remove();
    if (this.files.length && (this.files[0].size > 4000000)) {
      un.append('<p class="error-size">El archivo excede el máximo permitido.</p>');
      this.value = "";
    };
  });

  $('[onsubmit="return validationBs()"] [name="name"]').keyup(function() {
    $('.required-field').remove();
    $('[onsubmit="return validationBs()"] [type="submit"]').removeAttr('disabled');

    let elementValue = $(this).val();
    if (elementValue) {

      $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      let input = $(this);
      let id = $(this).attr('data-id');
      let u = '/api/live-search/search-bs/' + elementValue + '/' + id;

      $.ajax({
        type: 'GET',
        url: u,
        success: function(response) {
          if (response) {
            $('.required-field').remove();
            if (response.data.length > 0) {
              $('[onsubmit="return validationBs()"] [type="submit"]').attr('disabled', 'disabled');
              input.after('<p class="required-field">Este estado ya existe</p>');
            } else {
              $('[onsubmit="return validationBs()"] [type="submit"]').removeAttr('disabled');
            }
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log(jqXHR.status);
        },
      });
    }
  });

  $('[onsubmit="return validationCompany()"] [name="name"]').keyup(function() {
    $('.required-field').remove();
    $('[onsubmit="return validationCompany()"] [type="submit"]').removeAttr('disabled');

    let elementValue = $(this).val();
    if (elementValue) {

      $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      let input = $(this);
      let id = $(this).attr('data-id');
      let u = '/api/live-search/search-company/' + elementValue + '/' + id;

      $.ajax({
        type: 'GET',
        url: u,
        success: function(response) {
          if (response) {
            $('.required-field').remove();
            if (response.data.length > 0) {
              $('[onsubmit="return validationCompany()"] [type="submit"]').attr('disabled', 'disabled');
              input.after('<p class="required-field">Esta compañía ya existe</p>');
            } else {
              $('[onsubmit="return validationCompany()"] [type="submit"]').removeAttr('disabled');
            }
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log(jqXHR.status);
        },
      });
    }
  });

  $('[onsubmit="return validationBranch()"] [name="name"]').keyup(function() {
    $('.required-field').remove();
    $('[onsubmit="return validationBranch()"] [type="submit"]').removeAttr('disabled');

    let elementValue = $(this).val();
    if (elementValue) {

      $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      let input = $(this);
      let id = $(this).attr('data-id');
      let u = '/api/live-search/search-branch/' + elementValue + '/' + id;

      $.ajax({
        type: 'GET',
        url: u,
        success: function(response) {
          if (response) {
            $('.required-field').remove();
            if (response.data.length > 0) {
              $('[onsubmit="return validationBranch()"] [type="submit"]').attr('disabled', 'disabled');
              input.after('<p class="required-field">Este ramo ya existe</p>');
            } else {
              $('[onsubmit="return validationBranch()"] [type="submit"]').removeAttr('disabled');
            }
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log(jqXHR.status);
        },
      });
    }
  });

  var arrayResponsible = Array(true, true);

  $('[onsubmit="return validationResponsible()"] [name="name"]').keyup(function() {
    searchResponsible(this);
  });

  $('[onsubmit="return validationResponsible()"] [name="email"]').keyup(function() {
    searchResponsible(this);
  });

  function searchResponsible(element) {
    $(element).siblings('.required-field').remove();

    if (arrayResponsible[0] && arrayResponsible[1]) {
      $('[onsubmit="return validationResponsible()"] [type="submit"]').removeAttr('disabled');
    }
    
    let elementValue = $(element).val();
    let name = $(element).attr('name');
    if (elementValue) {

      $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      let input = $(element);
      let id = input.attr('data-id');
      let u = '/api/live-search/search-responsible/' + name + '/' + elementValue + '/' + id;

      $.ajax({
        type: 'GET',
        url: u,
        success: function(response) {
          if (response) {
            $(element).siblings('.required-field').remove();
            if (response.data.length > 0) {
              $('[onsubmit="return validationResponsible()"] [type="submit"]').attr('disabled', 'disabled');
              if (name == 'name') {
                input.after('<p class="required-field">Este responsable ya existe</p>');
                arrayResponsible[0] = false;
              } else {
                input.after('<p class="required-field">Este correo ya esta vinculado con un responsable</p>');
                arrayResponsible[1] = false;
              }
            } else {
              if (name == 'name') {
                arrayResponsible[0] = true;
              } else {
                arrayResponsible[1] = true;
              }

              if (arrayResponsible[0] && arrayResponsible[1]) {
                $('[onsubmit="return validationResponsible()"] [type="submit"]').removeAttr('disabled');
              }
            }
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log(jqXHR.status);
        },
      });
    } else {
      if (name == 'name') {
        arrayResponsible[0] = true;
      } else {
        arrayResponsible[1] = true;
      }

      if (arrayResponsible[0] && arrayResponsible[1]) {
        $('[onsubmit="return validationResponsible()"] [type="submit"]').removeAttr('disabled');
      }
    }
  }

  $('[onsubmit="return validationIusertype()"] [name="name"]').keyup(function() {
    $('.required-field').remove();
    $('[onsubmit="return validationIusertype()"] [type="submit"]').removeAttr('disabled');

    let elementValue = $(this).val();
    if (elementValue) {

      $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      let input = $(this);
      let id = $(this).attr('data-id');
      let u = '/api/live-search/search-iusertype/' + elementValue + '/' + id;

      $.ajax({
        type: 'GET',
        url: u,
        success: function(response) {
          if (response) {
            $('.required-field').remove();
            if (response.data.length > 0) {
              $('[onsubmit="return validationIusertype()"] [type="submit"]').attr('disabled', 'disabled');
              input.after('<p class="required-field">Este ramo ya existe</p>');
            } else {
              $('[onsubmit="return validationIusertype()"] [type="submit"]').removeAttr('disabled');
            }
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log(jqXHR.status);
        },
      });
    }
  });

  $('[onsubmit="return validationIuserDetail()"] [name="document"]').keyup(function() {
    $('.required-field').remove();
    $('[onsubmit="return validationIuserDetail()"] [type="submit"]').removeAttr('disabled');

    let elementValue = $(this).val();
    if (elementValue) {

      $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });

      let input = $(this);
      let id = $(this).attr('data-id');
      let u = '/api/live-search/search-iuser/' + elementValue + '/' + id;

      $.ajax({
        type: 'GET',
        url: u,
        success: function(response) {
          if (response) {
            $('.required-field').remove();
            if (response.data.length > 0) {
              $('[onsubmit="return validationIuserDetail()"] [type="submit"]').attr('disabled', 'disabled');
              input.after('<p class="required-field">Este ramo ya existe</p>');
            } else {
              $('[onsubmit="return validationIuserDetail()"] [type="submit"]').removeAttr('disabled');
            }
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log(jqXHR.status);
        },
      });
    }
  });

  $("body").on("change", "input[name*='checkboxusertype']", function () {
    let parent = $(this).parents();
    let userdoc = $(parent[1]).find("input[name*='userdoctype']");
    let username = $(parent[1]).find("input[name*='usernametype']");
    let userdochidden = $(parent[1]).find("input[name*='userdochiddentype']");
    let usernamehidden = $(parent[1]).find("input[name*='usernamehiddentype']");

    if ($(this).is(':checked')){
      userdoc.attr('type', 'hidden');
      userdochidden.attr('type', 'text');

      username.attr('type', 'hidden');
      usernamehidden.attr('type', 'text');

      let userdocdata = $("[user_section_key='0'] input[name*='userdoctype']");
      let userdocvalue = $(userdocdata[0]).val();
      userdoc.val(userdocvalue);
      userdochidden.val(userdocvalue);

      let usernamedata = $("[user_section_key='0'] input[name*='usernametype']");
      let usernamevalue = $(usernamedata[0]).val();
      username.val(usernamevalue);
      usernamehidden.val(usernamevalue);
    } else {

      userdochidden.attr('type', 'hidden');
      userdoc.attr('type', 'text');

      usernamehidden.attr('type', 'hidden');
      username.attr('type', 'text');

      userdoc.val('');
      userdochidden.val('');

      username.val('');
      usernamehidden.val('');
    }
  });

  $("body").on("blur", "[user_section_key='0'] input[user_data]", function () {
    let userdocdata = $("[user_section_key='0'] input[name*='userdoctype']");
    let userdocvalue = $(userdocdata[0]).val();

    let usernamedata = $("[user_section_key='0'] input[name*='usernametype']");
    let usernamevalue = $(usernamedata[0]).val();

    let checkboxusers = $("input[name*='checkboxusertype']:checked");

    for (let i = 0; i < checkboxusers.length; i++) {
      let parent = $(checkboxusers[i]).parents();

      $(parent[1]).find("input[name*='userdoctype']").val(userdocvalue);
      $(parent[1]).find("input[name*='userdochiddentype']").val(userdocvalue);
      $(parent[1]).find("input[name*='usernametype']").val(usernamevalue);
      $(parent[1]).find("input[name*='usernamehiddentype']").val(usernamevalue);
    }
  });

}); //Ends: Document Ready

function getItemNumber() {
  let noItem = jQuery('#noitem').val();

  if (noItem) {
    
    let u = '/api/apiitem/' + noItem;
    let e = jQuery('#item-number-error');
    
    jQuery.ajax({
      type: 'GET',
      url: u,
      success: function(data) {
        e.append('Número de Póliza ya existe.');
      },
      error: function(jqXHR, textStatus, errorThrown) {
          console.log(jqXHR.status);
          if (jqXHR.status = 404) {
            e.empty();
          }
      },
    });
  }
}

function validationCreationPolicy() {
  let error = true;
  jQuery('.required-field').remove();
  jQuery('[type="submit"]').after('<div class="loading-blue"></div>');

  // Document validation and user name
  let typesString = jQuery('#isuerTypesEncode').html()
  if (typesString && typesString != '') {

    let types = JSON.parse(typesString);
    types.map((type) => {
      let docs = new Array();
      let usersDoc = jQuery('input[name="userdoctype' + type.id + '[]"]');
      let usersName = jQuery('input[name="usernametype' + type.id + '[]"]');
      
      if (usersDoc.length > 0) {
        for (var i = 0; i < usersDoc.length; i++) {

          if (usersDoc[i]) {
            docValue = jQuery(usersDoc[i]).val();

            if (!docValue) {
              error = false;
              jQuery(usersDoc[i]).after('<p class="required-field">Campo requerido</p>');

            } else if (docs.indexOf(docValue) >= 0) {
              error = false;
              jQuery(usersDoc[i]).after('<p class="required-field">Esta duplicado este usuario como ' + type.name.toLowerCase() + '</p>');

            } else {
              docs[i] = docValue;
            }
          }

          if (usersName[i] && ( !(jQuery(usersName[i]).val()) ) ) {
            error = false;
            jQuery(usersName[i]).after('<p class="required-field">Campo requerido</p>');
          }
        }
      }
    });
  }

  // Validation of policy number
  let numPolicy = jQuery('input[name="noitem"]');
  if (!(numPolicy.val())) {
    error = false;
    numPolicy.after('<p class="required-field">Campo requerido</p>');
  }

  // Validation of policy expiration date
  let datePolicy = jQuery('input[name="duedate"]');
  if (!(datePolicy.val())) {
    error = false;
    datePolicy.after('<p class="required-field">Campo requerido</p>');
  }

  // Validation of the expiration date of the sarlaft
  let sarlaf = jQuery('input[name="sarlaf"]')
  if (sarlaf.length && sarlaf[0].checked) {
    dateSarlaf = jQuery('input[name="sarlafduedate"]');
    if (!(dateSarlaf.val())) {
      error = false;
      dateSarlaf.after('<p class="required-field">Campo requerido</p>');
    }
  }

  if (!(error)) {
    jQuery('.loading-blue').remove();
  }

  return error;
}


function validationEditPolicy() {
  let error = true;
  jQuery('.required-field').remove();
  jQuery('[type="submit"]').after('<div class="loading-blue"></div>');

  // Validation of policy number
  let numPolicy = jQuery('input[name="item_number"]');
  if (!(numPolicy.val())) {
    error = false;
    numPolicy.after('<p class="required-field">Campo requerido</p>');
  }

  // Validation of policy expiration date
  let datePolicy = jQuery('input[name="due_date"]');
  if (!(datePolicy.val())) {
    error = false;
    datePolicy.after('<p class="required-field">Campo requerido</p>');
  }

  // Validation of the expiration date of the sarlaft
  let sarlaf = jQuery('input[name="sarlaf"]')
  if (sarlaf.length && sarlaf[0].checked) {
    dateSarlaf = jQuery('input[name="sarlaf_duedate"]');
    if (!(dateSarlaf.val())) {
      error = false;
      dateSarlaf.after('<p class="required-field">Campo requerido</p>');
    }
  }

  if (!(error)) {
    jQuery('.loading-blue').remove();
  }

  return error;
}

function validationCompany() {
  let error = true;
  jQuery('.required-field').remove();
  jQuery('[type="submit"]').after('<div class="loading-blue"></div>');

  let name = jQuery('input[name="name"]');
  if (!(name.val())) {
    error = false;
    name.after('<p class="required-field">Campo requerido</p>');
  }

  if (!(error)) {
    jQuery('.loading-blue').remove();
  }

  return error;
}

function validationBs() {
  let error = true;
  jQuery('.required-field').remove();
  jQuery('[type="submit"]').after('<div class="loading-blue"></div>');

  let name = jQuery('input[name="name"]');
  if (!(name.val())) {
    error = false;
    name.after('<p class="required-field">Campo requerido</p>');
  }

  if (!(error)) {
    jQuery('.loading-blue').remove();
  }

  return error;
}

function validationBranch() {
  let error = true;
  jQuery('.required-field').remove();
  jQuery('[type="submit"]').after('<div class="loading-blue"></div>');

  let name = jQuery('input[name="name"]');
  if (!(name.val())) {
    error = false;
    name.after('<p class="required-field">Campo requerido</p>');
  }

  if (!(error)) {
    jQuery('.loading-blue').remove();
  }

  return error;
}

function validationResponsible() {
  let error = true;
  jQuery('.required-field').remove();
  jQuery('[type="submit"]').after('<div class="loading-blue"></div>');

  let name = jQuery('input[name="name"]');
  if (!(name.val())) {
    error = false;
    name.after('<p class="required-field">Campo requerido</p>');
  }
  
  let email = jQuery('input[name="email"]');
  if (!(email.val())) {
    error = false;
    email.after('<p class="required-field">Campo requerido</p>');

  } else {
    var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

    if (!(regex.test($('input[name="email"]').val().trim()))) {
      error = false;
      email.after('<p class="required-field">Dirección de correo electrónico no válida</p>');
    }
  }

  if (!(error)) {
    jQuery('.loading-blue').remove();
  }

  return error;
}

function validationIusertype() {
  let error = true;
  jQuery('.required-field').remove();
  jQuery('[type="submit"]').after('<div class="loading-blue"></div>');

  let name = jQuery('input[name="name"]');
  if (!(name.val())) {
    error = false;
    name.after('<p class="required-field">Campo requerido</p>');
  }

  if (!(error)) {
    jQuery('.loading-blue').remove();
  }

  return error;
}

function validationIuserDetail() {
  let error = true;
  jQuery('.required-field').remove();
  jQuery('[type="submit"]').after('<div class="loading-blue"></div>');

  let name = jQuery('input[name="name"]');
  if (!(name.val())) {
    error = false;
    name.after('<p class="required-field">Campo requerido</p>');
  }

  let document = jQuery('input[name="document"]');
  if (!(document.val())) {
    error = false;
    document.after('<p class="required-field">Campo requerido</p>');
  }

  if (!(error)) {
    jQuery('.loading-blue').remove();
  }

  return error;
}