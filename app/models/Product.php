<?php

class Product
{
    private $error = "";

    /**
     * create
     * insert a product into the BDD
     * @return void
     */
    public function create()
    {
        $db = Database::getInstance();
        $data = array();

        $data['nameProduct'] = validateData($_POST['name']);
        $data['descriptionProduct'] = validateData($_POST['description']);
        $data['priceProduct'] = validateData($_POST['price']);
        $data['priceProduct'] = (int)$data['priceProduct'];
        $data['stockProduct'] = validateData($_POST['stock']);
        $data['stockProduct'] = (int)$data['stockProduct'];
        $data['idCategoryProduct'] = validateData($_POST['category']);
        $data['idCategoryProduct'] = (int)$data['idCategoryProduct'];

        $data['imageProduct'] = $_FILES['image']['name'];

        if (empty($data['nameProduct'])) {
            $this->error .= "Veuillez entrez un nom de produit valide. <br>";
        }

        if (empty($data['descriptionProduct'])) {
            $this->error .= "Veuillez entrez une description de produit. <br>";
        }

        if (empty($data['priceProduct'])) {
            $this->error .= "Veuillez entrez un prix de produit valide. <br>";
        }

        if (empty($data['stockProduct'])) {
            $this->error .= "Veuillez entrez un stock de produit valide. <br>";
        }

        if (empty($data['idCategoryProduct'])) {
            $this->error .= "Veuillez entrez une catégorie de produit. <br>";
        }

        if (empty($data['imageProduct'])) {
            $this->error .= "Veuillez choisir une image de produit. <br>";
        }

        if ($this->error == "") {
            $nameImage = $this->getRandomString(5) . '_' . $data['imageProduct'];
            $data['imageProduct'] = $nameImage;

            $directory = $_SERVER['DOCUMENT_ROOT'] . ROOT_PATH . "public/assets/img/products/" . $nameImage;
            copy($_FILES['image']['tmp_name'], $directory);
            $query = "INSERT INTO product (nameProduct, descriptionProduct, imageProduct, priceProduct, stockProduct, idCategoryProduct) 
            VALUES (:nameProduct, :descriptionProduct, :imageProduct, :priceProduct, :stockProduct, :idCategoryProduct)";

            $result = $db->write($query, $data);
            if ($result) {
                echo "OK";
            } else {
                echo "pas ok";
            }
        }
        $_SESSION['error'] = $this->error;
    }

    /**
     * getRandomString
     * return a randon string
     * @param  int $length
     * @return string
     */
    private function getRandomString($length)
    {
        $array = range('a', 'z');
        $text = "";
        $length = rand(4, $length);

        for ($i = 0; $i < $length; $i++) {
            $random = rand(0, count($array) - 1);
            $text .= $array[$random];
        }
        return $text;
    }


    /**
     * getAllProducts
     * select all the products in the BDD
     * @return array
     */
    public function getAllProducts()
    {
        $db = Database::getInstance();
        $query = "SELECT * FROM product INNER JOIN category ON product.idCategoryProduct = category.idCategory";
        $data = $db->read($query);
        return $data;
    }

    /**
     * getOneProduct
     * select all the datas about one product
     * @param  int $idProduct
     * @return array
     */
    public function getOneProduct($idProduct)
    {
        $arr['idProduct'] = $idProduct;
        $db = Database::getInstance();
        $query = "SELECT * FROM product  INNER JOIN category ON product.idCategoryProduct = category.idCategory WHERE idProduct = :idProduct";
        $data = $db->read($query, $arr);
        return $data;
    }

    /**
     * makeSelectCategories
     * make html elements for the form add product
     * @param  arrays $categories
     * @return string HTML
     */
    public function makeSelectCategories($categories)
    {
        $selectHTML = "";
        foreach ($categories as $category) {
            $selectHTML .= '<option value="' . $category->idCategory . '">' . $category->nameCategory . '</option>';
        }
        return $selectHTML;
    }

