"use strict";

let btn = document.getElementById('btn');

loadGuides();
formDateSelector();
setTimeout(()=>{btn.click();},1000);

// Функция выбора вида показа - таблица или карточка

function showRecs(form) {
 
  let tbody = document.getElementById("tbody");
  let cards = document.getElementById("cards");
  
  tbody.innerHTML = '';
  cards.innerHTML = '';
   
  if(modeTable.checked == true){
    showRecsTable(form);
  }else{
    showRecsCard(form);
  }
}

// Функция запроса и отображения записей в виде таблицы  по фильтру

function showRecsTable(form) {

    let code = `
      <tr class="t-heads">
        <th>#</th>
        <th><em>Название</em></th>
        <th>$</th>
        <th><em>Экскурсовод</em></th>
        <th><em>Дата и время</em></th>
        <th><em>Перейти к экскурсии:</em></th>
      </tr>
    `;
    let tbody = document.getElementById("tbody");
    let free = '';
    let dayStr = '';
    let i = -1;
    let fav = ' ';
    
    const formData = new FormData(form);
    
    fetch("php/getRecs.php", {
      method: "POST",
      body: formData
    }).then(response=>response.json())
      .then(result=>{result.forEach((recs,index)=>{
          if (recs.free == 1){free = ''
          }else{free = '$'}

          fav = getFavicon(recs.site);
          
          let day = getDayOfWeek(recs.date);
          dayStr = day + recs.date.substr(8,2) + "." + recs.date.substr(5,2) + " в " + recs.time.substr(0,5); 
          code += `
          <tr class="t-row">
            <td>${index+1}</td>
            <td class="title" onclick="showModalDescr(${recs.exc_id})">${recs.title}</td>
            <td>${free}</td>            
            <td class="guide" onclick="showModalGuide(${recs.guide_id})">${recs.guide}</td>
            <td>${dayStr}</td>
            <td><img class = "favicon" src = "${fav}"><a href="${recs.link}" onclick="window.open(this.href, '_blank'); return false;">${recs.site}</a></td>
          </tr>
        `       
        i = index;
        });
    
        inform.innerText = 'Найдено экскурсий: ' + String(i+1); 
        tbody.innerHTML = code;
      }) 
}   

// Функция получения гидов из базы и помещение их в селектор

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
    code = `<option selected value=" ">Все ${i}</option>` + code;
    guidesList.innerHTML = code;
  })
} 

// Функция запроса и отображения записей в виде карточек  по фильтру

function showRecsCard(form) {

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
          <img src="${recs.img_url}" class="picture my-2">
          <h5 class="title" onclick="showModalDescr(${recs.exc_id})">${recs.title}</h5>
          <p>${dayStr}</p>
          <p class="guide" onclick="showModalGuide(${recs.guide_id})">${recs.guide}</p>
          <p>${free}</p>
          <p class="mb-1">Перейти к экскурсии на</p>
          <div class = "div-favicon mb-3">
            <img src="${fav}" class="favicon">
            <a href="${recs.link}" onclick="window.open(this.href, '_blank'); return false;">${recs.site}</a>
          </div>
          </div>
      ` 
      i = index;
      });
      inform.innerText = 'Найдено экскурсий: ' + String(i+1); 
      cards.innerHTML = code;
    }) 
}   

function getFavicon(site) {
  let fav = ' ';
  if(site == 'Mosstreets'){
    fav = 'img/mosstreets.png'
  }else if(site == 'Moscowwalking'){
    fav = 'img/moscowwalking.png'
  }else if(site == 'Moscoviti'){
    fav = 'img/moscoviti.png'
  }else if(site == 'Tvoyamoskva'){
    fav = 'img/tvoyamoskva.png' 
  }else if(site == 'Moskvahod'){
    fav = 'img/moskvahod.png'
  }else if(site == 'Moscowsteps'){
    fav = 'img/moscowsteps.png'} 
  return(fav);
}

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

function formDateSelector(){
  
  let code = `
  <option value="0" selected>Сегодня</option>
  `;
  let now = new Date();
  let daysList = document.getElementById("days-list");
  let i = 0;
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
  <option value="100">${'На ' + i + ' дней'}</option>
   `   
  daysList.innerHTML = code;
}

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

function showModalDescr(exc_id) {
  const formData = new FormData();

  formData.append('exc_id',exc_id);
  fetch("php/getDescr.php", {
    method: "POST",
    body: formData
    }).then(response=>response.json())
      .then(result => {
        showModalWindow(result.guide,result.descr);
      });
}

function showModalGuide(guide_id) {
  const formData = new FormData();

  formData.append('guide_id',guide_id);
  fetch("php/getGuideInfo.php", {
    method: "POST",
    body: formData
    }).then(response=>response.json())
      .then(result => {
        showModalWindow(result.guide,result.about,result.src_foto);
      });
}

function showModalWindow(title,descr,img_src) {
    if (descr == 'about'){
      descr = `Этот сайт возник как учебный проект для сдачи диплома на 2х месячных курсах по web-программированию. При
      выборе темы диплома мне показалось интересным собрать в одном месте все предложения по пешим экскурсиям по Москве
       с соответствующих сайтов. Сам я периодически посещаю подобные мероприятия, мне 
      нравится прогуляться по Москве пару часов, да при этом ещё и узнать что-то новенькое - приятно и полезно. 
      Но каждый раз приходилось заглядывать на разные ресурсы, чтобы найти подходящий вариант. Теперь стало 
      попроще). Страничка получилась немудрёной, но задачи свои выполняет. Если у публики будет интерес к моей идее,
      то проект можно будет развить, добавляя функционал и контент. Все ваши отзывы, пожелания, негодования отправляйте
      на адрес электронной почты, указанный внизу основной страницы.`
    }
    else if (descr != 'about' && arguments.length == 2){
      descr = String.fromCharCode(171) + ' — ' + descr + ' ' + String.fromCharCode(187);
      title = title + ':'
    }

    document.body.style.overflowY = 'hidden';
    modal__.style.overflowY = 'auto'; 
    modal__.classList.add('active');
    overlay.classList.add('active');
    modal__content.innerHTML = descr;
    modal__guide.innerText = title;
    modal__img.src = img_src;
    
    modal__cross.addEventListener('click', ()=>{  
      modal__.classList.remove('active');
      overlay.classList.remove('active');
      document.body.style.overflowY = 'auto';
    });
    overlay.addEventListener('click', ()=> {
      modal__.classList.remove('active');
      overlay.classList.remove('active');
      document.body.style.overflowY = 'auto';
    });  

   /*  document.body.addEventListener('keydown', (event)=> {
      if (event.code == 'Escape') {
        modal__.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflowY = 'auto';
      };
    }); */

}