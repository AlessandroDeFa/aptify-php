export class ConfirmationDialog {
  #confirmationDialog = $('.confirmation-dialog');
  #submitBtn = this.#confirmationDialog.find('.submit');
  #dismissBtn = this.#confirmationDialog.find('.dismiss');
  #title = this.#confirmationDialog.find('h2');
  #message = this.#confirmationDialog.find('p');
  constructor({ title = 'Sei sicuro?', message = 'Questa operazione non può essere annullata.', onConfirm = ()=>{} } = {}){
    this.#title.text(title);
    this.#message.text(message);
    this.onConfirm = onConfirm;
    $('.overlay').show();
    this.#confirmationDialog.addClass('active');
    this.InitListener();
  }

  InitListener(){
    this.#submitBtn.on('click', this.Submit.bind(this));
    this.#dismissBtn.on('click', this.Dismiss.bind(this));
  }

  Submit(){
    this.onConfirm((cb) => { this.Dismiss(cb) });
  }

  Dismiss(cb){
    this.DeactivateListener();
    this.#confirmationDialog.removeClass('active');
    $('.overlay').fadeOut(300, () => {
      $('.overlay').hide();
      if (typeof cb === "function") cb();
    });
  }

  DeactivateListener(){
    this.#submitBtn.off('click');
    this.#dismissBtn.off('click');
  }
}
