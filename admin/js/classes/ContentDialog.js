export class ContentDialog{
  name;
  selectedFields = [];
  #dialog = $('.dialog');
  #summary = $(".summary-content");
  #options = $(".field-options");
  #nameInput = this.#summary.find('#name');
  #fieldNameInput = this.#options.find('#field-name');
  #selectedFieldsContainer = this.#summary.find('.selected-fields');
  #submitBtn = this.#dialog.find('.submit');
  #addFieldBtn = $(".add-field");
  #backBtn = this.#dialog.find(".back");
  #fieldOption = this.#dialog.find('.fields .field-block');
  #dismissBtn = this.#dialog.find('.dismiss');
  constructor(){
    $('.overlay').show();
    this.#dialog.addClass('active');
    this.InitListener();
  }

  InitListener(){
    this.#addFieldBtn.on('click', this.#toggleView.bind(this))
    this.#dismissBtn.on('click', this.Dismiss.bind(this));
    this.#submitBtn.on('click', this.Submit.bind(this));
  }

  AddField(e){
    const name = this.#fieldNameInput.val();
    if (name.trim() === ''){
      showAlert("error", "Il nome del campo è obbligatorio");
      return
    }
    const field = $(e.target).closest('.field-block');
    const fieldId = field.data('id');
    const selectedField = {
      id: fieldId,
      name: name
    }
    this.selectedFields.push(selectedField);
    this.#selectedFieldsContainer.find('h5').hide();
    const newField = field.clone();
    const fieldType = newField.find('.field-info h4').text();
    newField.find('.field-info h4').text(`${name} - ${fieldType}`)
    this.#selectedFieldsContainer.append(newField);
    this.Back();
  }

  Submit(){
    this.name = this.#nameInput.val();

    if (this.name.trim() === ''){
      showAlert("error", "Il campo Nome è obbligatorio");
      return
    }

    this.#submitBtn.addClass('loading');
    $.ajax({
        url : '/api/contents/create.php',
        type : 'POST',
        data: {
          name: this.name,
          selectedFields: this.selectedFields
        },
        success : ({status, message, error_code}) => {
          this.#submitBtn.removeClass('loading');
          showAlert('success', message);
          this.Dismiss();
          location.reload();
        },
        error : (request,error)=>{
          const response = request.responseJSON
          this.#submitBtn.removeClass('loading');
          showAlert('error', response.message)
        }
    });
  }

  DeactivateListener(){
    this.#addFieldBtn.off('click');
    this.#fieldOption.off('click');
    this.#submitBtn.off('click');
    this.#dismissBtn.off('click');
    this.#backBtn.off('click');
  }

  Dismiss(){
    this.DeactivateListener();
    this.#dialog.removeClass('active');
    $('.overlay').fadeOut(300, () => {
      $('.overlay').hide();
      this.#nameInput.val('');
      this.selectedFields = [];
      this.#fieldNameInput.val('');
      this.#selectedFieldsContainer.find('.field-block').remove();
      this.#selectedFieldsContainer.find('h5').show();
      this.#summary.show();
      this.#options.hide();
      this.#backBtn.hide();
    });
  }

  Back(){
    this.#options.hide();
    this.#summary.show();
    this.#backBtn.hide();
    this.#fieldNameInput.val('');
  }

  #toggleView(){
    this.#summary.hide();
    this.#options.show();
    this.#backBtn.show();
    this.#fieldOption.off('click').on('click', this.AddField.bind(this));
    this.#backBtn.off('click').on('click', this.Back.bind(this));
  }
}
