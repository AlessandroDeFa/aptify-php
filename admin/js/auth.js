const validateFields = (username, password, confirmPassword) => {
  if(!username || !password){
    showAlert('error', 'Tutti i campi sono obbligatori');
    return false;
  }
  if (confirmPassword !== undefined && password !== confirmPassword) {
    showAlert("error", "Le password non coincidono.");
    return false;
  }
  return true;
};

const register = (username, password, confirmPassword, { onLoading, onCompletion })=>{
  if (typeof onLoading === "function") onLoading();
  $.ajax({
      url : '/api/auth/register.php',
      type : 'POST',
      data: {username,password, confirmPassword},
      dataType:'json',
      success : function({status, message}) {
        if (typeof onCompletion === "function") {
          onCompletion({ status, message });
        }
      },
      error : function(request,error){
        if (typeof onCompletion === "function") {
          onCompletion({ status: false, message: 'Si è verificato un errore. Riprova più tardi.' });
        }
      }
  });
}

const login = (username, password, { onLoading, onCompletion })=>{
  if (typeof onLoading === "function") onLoading();
  $.ajax({
      url : '/api/auth/login.php',
      type : 'POST',
      data: {username,password},
      dataType:'json',
      success : function({status, message}) {
        if (typeof onCompletion === "function") {
          onCompletion({ status, message });
        }
      },
      error : function(request,error){
        if (typeof onCompletion === "function") {
          onCompletion({ status: false, message: 'Si è verificato un errore. Riprova più tardi.' });
        }
      }
  });
}

const logout = ({ onLoading, onCompletion })=>{
   if (typeof onLoading === "function") onLoading();
   $.ajax({
       url : '/api/auth/logout.php',
       type : 'POST',
       success : function({status, message}) {
         if (typeof onCompletion === "function") {
           onCompletion({ status, message });
         }
       },
       error : function(request,error){
         if (typeof onCompletion === "function") {
           onCompletion({ status: false, message: 'Si è verificato un errore. Riprova più tardi.' });
         }
       }
   });
}

$(".submit-login").on('click', (e) => {
    const username = $("#username").val();
    const password = $("#password").val();
    const button = $(e.target);

    if (!validateFields(username, password)) {
        return;
    }

    login(username, password,{
      onLoading: ()=>{
        button.addClass("loading");
      },
      onCompletion: ({status, message})=>{
        button.removeClass("loading");
        if(!status){
          showAlert('error', message);
          return
        }
        showAlert('success', message);
        redirectTo("/contents", 400);
      }
    });
});

$(".submit-register").on('click', (e) => {
  const username = $("#username").val();
  const password = $("#password").val();
  const confirmPassword = $("#confirm-password").val();
  const button = $(e.target);

  if (!validateFields(username, password, confirmPassword)) {
      return;
  }

  register(username, password, confirmPassword,{
    onLoading: ()=>{
      button.addClass("loading");
    },
    onCompletion: ({status, message})=>{
      button.removeClass("loading");
      if(!status){
        showAlert('error', message);
        return
      }
      showAlert('success', message);
      redirectTo("/contents", 400);
    }
  });
});

$('.log-out').on('click', (e) => {
  logout({
    onCompletion: ({status, message})=>{
      if(!status){
        showAlert('error', message, 'top right');
        return
      }
      redirectTo("/auth/login");
    }
  })
});


$(".toggle-password-visibility").on('click', function() {
  const passwordField = $(this).prev('input');
  const isPasswordVisible = $(this).data("state") === "show";
  passwordField.attr("type", isPasswordVisible ? "password" : "text");
  $(this).data("state", isPasswordVisible ? "hide" : "show");
  $(this).find("svg.show").toggle(isPasswordVisible);
  $(this).find("svg.hide").toggle(!isPasswordVisible);
});
