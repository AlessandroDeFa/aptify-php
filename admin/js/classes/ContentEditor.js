import { MediaManager } from "./MediaManager.js";
export class ContentEditor {
  richTextInstances = {};
  mediaManagers = {};
  #name = $('#name');
  #submitBtn = $('.save-content');
  constructor(content) {
    this.content = content;
    this.Init();
  }

  Init() {
    this.#submitBtn.on('click', () => this.Submit());
    this.content.fields.forEach(field => {
      this.SetupField(field);
    })
  }

  SetupField(field) {
    const { id, field_type } = field;

    if (field_type === 'media') {
      this.InitMediaField(field);
    } else if (field_type === 'richtext') {
      this.InitRichTextField(field);
    }
  }

  InitMediaField(field) {
    const element = $(`.drop-media[data-id='${field.id}']`);
    if (element.length) {
      this.mediaManagers[field.id] = new MediaManager(element);
      if (field.value) {
        this.mediaManagers[field.id].setInitialMedia(field.value);
      }
    }
  }

  InitRichTextField(field) {
    const containerId = `editor-container-${field.id}`;
    const quill = new Quill(`#${containerId}`, {
      theme: 'snow',
      modules: {
        toolbar: [
          [{ header: [1, 2, false] }],
          ['bold', 'italic', 'underline'],
          [{ color: [] }, { background: [] }],
          ['link', 'image'],
          [{ list: 'ordered' }, { list: 'bullet' }],
          ['clean']
        ]
      }
    });

    const initialContent = field.value ?? '';
    quill.clipboard.dangerouslyPasteHTML(initialContent);
    const length = quill.getLength();
    quill.setSelection(length, length);
    this.richTextInstances[field.id] = quill;
  }

  Submit() {
    if (!this.#name || this.#name.val().trim() === '') {
      showAlert('error', 'Il campo Nome non è valido.');
      return
    }
    this.#submitBtn.addClass('loading')
    const fields = this.#GetFieldValues();
    const updatedContent = { ...this.content, name: this.#name.val(), fields };
    $.ajax({
        url : '/api/contents/update.php',
        type : 'POST',
        data: {
          content: updatedContent
        },
        success : ({status, message, error_code}) => {
          this.#submitBtn.removeClass('loading');
          showAlert('success', message);
        },
        error : (request,error)=>{
          const response = request.responseJSON
          this.#submitBtn.removeClass('loading');
          showAlert('error', response.message)
        }
    });
  }

  #GetFieldValues() {
    return this.content.fields.map(field => {
      const { id, field_type } = field;

      const value = this.#getFieldValueByType(id, field_type);
      return { ...field, value };
    });
  }

  #getFieldValueByType(id, fieldType) {
    switch (fieldType) {
      case 'text':
        return $(`#text-${id}`).val();
      case 'boolean':
        return $(`#switch-${id}`).is(':checked') ? '1' : '0';
      case 'numero':
        return $(`#numero-${id}`).val();
      case 'richtext':
        return this.richTextInstances[id] ? this.richTextInstances[id].root.innerHTML : null;
      case 'media':
        return this.mediaManagers[id] ? this.mediaManagers[id].media : null;
      case 'data':
        return $(`#data-${id}`).val();
      default:
        console.warn(`Tipo di campo non gestito: ${fieldType}`);
        return null;
    }
  }
}
