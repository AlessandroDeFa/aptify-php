const redirectTo=(path, delay=0)=>{
  const baseUrl = window.location.origin;
  const href = `${baseUrl}/admin${path}`;
  if (delay > 0) {
      setTimeout(() => {
          window.location.href = href;
      }, delay);
  } else {
      window.location.href = href;
  }
}


let timeoutIds = [];

const showAlert = (type, message, pos = "top center", container=".container") => {
  $(".alert").stop(true, true).remove();
  let svg;
  switch (type) {
    case "error":
      svg = `<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M8.36 2H16.64L22.5 7.86V16.14L16.64 22H8.36L2.5 16.14V7.86L8.36 2Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M12.5 8V12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M12.5 16H12.51" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>`;
      break;
    default:
      svg = `<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M22.5 11.08V12C22.4988 14.1564 21.8005 16.2547 20.5093 17.9818C19.2182 19.709 17.4033 20.9725 15.3354 21.5839C13.2674 22.1953 11.0573 22.1219 9.03447 21.3746C7.01168 20.6273 5.28465 19.2461 4.11096 17.4371C2.93727 15.628 2.37979 13.4881 2.52168 11.3363C2.66356 9.18457 3.49721 7.13633 4.89828 5.49707C6.29935 3.85782 8.19279 2.71538 10.2962 2.24015C12.3996 1.76491 14.6003 1.98234 16.57 2.86" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M22.5 4L12.5 14.01L9.5 11.01" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>`;
  }

  const alert = $(`
    <div class="alert ${type}">
      ${svg}
      <div class="message">${message}</div>
    </div>
  `);


  const [vertical, horizontal] = pos.split(" ");
  alert.addClass(vertical === 'center' ? "Y" + vertical : vertical);
  alert.addClass(horizontal === 'center' ? "X" + horizontal : horizontal);
  $(container).append(alert);
  const timeoutId = setTimeout(() => {
    alert.remove();
    timeoutIds = timeoutIds.filter((id) => id !== timeoutId);
  }, 3000);

  timeoutIds.push(timeoutId);
};
