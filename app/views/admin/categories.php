<?php $this->view("inc/header", $data); ?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center">Categories - Admin</h1>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-8">
            <button class="btn btn-primary" id="btnSubmit" onclick="displayForm()">Ajouter Catégorie</button>
        </div>
        <div class="col-8 formCat">
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Nom de la catégorie : </label>
                    <input id="inputAddCat" type="text" name='name' class="form-control">
                </div>
                <button type="button" onclick="collectDataCat()" class="btn btn-primary">Valider</button>
                <button type="button" onclick="displayForm()" class="btn btn-warning">Fermer</button>
            </form>
        </div>
        <div class="col-8 formEditCat hide">
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Nouveau nom de la catégorie : </label>
                    <input id="inputEditCat" type="text" name='name' class="form-control">
                </div>
                <button type="button" id="btnEditCat" onclick="collectDataEditCat()" class="btn btn-primary">Valider</button>
                <button type="button" onclick="displayEditForm()" class="btn btn-warning">Fermer</button>
            </form>
        </div>
        <div class="row justify-content-center">
            <div class="col-8">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Modifier</th>
                            <th scope="col">Supprimer</th>
                        </tr>
                    </thead>
                    <tbody id="tableCategories">
                        <?php
                        echo $tableHTML;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    echo $noCat;
    ?>
</div>
<script>
    /**
     * sendDataAjax
     * send the data to the Ajax controller PHP
     * @param  {object} data={}
     * @return void
     */
    function sendDataAjax(data = {}) {
        const ajax = new XMLHttpRequest();
        ajax.onload = function() {
            handleResultAjax(ajax.responseText);
        };

        ajax.open("POST", "<?= ROOT ?>categoryAjax", true);
        ajax.setRequestHeader("Content-type", "application/json");
        ajax.send(JSON.stringify(data));
    }

    /**
     * handleResultAjax
     * get the result from PHP and update the HTML table for categories
     * @return void
     */
    function handleResultAjax(result) {
        if (result != "") {
            let resultObj = JSON.parse(result);
            console.log(resultObj);
            if (typeof resultObj.dataType != "undefined") {
                if (resultObj.dataType == "addCategory") {
                    if (resultObj.messageType == "info") {
                        displayForm();
                        const tableCategories_tbody = document.querySelector('#tableCategories');
                        tableCategories_tbody.innerHTML = resultObj.data;
                    }
                } else if (resultObj.dataType == "deleteCategory") {

                    const tableCategories_tbody = document.querySelector('#tableCategories');
                    tableCategories_tbody.innerHTML = resultObj.data;
                } else if (resultObj.dataType == "updateCategory") {
                    displayEditForm();
                }
                const tableCategories_tbody = document.querySelector('#tableCategories');
                tableCategories_tbody.innerHTML = resultObj.data;
            }
        }
    }

    /**
     * delete a category
     */
    function deleteCategory(idCategory) {
        data = idCategory;
        const objData = {
            data: data,
            dataType: "deleteCategory",
        };
        sendDataAjax(objData);
    }

    /**
     * display the form to add a category
     */
    function displayForm() {
        const formCat_div = document.querySelector(".formCat");
        formCat_div.classList.toggle("showFormCat");
    }

    /**
     * display the form to add a category
     */
    function displayEditForm(idCategory = null, nameCategory = null) {
        const formCat_div = document.querySelector(".formEditCat");
        formCat_div.classList.toggle("show");

        if (idCategory !== null && nameCategory !== null) {
            const inputEditCat_input = document.getElementById('inputEditCat');
            inputEditCat_input.value = nameCategory;
            inputEditCat_input.setAttribute('idCat', idCategory);
            const btnSubmit = document.getElementById('btnEditCat');
        }
    }

    /**
     * collect the new category name
     */
    function collectDataEditCat() {
        const inputEditCat_input = document.getElementById('inputEditCat');
        const idCategory = inputEditCat_input.getAttribute("idCat");
        const newNameCategory = inputEditCat_input.value;

        const objData = {
            idCategory: idCategory,
            nameCategory: newNameCategory,
            dataType: "updateCategory",
        };
        inputEditCat_input.value = "";
        sendDataAjax(objData);
    }

    /**
     * collect the category name
     */
    function collectDataCat() {
        const categoryAdd_input = document.getElementById("inputAddCat");

        if (
            categoryAdd_input.value.trim() == "" ||
            !isNaN(categoryAdd_input.value.trim())
        ) {
            alert("Entrez un nom de categorie valide !");
        } else {
            const data = categoryAdd_input.value.trim();
            const objData = {
                data: data,
                dataType: "addCategory",
            };
            categoryAdd_input.value = "";
            sendDataAjax(objData);
        }
    }
</script>
<?php $this->view("inc/footer", $data); ?>