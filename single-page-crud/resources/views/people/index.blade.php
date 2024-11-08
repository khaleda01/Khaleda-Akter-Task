<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Single Page Image CRUD</title>
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <style>
       body {
           font-family: Arial, sans-serif;
           display: flex;
           flex-direction: column;
           align-items: center;
           padding: 20px;
       }
       form {
           display: flex;
           flex-direction: column;
           width: 300px;
           margin-bottom: 20px;
       }
       input[type="text"], input[type="number"], input[type="file"] {
           margin-bottom: 10px;
           padding: 8px;
       }
       button {
           padding: 10px;
           cursor: pointer;
       }
       .person-card {
           display: flex;
           align-items: center;
           border: 1px solid #ccc;
           padding: 10px;
           margin: 5px 0;
           width: 100%;
           justify-content: space-between;
       }
       img {
           width: 50px;
           height: 50px;
           border-radius: 50%;
           margin-right: 10px;
       }
   </style>
</head>
<body>
   <h1>Manage People</h1>
   
   <form id="personForm">
       <input type="text" name="name" id="name" placeholder="Name" required>
       <input type="number" name="age" id="age" placeholder="Age" required>
       <input type="file" name="image" id="image" required>
       <button type="submit">Add Person</button>
   </form>

   <div id="peopleList"></div>

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script>
       $(document).ready(function () {
           const csrfToken = $('meta[name="csrf-token"]').attr('content');

           function fetchPeople() {
               $.get('/people', function (data) {
                   $('#peopleList').html('');
                   data.forEach(person => {
                       $('#peopleList').append(`
                           <div class="person-card" id="person-${person.id}">
                               <img src="/storage/${person.image_path}" alt="Image">
                               <div>
                                   <p>Name: ${person.name}</p>
                                   <p>Age: ${person.age}</p>
                               </div>
                               <div>
                                   <button onclick="editPerson(${person.id})">Edit</button>
                                   <button onclick="deletePerson(${person.id})">Delete</button>
                               </div>
                           </div>
                       `);
                   });
               });
           }

           fetchPeople();

           $('#personForm').on('submit', function (e) {
               e.preventDefault();
               let formData = new FormData(this);

               $.ajax({
                   url: '/people',
                   method: 'POST',
                   headers: { 'X-CSRF-TOKEN': csrfToken },
                   data: formData,
                   processData: false,
                   contentType: false,
                   success: function (person) {
                       fetchPeople();
                       $('#personForm')[0].reset();
                   },
                   error: function (error) {
                       console.log(error);
                   }
               });
           });
       });

       function deletePerson(id) {
           const csrfToken = $('meta[name="csrf-token"]').attr('content');
           $.ajax({
               url: `/people/${id}`,
               method: 'DELETE',
               headers: { 'X-CSRF-TOKEN': csrfToken },
               success: function () {
                   $(`#person-${id}`).remove();
               },
               error: function (error) {
                   console.log(error);
               }
           });
       }
   </script>
</body>
</html>
