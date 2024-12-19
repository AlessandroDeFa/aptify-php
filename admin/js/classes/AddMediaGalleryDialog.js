import { MediaManager } from "./MediaManager.js";
export class AddMediaGalleryDialog {
  #dialog = $('.dialog');
  #dropMediaEl = this.#dialog.find('.drop-media');
  #mediaManager = null;
  #submitBtn = this.#dialog.find('.submit');
  #dismissBtn = this.#dialog.find('.dismiss');
  #selectedPosition = this.#dialog.find('select#order');
  constructor(){
    $('.overlay').show();
    this.#dialog.addClass('active');
    this.InitListener();
    this.#dismissBtn.on('click', this.Dismiss.bind(this));
    this.#submitBtn.on('click', this.Submit.bind(this));
  }

  InitListener(){
    this.#mediaManager = new MediaManager(this.#dropMediaEl);
  }

  DeactivateListener(){
    this.#submitBtn.off('click');
    this.#dismissBtn.off('click');
    this.#mediaManager.deactivateListener();
  }

  Submit(){
    if (!this.#mediaManager.media){
      showAlert("error", "Non hai inserito nessuna immagine.");
      return
    }

    this.#submitBtn.addClass('loading');
    $.ajax({
        url : '/api/gallery/add.php',
        type : 'POST',
        data: {
          media: this.#mediaManager.media,
          order: this.#selectedPosition.val()
        },
        success : ({status, message, error_code}) => {
          this.#submitBtn.removeClass('loading');
          showAlert('success', message);
          this.Dismiss(()=>{location.reload();});
        },
        error : (request,error)=>{
          const response = request.responseJSON
          this.#submitBtn.removeClass('loading');
          showAlert('error', response.message)
        }
    });
  }

  Dismiss(cb){
    this.DeactivateListener();
    this.#dialog.removeClass('active');
    $('.overlay').fadeOut(300, () => {
      $('.overlay').hide();
      this.#mediaManager.removeMedia();
      this.#selectedPosition.find('option').last().prop('selected', true);
      if (typeof cb === "function") cb();
    });
  }
}
