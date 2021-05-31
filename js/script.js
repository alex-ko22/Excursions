"use strict";

loadGuides();
showTodayRecsCard();

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

    const formData = new FormData(form);
    
    fetch("php/getRecs.php", {
      method: "POST",
      body: formData
    }).then(response=>response.json())
      .then(result=>{result.forEach((recs,index)=>{
          if (recs.free == 1){free = ''
          }else{free = '$'}

          dayStr = recs.date.substr(8,2) + "." + recs.date.substr(5,2) + " в " + recs.time.substr(0,5); 

          code += `
          <tr class="t-row">
            <td>${index+1}</td>
            <th scope="row" class="title" data-bs-toggle="tooltip" data-bs-placement="bottom" title="${recs.descr}">${recs.title}</th>
            <td>${free}</td>            
            <td>${recs.guide}</td>
            <td>${dayStr}</td>
            <td><a href="${recs.link}">${recs.site}</a></td>
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

  let code = '<option selected value="0">Все</option>';
  let guidesList = document.getElementById("guides-list");

  fetch("php/getGuides.php").then(response=>response.json())
    .then(result=>{
    result.forEach((recs)=>{
      code += `
       <option value="${recs.id}">${recs.guide}</option>
      `
    });
    guidesList.innerHTML = code;
  })
} 

// Функция запроса и отображения записей в виде карточек за текущую дату

function showTodayRecsCard() {

  let code = '';
  let cards = document.getElementById("cards");
  let free = '';
  let dayStr = '';
  let i = -1;
  
  fetch("php/getTodayRecs.php").then(response=>response.json())
    .then(result=>{
      result.forEach((recs, index)=>{
        
        if (recs.free == 1){free = '<span class="text-success">Бесплатная</span>'
        }else{free = '<span class="text-danger">Платная</span>'}

        dayStr = recs.date.substr(8,2) + "." + recs.date.substr(5,2) + " в " + recs.time.substr(0,5); 
        
        code += `
        <div class="card text-center border border-1">
          <img src="${recs.img_url}" class="picture my-2 class="img-fluid">
          <h5 class="title" data-bs-toggle="tooltip" data-bs-placement="bottom" title="${recs.descr}">${recs.title}</h5>
          <p>${dayStr}</p>
          <p>${recs.guide}</p>
          <p>${free}</p>
          <p class="mb-1">Перейти к экскурсии на</p>
          <a href="${recs.link}" class="mb-2">${recs.site}</a>
          </div>
      ` 
      i = index;
      });
      inform.innerText = 'Найдено экскурсий: ' + String(i+1); 
      cards.innerHTML = code;
    }) 
}   

// Функция запроса и отображения записей в виде карточек  по фильтру

function showRecsCard(form) {

  let code = '';
  let cards = document.getElementById("cards");
  let free = '';
  let dayStr = '';
  let i = -1;
  
  const formData = new FormData(form);
  
  fetch("php/getRecs.php", {
    method: "POST",
    body: formData
    }).then(response=>response.json())
      .then(result=>{
      result.forEach((recs, index)=>{
        
        if (recs.free == 1){free = '<span class="text-success">Бесплатная</span>'
        }else{free = '<span class="text-danger">Платная</span>'}

        dayStr = recs.date.substr(8,2) + "." + recs.date.substr(5,2) + " в " + recs.time.substr(0,5); 
        
        code += `
        <div class="card text-center border border-1">
          <img src="${recs.img_url}" class="picture my-2">
          <h5 class="title" data-bs-toggle="tooltip" data-bs-placement="bottom" title="${recs.descr}">${recs.title}</h5>
          <p>${dayStr}</p>
          <p>${recs.guide}</p>
          <p>${free}</p>
          <p class="mb-1">Перейти к экскурсии на</p>
          <a href="${recs.link}" class="mb-2">${recs.site}</a>
          </div>
      ` 
      i = index;
      });
      inform.innerText = 'Найдено экскурсий: ' + String(i+1); 
      cards.innerHTML = code;
    }) 
}   

