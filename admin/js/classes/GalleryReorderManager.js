export class GalleryReorderManager {
  constructor(container){
    this.container = $(container).get(0);
    this.reorderButton = $('.reorder-gallery-button');
    this.submitBtn = $('.submit-gallery-order');
    this.initialSlotItemMap = null;
    this.finalSlotItemMap = null;
    this.swapy = null;
    if (this.container) {
      this.InitializeSwapy();
      this.InitListener();
    }
  }

  InitializeSwapy() {
    this.swapy = Swapy.createSwapy(this.container, {
      swapMode: 'hover',
      dragAxis: 'y',
      autoScrollOnDrag: true,
      enabled: false
    });

    this.swapy.onSwapEnd((event) => {
      this.finalSlotItemMap = this.swapy.slotItemMap().asObject;
    });
  }

  InitListener(){
    this.reorderButton.on('click', () => {
      this.SetDragMode(true);
      this.initialSlotItemMap = this.swapy.slotItemMap().asObject;
      this.submitBtn.on('click', this.SaveOrder.bind(this));
    });
  }

  SaveOrder() {
    const newOrderMap = this.#GetChangedItems(this.initialSlotItemMap, this.finalSlotItemMap);
    if (Object.keys(newOrderMap).length === 0) {
      this.SetDragMode(false)
      this.submitBtn.off('click');
      return;
    }
    this.submitBtn.addClass('loading');
    $.ajax({
        url : '/api/gallery/reorder.php',
        type : 'POST',
        data: {
          newOrder: newOrderMap
        },
        success : ({status, message, error_code}) => {
          this.submitBtn.removeClass('loading');
          if (!status){
            showAlert('error', message);
            return
          }
          showAlert('success', message);
          this.SetDragMode(false)
          this.submitBtn.off('click');
          this.#UpdateGalleryOrder(newOrderMap);
        },
        error : (request,error)=>{
          const response = request.responseJSON
          this.submitBtn.removeClass('loading');
          showAlert('error', response.message)
        }
    });
  }

  SetDragMode(state){
    $('.content-wrapper.gallery').attr('data-drag-mode', state);
    this.swapy.enable(state);
  }

  #GetChangedItems(initialMap, finalMap){
    const changedItems = {};
    for (let slot in finalMap) {
      if (finalMap[slot] !== initialMap[slot]) {
        changedItems[slot] = finalMap[slot];
      }
    }

    return changedItems;
  }

  #UpdateGalleryOrder(newOrderMap){
    for (let slot in newOrderMap) {
      $(`.gallery-item[data-swapy-item="${newOrderMap[slot]}"]`).find(".order").text(slot);
    }
  }
}
