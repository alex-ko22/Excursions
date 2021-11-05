"use strict";
start();

function start() {
  let form = document.getElementById('form');
  loadGuides();
  formDateSelector();
  setTimeout(()=>showRecs(form),1000)
}

/**
 * Функция запроса и отображения записей в виде карточек  по фильтру
 * @param form
 */
function showRecs(form) {
  let code = '';
  let cards = document.getElementById("cards");
  let free = '';
  let dayStr = '';
  let i = -1;
  let fav = ' ';
  const formData = new FormData(form);
  
  fetch("php/getRecs.php", {
    method: "POST",
    body: formData
    }).then(response=>response.json())
      .then(result=>{
      result.forEach((recs, index)=>{
        
        if (recs.free == 1){free = '<span class="text-success">Бесплатная</span>'
        }else{free = '<span class="text-danger">Платная</span>'}

        fav = getFavicon(recs.site);

        let day = getDayOfWeek(recs.date);

        dayStr = day + recs.date.substr(8,2) + "." + recs.date.substr(5,2) + " в " + recs.time.substr(0,5); 
        
        code += `
        <div class="card text-center border border-1">
          <img src="${recs.img_url}" class="picture my-2" alt=" ">
          <h5 class="title" onclick="showModalDescr(${recs.exc_id})">${recs.title}</h5>
          <p>${dayStr}</p>
          <p class="guide" onclick="showModalGuide(${recs.guide_id})">${recs.guide}</p>
          <p>${free}</p>
          <p class="mb-1">Перейти к экскурсии на</p>
          <div class = "div-favicon mb-3">
            <img src="${fav}" class="favicon" alt=" ">
            <a href="${recs.link}" onclick="window.open(this.href, '_blank'); return false;">${recs.site}</a>
          </div>
          </div>
      ` 
      i = index;
      });
      inform.textContent = i + 1;
      cards.innerHTML = code;
    }) 
}

/**
 * Функция получения гидов из базы и помещение их в селектор
 */
function loadGuides() {
    let i = 0;
    let code = '';
    let guidesList = document.getElementById("guides-list");

    fetch("php/getGuides.php").then(response=>response.json())
        .then(result=>{
            result.forEach((recs)=>{
                code += `
       <option value="${recs.id}">${recs.guide}</option>
      `
                i++;
            });
            code = `<option selected value="All">Все ${i}</option>` + code;
            guidesList.innerHTML = code;
        })
}

/**
 * Функция возврвщает путь к фавикону соответствующего сайта
 * @param site
 * @returns {string}
 */
function getFavicon(site) {
  switch(site) {
    case 'Mosstreets':
      return('img/mosstreets.png');
    case 'Moscowwalking':
      return('img/moscowwalking.png');
    case 'Moscoviti' :
      return('img/moscoviti.png');
    case 'Tvoyamoskva':
      return('img/tvoyamoskva.png');
    case 'Moskvahod' :
      return('img/moskvahod.png');
    case 'Moscowsteps':
      return('img/moscowsteps.png');
  }
}

/**
 * Функция возврвщает сокращённый день недели
 * @param date
 * @returns {string}
 */
function getDayOfWeek(date) {
  let dateArr = date.split('-'); 
  let objDate = new Date(dateArr[0],dateArr[1]-1,dateArr[2]);

  switch(objDate.getDay()){
    case 0:
      return('Вс ');
    case 1:
      return('Пн ');
    case 2:
      return('Вт ');
    case 3:
      return('Ср ');
    case 4:
      return('Чт ');  
    case 5:
      return('Пт ');
    case 6:
      return('Сб '); 
    }
}

/**
 * Функция формирует селектор для выбора дат
 */