    /**
     * makeTable
     * make the product HTML table for admin products view
     * @param  array $products
     * @return string HTML elements
     */
    public function makeTable($products)
    {
        $tableHTML = "";
        if (is_array($products)) {
            foreach ($products as $product) {
                $tableHTML .= '<tr>
                            <th scope="row">' . $product->idProduct . '</th>
                            <td>' . $product->nameProduct . '</td>
                            <td>' . $product->descriptionProduct . '</td>
                            <td>' . $product->priceProduct . ' </td>
                            <td>' . $product->stockProduct . '</td>
                          
                            
                            <td>' . $product->nameCategory . '</td>
                            <td><button class="btn btn-primary"><a href=products/update/' . $product->idProduct . '>Modifier</a></button></td>
                            <td><button class="btn btn-warning"><a href=deleteProduct/' . $product->idProduct . '>Supprimer</a></button></td>
                        </tr>';
            }
        }
        return $tableHTML;
    }

    /**
     * makeFrontProducts
     * return HTML elements for the products page Front office
     * @param  array $products
     * @return string HTML
     */
    public function makeFrontProducts($products)
    {
        $html = "";

        if (is_array($products)) {
            foreach ($products as $product) {
                $html .= '<div class="col-12 col-sm-6 col-lg-4 my-3">
                            <div class="card">
                                <img width=90% src="' . ASSETS . 'img/products/' . $product->imageProduct . '"  style="height:200px; width:100%;object-fit: cover;">
                               
                                <div class="card-body">
                                    <h5 class="card-title">' . $product->nameProduct . '</h5>
                                    <p class="card-text"' . $product->descriptionProduct . '</p>
                                    <a href="' . ROOT . 'products/details/' . $product->idProduct . '" class="btn btn-info">Voir plus</a>
                                </div>
                            </div>
                        </div>';
            }
        }
        return $html;
    }

    /**
     * deleteProduct
     * delete one product in the BDD
     * @param  int $idProduct
     * @return void
     */
    public function deleteProduct($idProduct)
    {
        $db = Database::getInstance();
        $db->write("DELETE FROM product WHERE idProduct = $idProduct");
        header("Location: " . ROOT . "admin/products");
    }

    /**
     * updateProduct
     * update one product in the BDD
     * @param  int $idProduct
     * @return void
     */
    public function updateProduct($idProduct)
    {
        $db = Database::getInstance();
        $data = array();

        $data['nameProduct'] = validateData($_POST['name']);
        $data['descriptionProduct'] = validateData($_POST['description']);
        $data['priceProduct'] = validateData($_POST['price']);
        $data['priceProduct'] = (int)$data['priceProduct'];
        $data['stockProduct'] = validateData($_POST['stock']);
        $data['stockProduct'] = (int)$data['stockProduct'];
        $data['idCategoryProduct'] = validateData($_POST['category']);
        $data['idCategoryProduct'] = (int)$data['idCategoryProduct'];

        $data['imageProduct'] = $_FILES['image']['name'];

        if (empty($data['nameProduct'])) {
            $this->error .= "Veuillez entrez un nom de produit valide. <br>";
        }

        if (empty($data['descriptionProduct'])) {
            $this->error .= "Veuillez entrez une description de produit. <br>";
        }

        if (empty($data['priceProduct'])) {
            $this->error .= "Veuillez entrez un prix de produit valide. <br>";
        }

        if (empty($data['stockProduct'])) {
            $this->error .= "Veuillez entrez un stock de produit valide. <br>";
        }

        if (empty($data['idCategoryProduct'])) {
            $this->error .= "Veuillez entrez une catégorie de produit. <br>";
        }

        if (empty($data['imageProduct'])) {
            $this->error .= "Veuillez choisir une image de produit. <br>";
        }

        if ($this->error == "") {
            $nameImage = $this->getRandomString(5) . '_' . $data['imageProduct'];
            $data['imageProduct'] = $nameImage;
            $data['idProduct'] =  (int)$idProduct;

            $directory = $_SERVER['DOCUMENT_ROOT'] . ROOT_PATH . "public/assets/img/products/" . $nameImage;
            copy($_FILES['image']['tmp_name'], $directory);

            $query = "UPDATE product SET nameProduct = :nameProduct, descriptionProduct = :descriptionProduct, imageProduct = :imageProduct, stockProduct = :stockProduct, priceProduct = :priceProduct, idCategoryProduct = :idCategoryProduct WHERE idProduct = :idProduct";
            $result = $db->write($query, $data);
            if ($result) {
                header("Location: " . ROOT . "admin/products");
                die;
            }
        }
        $_SESSION['error'] = $this->error;
    }
}
