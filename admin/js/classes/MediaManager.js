export class MediaManager {
  constructor(element){
    this.element = $(element);
    this.previewContainer = this.element.siblings(".uploaded-media-container");
    this.fileInput = this.element.find("input[type='file']");
    this.uploadedMedia = null;
    this.initListener();
  }

  get media() {
    return this.uploadedMedia;
  }

  initListener(){
    this.element.on("dragover",false).on("drop",(e)=>{
      e.preventDefault();
      const file = e.originalEvent.dataTransfer.files[0];
      if (file){
        this.uploadMedia(file);
      }
      return false;
    });
    this.fileInput.on('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        this.uploadMedia(file);
      }
    });
    this.previewContainer.on("click",".remove-media",()=>{this.removeMedia()});
  }

  deactivateListener(){
    this.element.off('click');
    this.fileInput.off('change');
    this.previewContainer.off('click');
  }

  uploadMedia(file){
    this.#readFile(file, (src)=>{
      this.removeMedia();
      const mediaPreview = this.#createMediaPreview(src, file.name, file.size);
      this.previewContainer.append(mediaPreview);
      let [metadata, data]=src.split(";base64,");
      this.uploadedMedia = { metadata, data };
    });
  }

  removeMedia(){
    this.previewContainer.html("");
    this.uploadedMedia = null;
  }

  setInitialMedia(value){
    this.uploadedMedia = { path: value };
  }

  #getFormattedFileSize(size){
    const fileSizeInKB = size / 1024;
    let fileSizeDisplay;
    if (fileSizeInKB < 1024) {
      fileSizeDisplay = `${fileSizeInKB.toFixed(2)} KB`;
    } else {
      const fileSizeInMB = fileSizeInKB / 1024;
      fileSizeDisplay = `${fileSizeInMB.toFixed(2)} MB`;
    }
    return fileSizeDisplay;
  }

  #readFile(file, cb){
    const reader=new FileReader();
    reader.onload=(e)=>{
      const src = e.target.result;
      if(cb!==undefined){
        cb(src);
      }
    };
    reader.readAsDataURL(file);
  };

  #createMediaPreview(src, fileName, size){
    return `<div class="preview-media">
    <div>
      <img src="${src}" alt="image" />
      <div>
       <div class="file-info"><span class="file-name">${fileName}</span><span class="file-size">${this.#getFormattedFileSize(size)}</span></div>
       <div class="remove-media">
           <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
       </div>
      </div>
    </div>
    </div>`;
  }
}