function formDateSelector(){
  let code = `
  <option value="0" selected>Сегодня</option>
  `;
  let now = new Date();
  let daysList = document.getElementById("days-list");
  let i;
  let dayStr = '';

  for(i = 1; i <= 9; i++){
    now.setDate(now.getDate() + 1);
    dayStr = getDateStr(now);
    
    if((dayStr.substr(0,2) == 'Сб') || (dayStr.substr(0,2) == 'Вс')){
      code += `
       <option class="weekend" value="${i}">${dayStr}</option>
      `  
    }else{
    code += `
       <option value="${i}">${dayStr}</option>
      `
    }
  }
  code += `
  <option value="10">На 10 дней</option>
   `   
  daysList.innerHTML = code;
}

/**
 * Функция формирует строку даты для показа в карточке
 * @param day
 * @returns {string}
 */
function getDateStr(day) {
  let month = day.getMonth() + 1;
  let dayOfWeek = day.getDay();
  let dayOfMonth = day.getDate();

  switch(month){
      case 1:
          month = ' января';
          break;
      case 2:
        month = ' февраля';
        break;
      case 3:
        month = ' марта';
        break;
      case 4:
        month = ' апреля';
        break;
      case 5:
        month = ' мая';
        break;
      case 6:
        month = ' июня';
        break;
      case 7:
        month = ' июля';
        break;
      case 8:
        month = ' августа';
        break;
      case 9:
        month = ' сентября';
        break;
      case 10:
        month = ' октября';
        break;
      case 11:
        month = ' ноября';
        break;
      case 12:
        month = ' декабря';
        break;
  }
  
  switch(dayOfWeek){
      case 1:
          return('Пн  ' + dayOfMonth + month);
      case 2:
          return('Вт  ' + dayOfMonth + month);
      case 3:
          return('Ср  ' + dayOfMonth + month);
      case 4:
          return('Чт  ' + dayOfMonth + month);
      case 5:
          return('Пт  ' + dayOfMonth + month);
      case 6:
          return('Сб  ' + dayOfMonth + month);
      case 0:
          return('Вс  ' + dayOfMonth + month);                    
  }
}

/**
 * Функция показывает модальное окно описания экскурсии
 * @param exc_id
 */
function showModalDescr(exc_id) {
  const formData = new FormData();

  formData.append('exc_id',exc_id);
  fetch("php/getDescr.php", {
    method: "POST",
    body: formData
    }).then(response=>response.json())
      .then(result => {
        showModalWindow(1,result.guide,result.descr,result.img_url);
      });
}

/**
 * Функция показывает модальное окно с презентацией гида
 * @param guide_id
 */
function showModalGuide(guide_id) {
  const formData = new FormData();

  formData.append('guide_id',guide_id);
  fetch("php/getGuideInfo.php", {
    method: "POST",
    body: formData
    }).then(response=>response.json())
      .then(result => {
        showModalWindow(2, result.guide,result.about,result.src_foto);
      });
}

/**
 * Функция отображения модального окна
 * @param title
 * @param descr
 * @param img_src
 */
function showModalWindow(mode, title,descr,img_src) {

    //descr = String.fromCharCode(171) + ' — ' + descr + ' ' + String.fromCharCode(187);
    if (mode == 1) {
      descr = '<q>' + ' — ' + descr + "</q>";
      title += ':'
    }

    // Полоса прокрутки
    const scrollBarWidth = window.innerWidth - document.documentElement.clientWidth;
    const widthBefore = document.body.clientWidth;
    document.body.style.overflowY = 'hidden';
    if (widthBefore !== document.body.clientWidth ) {
      document.body.style.paddingRight = scrollBarWidth + 'px';
    }

    modal.style.overflowY = 'auto';
    modal.classList.add('active');
    overlay.classList.add('active');
    modal_content.innerHTML = descr;
    modal_guide.innerText = title;
    modal_img.src = img_src;
    
    modal_cross.addEventListener('click', closeModalWindow);
    overlay.addEventListener('click', closeModalWindow);
    document.onkeydown = function (event) {
      if(event.key == 'Escape') closeModalWindow();
    }

    function closeModalWindow() {
      modal.classList.remove('active');
      overlay.classList.remove('active');
      setTimeout(()=>{document.body.style.paddingRight = 0 + 'px';
                              document.body.style.overflowY = 'auto'}  ,300);
    }
}