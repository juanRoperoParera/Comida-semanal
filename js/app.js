// Obtener los elementos arrastrables
var draggables = document.querySelectorAll('.seleccionComidas .comida');
var data = [];

// Obtener los elementos de la tabla
var tds = document.querySelectorAll('.calendario table td');

var table = document.querySelector('table');

// Agregar eventos dragstart y dragend a los elementos arrastrables
draggables.forEach(function (draggable) {
  draggable.addEventListener('dragstart', function (e) {
    e.dataTransfer.setData('text/plain', draggable.id);
  });

  draggable.addEventListener('dragend', function () {
    // Limpiar cualquier clase de resaltado
    tds.forEach(function (td) {
      td.classList.remove('over');
    });
  });
});

// Agregar eventos dragover, dragenter, dragleave y drop a los elementos de la tabla
tds.forEach(function (td) {
  td.addEventListener('dragover', function (e) {
    e.preventDefault();
  });

  td.addEventListener('dragenter', function (e) {
    e.preventDefault();
    td.classList.add('over');
  });

  td.addEventListener('dragleave', function () {
    td.classList.remove('over');
  });

  td.addEventListener('drop', function (e) {
    e.preventDefault();

    var draggableId = e.dataTransfer.getData('text/plain');
    var draggable = document.getElementById(draggableId);
    td.appendChild(draggable);
    td.classList.remove('over');

    guardarTablaEnLocalStorage();

    var tableFilas = document.querySelectorAll('table tr>td');

    if (tableFilas instanceof NodeList) {
      // Convertir NodeList a array utilizando Array.from()
      tableFilas = Array.from(tableFilas);
    }

    tableFilas.forEach(function (contenedor) {
      var h5Element = contenedor.querySelector('h5');

      if (h5Element) {
        var contenido = {
          titulo: h5Element.textContent,
        };

      }


      data.push(contenido);
    });

    fetch('../index.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    })
    .then(response => {
      // Verifica si la respuesta fue exitosa
      if (!response.ok) {
        throw new Error('La solicitud no fue exitosa');
      }
      // Procesa la respuesta como JSON
      return response.json();
    })
    .then(data => {
      // Maneja los datos recibidos
      console.log(data);
    })
    .catch(error => {
      // Maneja los errores que puedan ocurrir durante la solicitud
      console.error('Error:', error);
    });
  });
});

function guardarTablaEnLocalStorage() {
  var tablaHTML = table.innerHTML;
  localStorage.setItem('tablaHTML', tablaHTML);
}

function restaurarTablaDesdeLocalStorage() {
  var tablaGuardada = localStorage.getItem('tablaHTML');
  if (tablaGuardada) {
    table.innerHTML = tablaGuardada;
  }
}

restaurarTablaDesdeLocalStorage();
