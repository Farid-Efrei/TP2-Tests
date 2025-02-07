document.addEventListener("DOMContentLoaded", function () {
    const userForm = document.getElementById("userForm");
    const userList = document.getElementById("userList");
    const userIdField = document.getElementById("userId");
    const roleField = document.getElementById("role"); // Nouveau ajout role

    function fetchUsers() {
        fetch("api.php")
            .then(response => response.json())
            .then(users => {
                userList.innerHTML = "";
                users.forEach(user => {
                    const li = document.createElement("li");
                    // On prend désormais en compte le role dans l'affichage:
                    li.innerHTML = `${user.name} (${user.email} - Rôle : ${user.role})
                        <button onclick="editUser(${user.id}, '${user.name}', '${user.email}','${user.role}' )">✏️</button>
                        <button onclick="deleteUser(${user.id})">❌</button>`;
                    userList.appendChild(li);
                });
            });
    }

    userForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const name = document.getElementById("name").value;
        const email = document.getElementById("email").value;
        const userId = userIdField.value;
        const role = roleField.value; // on récupère la valeur du <select>

        if (userId) {
            // Méthode PUT => update:
            fetch("api.php", {
                method: "PUT",
                body: new URLSearchParams({ id: userId, name, email, role }),
                headers: { "Content-Type": "application/x-www-form-urlencoded" }
            }).then(() => {
                fetchUsers();
                userForm.reset();
                userIdField.value = "";
            });
        } else {
            // Méthode POST => add:
            fetch("api.php", {
                method: "POST",
                body: new URLSearchParams({ name, email, role }),
                headers: { "Content-Type": "application/x-www-form-urlencoded" }
            }).then(() => {
                fetchUsers();
                userForm.reset();
            });
        }
    });

    //On adapte la fonction editUser pour remplir le sélect "role":
    window.editUser = function (id, name, email, role) {
        document.getElementById("name").value = name;
        document.getElementById("email").value = email;
        userIdField.value = id;
        roleField.value = role; // Remplit le champ select
    };

    window.deleteUser = function (id) {
        fetch(`api.php?id=${id}`, { method: "DELETE" })
            .then(() => fetchUsers());
    };

    fetchUsers();
});
