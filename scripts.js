
    <script>
        function toggleNewProjectInput() {
            var select = document.getElementById("nom_projet_exist");
            var newProjectContainer = document.getElementById("new_project_container");

            if (select.value === "new") {
                newProjectContainer.style.display = "block";
            } else {
                newProjectContainer.style.display = "none";
            }
        }

        // Récupérer les données du projet sélectionné
        document.getElementById("nom_projet_exist").addEventListener("change", function() {
            var projet = this.value;

            if (projet !== "" && projet !== "new") {
                fetch("get_project_data.php?nom_projet=" + encodeURIComponent(projet))
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById("cible").value = data.cible || "";
                        document.getElementById("thematique").value = data.thematique || "";
                    })
                    .catch(error => console.error("Erreur de récupération des données:", error));
            } else {
                document.getElementById("cible").value = "";
                document.getElementById("thematique").value = "";
            }
        });
    </script>





