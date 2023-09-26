
const contenedor=document.querySelector(".meteo");

let info;

let ciudad = 'Granada';

busqueda(ciudad);

async function busqueda(busqueda){
  const respuesta = await fetch(`http://api.weatherapi.com/v1/forecast.json?=${busqueda}`);
  const datos = await respuesta.json();
  info = datos;
  let temp_max=info.forecast.forecastday[0].day.maxtemp_c;
  let temp_min=info.forecast.forecastday[0].day.mintemp_c;
  let viento=info.forecast.forecastday[0].day.maxwind_kph;
  let probabilidad_lluvia = info.forecast.forecastday[0].day.daily_chance_of_rain;
  let uv = info.forecast.forecastday[0].day.uv;
  let amanecer=info.forecast.forecastday[0].astro.sunrise;
  let atardecer=info.forecast.forecastday[0].astro.sunset;
  let icono=info.forecast.forecastday[0].day.condition.icon;

  console.log(info.forecast.forecastday[0]);

  crearDOM(temp_max,temp_min,icono,amanecer,atardecer,probabilidad_lluvia,uv,viento);
}

function crearDOM(temp_max,temp_min,icono,amanecer,atardecer,probabilidad_lluvia,uv,viento) {
  contenedor.innerHTML+=`
    <ul>
        <li class='max'>${temp_max} °C</li>
        <li class='min'>${temp_min} °C</li>
        <li class='text'>Amanecer</li>
        <li class='text'>Atardecer</li>
        <li>${amanecer}</li>
        <li>${atardecer}</li>
        <li class='lluvia'><i class="fa-solid fa-cloud-showers-heavy"></i> ${probabilidad_lluvia} %</li>
        <li class='lluvia'><i class="fa-solid fa-wind"></i> ${viento} km/h</li>
    </ul>
    <img src='${icono}' alt=''>
  `;
}
