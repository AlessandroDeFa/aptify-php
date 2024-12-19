import { ContentDialog } from "./classes/ContentDialog.js";
import { ConfirmationDialog } from "./classes/ConfirmationDialog.js";
import { ContentEditor } from "./classes/ContentEditor.js";
import { AddMediaGalleryDialog } from "./classes/AddMediaGalleryDialog.js";
import { GalleryReorderManager } from "./classes/GalleryReorderManager.js";

const toggleItemVisibility = (tableId, id, state, { onLoading, onCompletion })=>{
  if (typeof onLoading === "function") onLoading();
  $.ajax({
      url : '/api/deactivate.php',
      type : 'POST',
      data: {tableId, id, state},
      success : function({status, message, error_code}) {
        if (typeof onCompletion === "function") {
          onCompletion({ status, message, error_code });
        }
      },
      error : function(request,error){
        const response = request.responseJSON
        if (typeof onCompletion === "function") {
          onCompletion({ status: response.status, message: response.message, error_code: response.error_code });
        }
      }
  });
}

$(".visible-toggle").on('input', function(){
  const currentTarget = $(this);
  const tableId = currentTarget.data('table');
  const state = currentTarget.is(':checked') ? '1' : '0';
  const id = currentTarget.data('id');
  toggleItemVisibility(tableId, id, state, {
    onCompletion: ({ status, message, error_code})=>{
      if(!status){
        currentTarget.prop('checked', !currentTarget.is(':checked'));
        showAlert('error', message, "top right");
        return
      }
      showAlert('success', message, "top right");
    }
  });
})

const deleteItem = (id, tableId, { onLoading, onCompletion }) => {
   if (typeof onLoading === "function") onLoading();
   $.ajax({
       url : '/api/delete.php',
       type : 'POST',
       data: {tableId, id},
       success : function({status, message, error_code}) {
         if (typeof onCompletion === "function") {
           onCompletion({ status, message, error_code });
         }
       },
       error : function(request,error){
         const response = request.responseJSON
         if (typeof onCompletion === "function") {
           onCompletion({ status: response.status, message: response.message, error_code: response.error_code });
         }
       }
   });
};

$(".content-item .delete").on('click', function() {
  const el = $(this).closest(".content-item")
  const id = $(this).data('id');
  const tableId = $(this).data('table');
  const button =$(".confirmation-dialog .submit" );
  new ConfirmationDialog({
    onConfirm: (dismiss)=>{
      deleteItem(id,tableId, {
        onLoading: ()=>{
          button.addClass("loading");
        },
        onCompletion: ({status, message, error_code})=>{
          button.removeClass("loading");
          if(!status){
            showAlert('error', message, 'top center');
            return
          }
          dismiss();
          el.remove();
          $(".content-item").each(function(index) {
            $(this).find(".index").text(index + 1);
          });
          const contentLength = $(".content-item").length
          $(".header-content > div > p").text(contentLength +" "+ (contentLength == 1 ? 'Contenuto' : 'Contenuti'));
          showAlert('success', message, 'top center');
        }
      });
    }
  });
});

$(".create-content").on('click', function(){
  const contentDialog = new ContentDialog();
})

$(".more-button").on('click', function(){
  $(this).closest('.edit-content-header').find('.more-menu').toggleClass('active');
})

$(".more-menu .delete").on('click', function() {
  const id = $(this).data('id');
  const tableId = $(this).data('table');
  const button = $(".confirmation-dialog .submit" );
  new ConfirmationDialog({
    onConfirm: ()=>{
      deleteItem(id,tableId, {
        onLoading: ()=>{
          button.addClass("loading");
        },
        onCompletion: ({status, message, error_code})=>{
          button.removeClass("loading");
          if(!status){
            showAlert('error', message, 'top center');
            return
          }
          $('.content-link').each(function() {
            const href = $(this).find('a').attr('href');
            const lastSegment = href.split('/').pop();

            if (lastSegment !== id) {
              window.location.href = href;
              return false;
            }
          });
          redirectTo('/contents');
        }
      });
    }
  });
});

if (typeof content !== 'undefined' && content !== null) {
  const contentEditor = new ContentEditor(content);
}

$(".gallery-item .delete").on('click', function () {
  const id = $(this).data('id');
  const tableId = $(this).data('table');
  const button =$(".confirmation-dialog .submit" );
  new ConfirmationDialog({
    onConfirm: (dismiss)=>{
      deleteItem(id,tableId, {
        onLoading: ()=>{
          button.addClass("loading");
        },
        onCompletion: ({status, message, error_code})=>{
          button.removeClass("loading");
          if(!status){
            showAlert('error', message, 'top center');
            return
          }
          dismiss(()=>{location.reload();});
        }
      });
    }
  });
});

$('.add-media-gallery').on('click', function () {
  const addMediaGalleryDialog = new AddMediaGalleryDialog();
});

const galleryOrderManager = new GalleryReorderManager('.content-wrapper.gallery table');
