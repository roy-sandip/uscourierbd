/**
* Custom Javascript Helpers
*/
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

//Select2 Toast
 const Toast = Swal.mixin({
      toast: true,
      position: "bottom-end",
      showConfirmButton: false,
      showCloseButton: true,
      timer: 5000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
      }
});




/**Fill Value **/
function fillVal(selector, val){
	var dom = $(selector);
	$(dom).val(val);
	if($(dom).hasClass('tagEditor')){
		$(dom).tagEditor('addTag', val);
	}
}

/** Find Dimensional Weighht in KG**/
function dWeight(width, length, height)
{
	var weight = 0.00;
	var w = Number(width);
	var l = Number(length);
	var h = Number(height);

	if(w > 0 && l > 0 && h > 0) {
		weight = (w*l*h)/5000;
	}
	return weight.toFixed(2);
}


/**
* Generate Error HTML
*/
function errorText(text)
{
	return '<span class="invalid-feedback" role="alert"><strong>'+text+'</strong></span>';
}









/** Submit form with AJAX Request **/
function submitForm(formSelector)
{
	 $(formSelector).on('submit', function(event){
        event.preventDefault();
        var formdata = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            dataType: 'json',
            processData: false,
            contentType: false,
            data: formdata,
            success: function(response){
                console.log('Success: '+ response);
            },  
            error: function(response){
                if(response.status === 422) {
                    var errors = response.responseJSON;
                    $(formSelector).find('.is-invalid').removeClass('is-invalid');
                    $.each(errors.errors, function (key, value) {
                        var splitted = key.split('.');
                        var attrName = splitted[0];
                        for(i = 1; i <= splitted.length - 1;i++)
                        {
                            attrName += '['+splitted[i]+']';
                        }
                        var inputDom = $("*[name^='"+attrName+"']");
                        var parentDom = $(inputDom).parent();

                        $(parentDom).addClass('is-invalid');
                        var errDom = $(parentDom).find('.invalid-feedback strong');
                        if(errDom.length){
                            errDom.html(value);
                        }else{
                            parentDom.append(errorText(value));
                        }
                        
                    });
                }
            }
        });
    });
}





/**
* Global Redirect Function
*/
function redirect(url)
{
    window.location.href = url;
}

function Response(data){
                //on error
                if(data.error != null){
                    swal({
                        title: "Failed!",
                        html: '<span style="text-align:left">'+data.error+'</span>',
                        type: "error"
                    });
                }
                //On success
                if(data.success == true && data.error == null){
                    Toast.fire({
                        type: 'success',
                        title:'Data updated'
                    });
                }
}


var FormatDate = function (input){
    var d = new Date(Date.parse(input.replace(/-/g, "/")));
    var month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var date = d.getDate() + " " + month[d.getMonth()] + ", " + d.getFullYear();
  //  var time = d.toLocaleTimeString().toLowerCase().replace(/([\d]+:[\d]+):[\d]+(\s\w+)/g, "$1$2");
    return (date);  
};

/**
* Laravel Global Error handler
*/
function laravelError(response, form)
{
    var error = response.responseJSON;
    var errorList = error.errors;
    
    var text = '';
    var message = '';
    
    if(typeof(error.message) != 'undefined'){
        if(error.message.length < 50){
            message += error.message;
        }else{
            text += error.message+'<br>';
            message += 'Error!';
        }
    }

    if(typeof(error.error) == 'string')
    {
        message += error.error;
    }

if(errorList){
   for (var key of Object.keys(errorList)) {

        var err = errorList[key].join("<br>");
        text += '<li>'+ err +'</li>';
        var input = $(form).find('input[name="'+key+'"]').closest('.field');
        $(input).addClass('error');
          
    }
}

   
    Swal.fire({
          title: message,
          html: '<ul style="text-align:left;color:red">'+text+'</ul>',
          icon: "error",
          //allowOutsideClick: false,
          showConfirmButton: false,
          showCancelButton: true,
          cancelButtonText: 'Close'
        });

}

/**
 * Laravel Handle Success Notices
 */
function laravelSuccess(response, type = 'swal')
{
    if(response.redirect == true)
    {
        redirect(response.redirect_url);
    }
    if(type == 'toast')
    {
        notify('Success', response.message);
        return;
    }
    
   Swal.fire({
                    title: 'Success!',
                    html: '<ul style="text-align:left;color:green">'+response.message+'</ul>',
                    icon: "success",
                    //allowOutsideClick: false,
                    showConfirmButton: false,
                    showCancelButton: true,
                    cancelButtonText: 'Close'
                });
        
}





/**
* Global Ajax Function Helper
*/
function makeAjax(form, beforeSend, complete, success, error)
{
                  
        $.ajax({
            url: $(form).attr('action'),
            type: $(form).attr('method'),
            dataType: 'json',
            contentType: false,
            processData: false,
            data: new FormData(form),            
            beforeSend: function()
            {
                if(typeof(beforeSend) != 'undefined' && beforeSend != null){
                    beforeSend();
                }
            },
            complete: function()
            {
                if(typeof(complete) != 'undefined' && complete != null){
                    complete();
                }
                
            },
            success: function(response)
            {
                if(typeof(success) != 'undefined' && success != null){
                    success(response);
                }
                
            },
            error: function(response)
            {
                if(typeof(error) != 'undefined' && error != null){
                    error(response);
                }
            }

        });
    
}

/**
* TableEdit Plugin Buttons style
*/
function tableEditButtons()
{
    return  {
                    edit: {
                        class: 'btn btn-sm btn-secondary ',
                        html: '<i class="fa fa-pencil"></i>',
                        action: 'edit'
                    },
                    delete: {
                        class: 'btn btn-sm btn-danger ',
                        html: '<i class="fa fa-trash-o"></i>',
                        action: 'delete'
                    },
                    save: {
                        class: 'btn btn-sm btn-success ',
                        html: '<i class="fa fa-check"></i>'
                    },
                    restore: {
                        class: 'btn btn-sm btn-success ',
                        html: 'Restore',
                        action: 'restore'
                    },
                    confirm: {
                        class: 'btn btn-sm btn-warning',
                        html: 'Sure?'
                    }
                };
}



/**
* Generate payment status label
*/
function status(status)
{
    var color = 'yellow';
    switch(status)
    {
        case 'DUE': color = 'red';break;
        case 'PAID': color = 'green';break;
        case 'PENDING': color = 'yellow';break;
    }
    return '<span class="ui '+color+' label">'+status+'</span>';
}

/**
* Retrun all status list
*/
function statusList(){
    var select = "<select>";
                  select += '<option value="">All</option>';
                  select += '<option value="PENDING">PENDING</option>';
                  select += '<option value="DUE">DUE</option>';
                  select += '<option value="PAID">PAID</option>';
                  select += "</select>";
                  return select;
  };


 

  /**
   * Make Table mobile friendly
  */
 function responsiveTable()
 {
     
    
    css = "<style>";
    css +="@media only screen and (max-width: 760px), (min-device-width: 768px) and (max-device-width: 1024px)  {";
      $("table.responsive ").each(function(element){
        var randClass = 'class_'+ Math.random().toString().substr(2, 8); 
        $(this).addClass(randClass);
        $(this).find('thead tr th').each(function(el){
            var text = $(this).text();
            var key = el+1;
            css += '.'+randClass+' td:nth-of-type('+key+'):before{content:"'+text+'";}';
            css += '.'+randClass+' tbody tr td {min-heignt:40px;}';
        });
    });

    css += "}</style>";
    $('head').append(css);
 }

// Toast.fire({
//     icon : 'success',
//     title: 'loaded'
// });
    