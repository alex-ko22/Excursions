"use strict";

loadGuides();
showTodayRecsCard();
//showRecsTable('filter');

function showRecsTable(form) {
    let code = `
      <tr>
        <th>#</th>
        <th>Название</th>
        <th>$</th>
        <th>Экскурсовод</th>
        <th>Дата и время</th>
        <th>Перейти к экскурсии</th>
      </tr>
    `;
    let tbody = document.getElementById("tbody");
    let free = '';
    let dayStr = '';
    let i = 0;

    cards.innerHTML = '';

    const formData = new FormData(form);
    
    fetch("php/getRecs.php", {
      method: "POST",
      body: formData
    }).then(response=>response.json())
      .then(result=>{
        result.forEach((recs,index)=>{
          if (recs.free == 1){free = ''
          }else{free = '$'}

          dayStr = recs.date.substr(8,2) + "." + recs.date.substr(5,2) + " в " + recs.time.substr(0,5); 

          code += `
          <tr>
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

function loadGuides() {

  let code = '<option selected value="0">Все</option>';
  let tbody = document.getElementById("guides-list");

  fetch("php/getGuides.php").then(response=>response.json())
    .then(result=>{
    result.forEach((recs)=>{
      code += `
       <option value="${recs.id}">${recs.guide}</option>
      `
    });
    tbody.innerHTML = code;
  })
} 

function showTodayRecsCard() {

  let code = '';
  let tbody = document.getElementById("cards");
  let free = '';
  let dayStr = '';
  let i = 0;
  
  fetch("php/getTodayRecs.php").then(response=>response.json())
    .then(result=>{
      result.forEach((recs, index)=>{
        
        if (recs.free == 1){free = '<span class="text-success">Бесплатная</span>'
        }else{free = '<span class="text-danger">Платная</span>'}

        dayStr = recs.date.substr(8,2) + "." + recs.date.substr(5,2) + " в " + recs.time.substr(0,5); 
        
        code += `
        <div class="card text-center border border-secondary">
          <img src=${recs.img_url} class="picture my-2">
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
      tbody.innerHTML = code;
    }) 
}   